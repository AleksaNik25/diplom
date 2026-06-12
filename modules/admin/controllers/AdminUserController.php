<?php

namespace app\modules\admin\controllers;

use app\models\Order;
use app\models\OrderItem;
use app\models\Product;
use app\models\Status;
use app\models\User;
use app\models\UserLE;
use app\modules\admin\models\AdminUserSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * UserController implements the CRUD actions for User model.
 */
class AdminUserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdminUserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangeStatus($id)
    {
        $user = User::findOne($id);

        if (!$user || !$user->userLE) {
            Yii::$app->session->setFlash("error", "Пользователь не найден");
            return $this->redirect('/admin/admin-user');
        }

        $userLE = $user->userLE;

        if ($userLE->approval == 2) {
            $userLE->approval = 1;
            if ($userLE->save(false)) {
                Yii::$app->session->setFlash("success", "Продавец разблокирован и подтверждён");
            } else {
                Yii::$app->session->setFlash("error", "Ошибка разблокировки");
            }
            return $this->redirect('/admin/admin-user');
        }

        if ($userLE->approval == 0) {
            $userLE->approval = 1;
            if ($userLE->save(false)) {
                Yii::$app->session->setFlash("success", "Продавец подтверждён");
            } else {
                Yii::$app->session->setFlash("error", "Ошибка подтверждения");
            }
            return $this->redirect('/admin/admin-user');
        }

        if ($userLE->approval == 1) {
            $userLE->approval = 2;

            if (!$userLE->save(false)) {
                Yii::$app->session->setFlash("error", "Ошибка блокировки");
                return $this->redirect('/admin/admin-user');
            }

            // Архивируем все товары продавца
            $archivedStatusId = Status::getStatusId('arhived'); 
            $checkStatusId = Status::getStatusId('check');

            $products = Product::find()
                ->where(['user_id' => $user->id])
                ->andWhere(['!=', 'status_id', $archivedStatusId])
                ->all();

            $productIds = [];
            foreach ($products as $product) {
                $productIds[] = $product->id;
                $product->status_id = $archivedStatusId;
                $product->save(false);
            }

            // Отменяем заказы, содержащие товары заблокированного продавца
            if (!empty($productIds)) {
                $newStatusId = Status::getStatusId('new');
                $deliveryStatusId = Status::getStatusId('in delivery');
                $cancelStatusId = Status::getStatusId('canceled');

                $affectedOrderIds = OrderItem::find()
                    ->select('order_id')
                    ->where(['product_id' => $productIds])
                    ->column();

                if (!empty($affectedOrderIds)) {
                    $ordersToCancel = Order::find()
                        ->where(['id' => $affectedOrderIds])
                        ->andWhere(['status_id' => [$newStatusId, $deliveryStatusId]])
                        ->all();

                    foreach ($ordersToCancel as $order) {
                        $order->status_id = $cancelStatusId;
                        $order->save(false);
                    }
                }
            }

            Yii::$app->session->setFlash("success", "Продавец заблокирован. Его товары архивированы, затронутые заказы отменены.");
            return $this->redirect('/admin/admin-user');
        }

        Yii::$app->session->setFlash("error", "Неизвестный статус продавца");
        return $this->redirect('/admin/admin-user');
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
