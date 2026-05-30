<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\Basket;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php $this->beginBody() ?>

    <header id="header" class="mb-4">
        <?php
        NavBar::begin([
            'brandLabel' => ' Вершки и корешки',
            // <img class="logo" src="../web/img/logo.svg" alt="logo">
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar bg fixed-top']
        ]);
        ?>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="d-flex flex-grow-1 justify-content-center navbar-nav">
                <div class="d-flex align-items-center gap-4">
                    <?php # Html::a('Главная', ['/site/index'], ['class' => 'navigation-style']) 
                    ?>
                    <?= Html::a('Каталог', ['/catalog'], ['class' => 'navigation-style']) ?>

                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity?->isAdmin): ?>
                        <?= Html::a('Панель управления', ['/admin'], ['class' => 'navigation-style']) ?>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->identity?->isClient): ?>
                        <?= Html::a('Личный кабинет', ['/account'], ['class' => 'navigation-style']) ?>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->identity?->isSeller): ?>
                        <?= Html::a('Личный кабинет продавца', ['/seller'], ['class' => 'navigation-style']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex align-items-center gap-4 ms-auto">
                <?= Html::a(
                    Yii::$app->user->isGuest ? 'Вход' : 'Выход (' . Yii::$app->user->identity->login . ')',
                    Yii::$app->user->isGuest ? ['/site/login'] : ['/site/logout'],
                    [
                        'class' => 'navigation-style',
                        'data-method' => Yii::$app->user->isGuest ? null : 'post'
                    ]
                ) ?>

                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('Регистрация', ['/site/register'], ['class' => 'navigation-style']) ?>
                <?php endif; ?>

                <?php
                if (Yii::$app->user->identity?->isClient): ?>
                    <div class="d-flex">
                        <?= Html::a('<i class="fas fa-shopping-basket text-dark"></i>', '/account/account-basket') ?>
                        <div class="mx-2 text-dark">(<span id="basket-items-count"><?= Basket::getCount() ?></span>)
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <?php
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-grow-1" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-5" style=" left: 0; bottom: 40px; width: 100%; z-index: 1030;">
        <div class="container py-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-4">
                <nav class="footer-nav d-flex flex-wrap gap-4">
                    <?= Html::a('Главная', ['/site/index'], ['class' => 'footer-link']) ?>
                    <?= Html::a('Каталог', ['/catalog'], ['class' => 'footer-link']) ?>
                </nav>

                <div class="footer-contacts">
                    Контакты: <br>
                    <?= Html::a('+7 999 888-77-66', 'tel:+79998887766', ['class' => 'footer-contact-link']) ?> <br>
                    <?= Html::a(
                        'vershki-and-koreshki_info@mail.ru',
                        'https://e.mail.ru/compose/?mailto=vershki-and-koreshki_info@mail.ru',
                        ['class' => 'footer-contact-link', 'target' => '_blank', 'rel' => 'noopener noreferrer']
                    ) ?>
                </div>
            </div>

            <div class="footer-copyright text-center">&copy; Вершки и корешки <?= date('Y') ?> <br> Все права защищены</div>
        </div>
    </footer>


    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>