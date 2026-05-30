<?php

namespace app\modules\seller\controllers;

use app\models\Category;
use app\models\Comment;
use app\models\Product;
use app\models\Rating;
use app\models\Status;
use app\models\User;
use app\modules\seller\models\SellerProductSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * SellerProductController implements the CRUD actions for Product model.
 */
class SellerProductController extends Controller
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
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SellerProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->where(['user_id' => Yii::$app->user->id]),

            'pagination' => [
                'pageSize' => 8
            ],
        ]);

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
        $user = User::findOne(Yii::$app->user->id);
        if ($user->userLE && $user->userLE->approval == 2) {
            Yii::$app->session->setFlash('error', 'Ваш аккаунт заблокирован. Создание товаров недоступно.');
            return $this->redirect(['index']);
        }

        $model = new Product();
        $model->scenario = Product::SCENARIO_CREATE;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id = Yii::$app->user->id;
                $model->status_id = Status::getStatusId('check');
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

                // проверка наличия фото при создании
                if (empty($model->imageFiles)) {
                    $model->addError('imageFiles', 'Необходимо загрузить хотя бы одно фото товара.');
                    return $this->render('create', ['model' => $model]);
                }

                if ($model->save()) {
                    // Сохраняем подкатегории через product_category
                    $categoryIds = $this->request->post('category_ids', []);
                    $model->saveCategories($categoryIds);

                    if ($model->upload()) {
                        Yii::$app->session->setFlash('success', 'Продукт отправлен на рассмотрение администратору');
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        VarDumper::dump($model->errors, 10, true);
                        die;
                    }
                } else {
                    VarDumper::dump($model->errors, 10, true);
                    die;
                }
            } else {
                VarDumper::dump($model->attributes, 10, true);
                die;
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            // 'category' => $category,
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
        $model->scenario = Product::SCENARIO_UPDATE;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $uploadedFiles = UploadedFile::getInstances($model, 'imageFiles');
                // Присваиваем только если реально загружены файлы
                $model->imageFiles = !empty($uploadedFiles) ? $uploadedFiles : [];
                $model->status_id = Status::getStatusId('check');

                if ($model->save()) {
                    $categoryIds = $this->request->post('category_ids', []);
                    $model->saveCategories($categoryIds);

                    $model->upload();
                    Yii::$app->session->setFlash('success', 'Товар обновлён и отправлен на повторное рассмотрение');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    VarDumper::dump($model->errors, 10, true);
                    die;
                }
            }
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

    public function actionChangeStatus($id, $status)
    {
        $model = $this->findModel($id);

        if ($model) {
            $model->status_id = Status::getStatusId($status);

            if (!$model->save(false)) {
                VarDumper::dump($model->errors, 10, true);
                die;
            }

            Yii::$app->session->setFlash("toast", ["status" => "info", "text" => "Статус товара №$model->id изменен на " . Status::getStatuses()[$model->status_id]]);
        }

        return $this->actionIndex();
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
