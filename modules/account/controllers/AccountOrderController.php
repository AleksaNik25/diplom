<?php

namespace app\modules\account\controllers;

use app\models\Assist;
use app\models\Basket;
use app\models\BasketItem;
use app\models\Order;
use app\models\OrderItem;
use app\models\PayType;
use app\models\Status;
use app\modules\account\models\AccountOrderSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * AccountOrderController implements the CRUD actions for Order model.
 */
class AccountOrderController extends Controller
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
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AccountOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => Assist::getColsItems(Status::tableName(), ['title', 'alias']),
            'status_order' => Status::getStatusesAlias(),
        ]);
    }

    /**
     * Displays a single Order model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $dataProviderItems = new ActiveDataProvider([
            'query' => OrderItem::find()
                ->where(["order_id" => $id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProviderItems' => $dataProviderItems,
            'statuses' => Assist::getColsItems(Status::tableName(), ['title', 'alias']),
            'order' => $model,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($basket_id)
    {
        $basket = Basket::findOne($basket_id);

        $payType = PayType::getPayType();

        $dataProviderItems = new ActiveDataProvider([
            'query' => BasketItem::find()
                ->where("basket_item.basket_id = $basket_id"),
        ]);

        if ($this->request->isPost) {
            $post = $this->request->post('Order'); 
            $orderId = Order::createOrder(
                $basket_id,
                (int) $post['pay_type_id'],
                $post['address'],
                $post['phone'],
                $post['date'],
                $post['time'],
            );
            if ($orderId) {
                return $this->redirect(['view', 'id' => $orderId]);
            }
        }
       
        return $this->render('create', [
            'basket' => $basket,
            'dataProviderItems' => $dataProviderItems,
            'payType' => $payType,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
