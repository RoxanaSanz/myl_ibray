<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'My Library Application';
?>
<div class="pt-4 site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">¡Bienvenido!</h1>

        <p class="lead">
            Es un placer recibirte en <i><b>Hogar, dulce hogar</b></i>.
            <br>Prepárate para emprender viajes inesperados desde cualquier lugar, todo desde el alcance de un <i><b>libro</b></i>.
        </p>

    </div>

    <div class="body-content">

        <div class="row">
            <?= Html::img('@web/uploads/HDH.png') ?>
        </div>

    </div>
</div>