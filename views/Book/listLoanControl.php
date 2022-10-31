<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Seguimiento de préstamos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $arrayBooks,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header' => 'Usuario solicitante',
                'attribute' => 'Usuario',
                'format' => 'text',
            ],
            [
                'header' => 'Libro solicitado',
                'attribute' => 'nameBook',
                'format' => 'text',
            ],
            [
                'header' => 'Fecha solicitud',
                'attribute' => 'dateStart',
                'format' => 'text',
            ],
            [
                'header' => 'Libro',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img('@web/' . $data['image'], ['width' => '80px']);
                },
            ],
        ],
    ]); ?>
</div>