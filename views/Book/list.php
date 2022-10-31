<?php

use yii\data\Pagination;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\LinkPager;
use yii\web\View;

?>
<h1 class="py-5 text-center">Libros disponibles</h1>

<?php if ($pagination) : ?>
    <div class="row pt-2">
        <div class="col text-end">
            <h5>Paginado: </h5>
        </div>
        <div class="col">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <?php foreach ($arrayBooks as $positionBook => $infoBook) : ?>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="card" style="width: 18rem;">
                <?= Html::img("@web/" . "{$infoBook->image}", ['class' => "card-img-top", 'title' => Html::encode("{$infoBook->nameBook}")]) ?>
                <div class="card-body col-lg">
                    <h5 class="card-title"><?= Html::encode("{$infoBook->nameBook}") ?></h5>

                    <p class="card-text text-break" style="text-align: justify;"><?= Html::encode("{$infoBook->synopsis}") ?></p>

                    <?php if ($infoBook->available == 1) : ?>
                        <h6 class="text-center"><span class="badge bg-success"><b>Libro disponible</b></span></h6>
                        <div class="text-center">
                            <?php if (isset(Yii::$app->user->identity->ID)) : ?>
                                <?= Html::a('Solicitar préstamo', ['loanbook', 'idBook' => $infoBook->idBook], [
                                    'class' => 'btn btn-primary',
                                    'data' => [
                                        'confirm' => '¿Está seguro que desea adquirir el libro seleccionado?',
                                        'method' => 'post',
                                    ]
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <h6 class="text-center"><span class="badge bg-warning"><b>Libro no disponible</b></span></h6>
                        <?php if (isset(Yii::$app->user->identity->ID)) : ?>
                            <?php foreach ($loanBooks as $positionLoanBook => $infoLoanBook) : ?>
                                <?php if (isset($infoLoanBook['idBook'])) : ?>
                                    <?php if ($infoLoanBook['idBook'] == $infoBook->idBook) : ?>
                                        <div class="text-center">
                                            <?= Html::a('Devolver libro', ['returnbook', 'idBook' => $infoBook->idBook], [
                                                'class' => 'btn btn-primary',
                                                'data' => [
                                                    'confirm' => '¿Está seguro que desea devolver el libro seleccionado?',
                                                    'method' => 'post',
                                                ]
                                            ]) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>