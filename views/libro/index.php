<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?= Html::style(
    'div.required label.control-label:after {
        content: " *";
        color: red;
    }'
) ?>
<?= Html::style('div.help-block:before { color: #f00 !important; }', ['media' => 'print']) ?>

<?php
$form = ActiveForm::begin([
    'id' => 'book-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
<?= $form->field($libroModel, 'name')->textInput(['autofocus' => true])->hint('Por favor, ingrese el nombre del libro') ?>
<?= $form->field($libroModel, 'folio')->hint('Por favor, ingrese el folio del libro') ?>
<?= $form->field($libroModel, 'editorial')->hint('Por favor, ingrese el nombre de la editorial') ?>
<?= $form->field($libroModel, 'autor')->hint('Por favor, ingrese el nombre del autor') ?>
<?= $form->field($libroModel, 'sinopsis')->hint('Por favor, ingrese la sinopsis del libro') ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>