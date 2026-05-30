<?php

use app\models\Order;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\AdminOrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Панель администратора';

// VarDumper::dump($dataProvider, 10, true); die;
?>
<div class="admin-index d-flex gap-3 mb-4 mt-4">

    <p>
        <?= Html::a('Управление категориями', ['/admin/admin-category'], ['class' => 'btn btn-primary']) ?>
    </p>

    <p>
        <?= Html::a('Управление товарами', ['/admin/admin-product'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <p>
        <?= Html::a('Управление продавцами', ['/admin/admin-user'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a('Управление компаниями', ['/admin/admin-company'], ['class' => 'btn btn-outline-success']) ?>
    </p>

</div>

<?php

$this->title = 'Управление заказами';

?>

<div class="order-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => fn($model) => $this->render('item', [
            'model' => $model,
            'statuses' => $statuses,
            'status_order' => $status_order
        ]),
        'pager' => [
            'class' => LinkPager::class
        ],
    ]) ?>

    <?php Pjax::end(); ?>

</div>