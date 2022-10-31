<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->nameBook;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1 class="py-3 text-center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Editar', ['update', 'idBook' => $model->idBook], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'idBook' => $model->idBook], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro que desea eliminar el libro seleccionado?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idBook',
            'nameBook',
            'editorial',
            'author',
            'synopsis',
        ],
    ]) ?>

</div>