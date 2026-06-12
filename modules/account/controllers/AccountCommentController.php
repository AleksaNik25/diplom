<?php

namespace app\modules\account\controllers;

use app\models\Comment;
use app\models\Rating;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccountCommentController implements the CRUD actions for Comment model.
 */
class AccountCommentController extends Controller
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
     * Lists all Comment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find()
                ->with(['product'])
                ->where(['user_id' => Yii::$app->user->id]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('view', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Comment();

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

    public function actionWrite($product_id, $parent_id = null)
    {
        if (!$model = Comment::findOne([
            'product_id' => $product_id,
            'user_id' => Yii::$app->user->id,
            'parent_id' => $parent_id
        ])) {
            $model = new Comment();
            $model->product_id = $product_id;
            $model->parent_id = $parent_id; 
        }

        if (!$parent_id) {
            $rating = Rating::findOne([
                'user_id' => Yii::$app->user->id,
                'product_id' => $product_id,
            ]);
            if ($rating) $model->user_stars = $rating->estimation;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id = Yii::$app->user->id;
                if (!$model->isNewRecord) $model->updated_at = date("Y-m-d H:i:s");

                if ($model->save()) {
                    if (!$parent_id && $model->user_stars > 0) {
                        $rating = Rating::findOne([
                            'user_id' => Yii::$app->user->id,
                            'product_id' => $product_id,
                        ]) ?: new Rating();

                        $rating->user_id = Yii::$app->user->id;
                        $rating->product_id = $product_id;
                        $rating->estimation = $model->user_stars;
                        $rating->save();
                    }

                    return $this->redirect(['/catalog/view', 'id' => $model->product->id, '#' => "comment-{$model->id}"]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionGetReply($id)
    {
        $model = Comment::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        if (!$model) throw new \yii\web\ForbiddenHttpException('Нет доступа.');
        return $this->asJson(['text' => $model->text]);
    }

    public function actionEdit($id)
    {
        if ($model = $this->findModel($id)) {
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    $model->updated_at = date("Y-m-d H:i:s");
                    if ($model->save()) {
                        return $this->redirect("view");
                    }
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['view']);
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $product_id = $model->product_id;
        $currentUser = Yii::$app->user->identity;

        if ($model->user_id !== $currentUser->id && !$currentUser->isAdmin) {
            throw new \yii\web\ForbiddenHttpException('Нет доступа.');
        }

        if (!$model->parent_id) {
            Rating::deleteAll([
                'user_id' => $model->user_id,
                'product_id' => $product_id,
            ]);
        }

        $model->delete();
        return $this->redirect(['/catalog/view', 'id' => $product_id]);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
