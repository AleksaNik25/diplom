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

class AccountOrderController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel  = new AccountOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses'     => Assist::getColsItems(Status::tableName(), ['title', 'alias']),
            'status_order' => Status::getStatusesAlias(),
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        $dataProviderItems = new ActiveDataProvider([
            'query' => OrderItem::find()->where(["order_id" => $id]),
        ]);

        return $this->render('view', [
            'model'            => $model,
            'dataProviderItems' => $dataProviderItems,
            'statuses'         => Assist::getColsItems(Status::tableName(), ['title', 'alias']),
            'order'            => $model,
        ]);
    }

    public function actionCreate($basket_id)
    {
        $basket = Basket::findOne($basket_id);

        $payType = PayType::getPayType();

        $dataProviderItems = new ActiveDataProvider([
            'query' => BasketItem::find()->where(['basket_item.basket_id' => $basket_id]),
        ]);

        // Создаём модель Order для формы и валидации
        $model = new Order();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $orderId = Order::createOrder(
                    $basket_id,
                    (int) $model->pay_type_id,
                    $model->address,
                    $model->phone,
                    $model->date,
                    $model->time,
                );
                if ($orderId) {
                    return $this->redirect(['view', 'id' => $orderId]);
                }
            }
            // Если валидация не прошла — модель с ошибками вернётся во вью
        }

        return $this->render('create', [
            'basket'            => $basket,
            'dataProviderItems' => $dataProviderItems,
            'payType'           => $payType,
            'model'             => $model,
        ]);
    }

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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
