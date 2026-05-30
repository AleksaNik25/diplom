<?php

namespace app\controllers;

use app\models\Product;
use app\models\CatalogSearch;
use app\models\Comment;
use app\models\Rating;
use app\models\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * CatalogController implements the CRUD actions for Product model.
 */
class CatalogController extends Controller
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

    public function actionStars($id)
    {
        if ($product = Product::findOne(["id" => $id])) {
            $userId = Yii::$app->user->id;
            $rating = Rating::findOne(['user_id' => $userId, 'product_id' => $id]);

            if (!$rating) {
                $model = new Rating();
                $model->user_id = $userId;
                $model->product_id = $id;
                $model->estimation = $this->request->post('estimation');
                if ($model->save()) {
                    return $this->asJson(true);
                }
            }
            return $this->asJson(false);
        }
        return $this->asJson(false);
    }

    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CatalogSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // $dataProvider = new ActiveDataProvider([
        //     'query' =>
            
        //     Product::find()->where(['status_id' => Status::getStatusId('on sale')]),

        //     'pagination' => [
        //         'pageSize' => 24
        //     ],
        //     /*
        //     'sort' => [
        //         'defaultOrder' => [
        //             'id' => SORT_DESC,
        //         ]
        //     ],
        //     */
        // ]);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $id №
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find()
                ->where(['product_id' => $model->id, 'parent_id' => null])
                ->with(['replies.user', 'replies.replies.user', 'replies.replies.replies.user', 'user'])
                ->orderBy(['created_at' => SORT_DESC]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 5
            ],
        ]);

        $stars = Rating::find()
            ->where(['user_id' => Yii::$app->user->id, 'product_id' => $id])
            ->select('estimation')
            ->scalar();

            // VarDumper::dump($this->stars, 10, true); die;

        $stars = $stars ? (float)$stars : 0;

        $productModel = $this->findModel($id);
        $productModel->user_stars = $stars;

        $commentModel = new Comment();
        $commentModel->product_id = $id;

        return $this->render('view', [
            'model' => $productModel,        
            'dataProvider' => $dataProvider,
            'model_comment' => $commentModel,
            'stars' => $stars,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();

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
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id №
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
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id №
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id №
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
