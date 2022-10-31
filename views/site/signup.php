<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="py-5 site-signup">
    <h1>Creación de un nuevo usuario</h1>
    <div class="row">
        <div class="col-lg-5">
            <?php $form =  ActiveForm::begin(['action' => ['site/insert'], 'method' => 'post', 'id' => 'form-signup']) ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Registrar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>