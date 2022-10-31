<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\SignUpForm $model */

$this->title = 'Editar usuario: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="py-5 user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form =  ActiveForm::begin(['action' => ['site/update'], 'method' => 'post', 'id' => 'form-update']) ?>
            <?= Html::hiddenInput('id', $model->id) ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>