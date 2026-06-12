<?php

namespace app\modules\account\controllers;

use app\models\Basket;
use app\models\BasketItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccountBasketController implements the CRUD actions for Basket model.
 */
class AccountBasketController extends Controller
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
     * Lists all Basket models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $basket = Basket::findOne(['user_id' => Yii::$app->user->id]);

        $dataProviderItems = new ActiveDataProvider([
            'query' => BasketItem::find()
                ->where(['basket_item.basket_id' => $basket?->id ?? 0]),
        ]);

        return $this->render('index', [
            'basket' => $basket,
            'dataProviderItems' => $dataProviderItems,
        ]);
    }

    /**
     * Displays a single Basket model.
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

    public function actionAdd($product_id)
    {
        $model = Basket::findOne(['user_id' => Yii::$app->user->id]) ?? Basket::create();
        $model->addItem($product_id);
        return $this->asJson(true);
    }

    public function actionDec($item_id)
    {
        $model = Basket::findOne(['user_id' => Yii::$app->user->id]);
        $model->addDec($item_id);
        return $this->asJson(true);
    }

    public function actionDelete($item_id)
    {
        $model = BasketItem::findOne(['id' => $item_id]);
        if ($model) {
            if ($basket = Basket::findOne($model->basket_id)) {
                $basket->amount -= $model->amount;
                $basket->sum -= $model->sum;
                if ($basket->save()) {
                    $model->delete();
                }
            }
        }
        return $this->asJson(true);
    }

    public function actionClear($id)
    {
        $model = Basket::findOne($id);
        if ($model) {            
            $model->delete();
        }
        return true;
    }

    public function actionGetCount()
    {
        return $this->asJson(Basket::getCount());
    }

    /**
     * Finds the Basket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Basket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Basket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
