<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;


/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?= Html::style('textarea: { resize:none; }') ?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($model, 'nameBook')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'editorial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'synopsis')->textarea(['maxlength' => true]) ?>

    <?= Html::img('@web/' . $model->image, ['width' => '80px']); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group pt-3">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>