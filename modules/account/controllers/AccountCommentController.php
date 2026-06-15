<?php

namespace app\modules\account\controllers;

use app\models\Comment;
use app\models\Rating;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AccountCommentController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find()
                ->with(['product', 'product.productImages'])
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

    /**
     * Редактирование комментария — работает и как AJAX (из «Мои отзывы»), и как обычный POST.
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        // Проверяем право на редактирование
        if ($model->user_id !== Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException('Нет доступа.');
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->updated_at = date("Y-m-d H:i:s");
                if ($model->save()) {
                    if (Yii::$app->request->isAjax) {
                        return $this->asJson(['success' => true]);
                    }
                    return $this->redirect(['view']);
                }
            }
            if (Yii::$app->request->isAjax) {
                return $this->asJson(['success' => false, 'errors' => $model->errors]);
            }
        }

        // GET-запрос: возвращаем данные для модального окна
        if (Yii::$app->request->isAjax) {
            return $this->asJson(['text' => $model->text, 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

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

    protected function findModel($id)
    {
        if (($model = Comment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
