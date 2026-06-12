<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * AdminCategoryController implements the CRUD actions for Category model.
 */
class AdminCategoryController extends Controller
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
     * Lists all Category models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
            'pagination' => [
                'pageSize' => 15
            ],
            /*
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
     * Displays a single Category model.
     * @param int $id №
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($this->request->isPost) {
            $model->parent_id = null;
            $model->extend = null;

            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new subcategory (level 2) with optional extending subcategories (level 3).
     * @return string|\yii\web\Response
     */
    public function actionCreateSub()
    {
        $model = new Category();
        $subcategories = [new Category()];

        if ($this->request->isPost) {
            $post = $this->request->post();
            $subcategoryData = $post['Subcategory'] ?? [];

            $parentId = $subcategoryData['parent_id'] ?? null;
            $model->parent_id = $parentId;

            $saved = [];

            foreach ($subcategoryData as $key => $item) {
                if ($key === 'parent_id') continue;

                $title = trim($item['title'] ?? '');
                if ($title === '') continue;

                $sub = !empty($item['id']) ? Category::findOne($item['id']) : new Category();
                $sub->title     = $title;
                $sub->parent_id = $parentId;
                $sub->extend    = !empty($item['extend']) ? 1 : null;

                if ($sub->save()) {
                    $saved[] = $sub;
                } else {
                    VarDumper::dump($sub->errors, 10, true);
                }
            }

            if (!empty($saved)) {
                return $this->redirect(['index']);
            }

            $subcategories = array_values(array_map(function ($item) use ($parentId) {
                $s = new Category();
                $s->title = $item['title'] ?? '';
                $s->extend = !empty($item['extend']) ? 1 : null;
                $s->parent_id = $parentId;
                return $s;
            }, array_filter($subcategoryData, fn($k) => $k !== 'parent_id', ARRAY_FILTER_USE_KEY)));

            if (empty($subcategories)) {
                $subcategories = [new Category()];
            }
        }

        return $this->render('create-sub', [
            'model' => $model,
            'subcategories' => $subcategories,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id №
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $isRoot = $model->parent_id === null;
        $subcategories = [];

        if ($this->request->isPost) {
            $post = $this->request->post();

            if ($isRoot) {
                // Корневая: обновляем только title
                if ($model->load($post) && $model->save()) {
                    return $this->redirect(['index']);
                }
            } else {
                // Подкатегория: обновляем title, parent_id, extend
                // parent_id и extend приходят через Category[] — стандартный load()
                if ($model->load($post) && $model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'subcategories' => $subcategories,
        ]);
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id №
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
