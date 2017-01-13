<?php

use app\models\Comentario;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->titulo;
// $this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($esAutor) {
    ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' =>
            'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
<?php

} ?>

    <table class="table">
        <tr>
            <td>
                <p>Votos: <?= $model->getVotos() ?></p>
                <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $model->id ?>">
                  <span class="glyphicon glyphicon-thumbs-up"></span>
                </button>
                <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $model->id ?>">
                  <span class="glyphicon glyphicon-thumbs-down"></span>
                </button>
            </td>
        </tr>
        <tr>
            <td><?= Html::a(Html::img('' . Html::encode($model->imageurlResized), ['class' => 'img-rounded', 'style' => 'margin-bottom: 20px;']), ['posts/view', 'id' => $model->id]); ?></td>

            <td><?php
                $form = ActiveForm::begin();
                $nuevo = new Comentario; ?>

            <?= $form->field($nuevo, 'cuerpo')->textarea() ?>

            <?php ActiveForm::end(); ?></td>
            <?php foreach ($comentarios as $comentario) {
                    ?> <td><h4><?= $comentario->cuerpo ?></h4></td> <?php

                } ?>
        </tr>
    </table>



    <!-- DetailView::widget([
        'model' => $model,
        'attributes' => [
            'votos',
            [
                'label' => 'Autor',
                'value' => $model->usuario->nick,
            ]
        ],
        'options' => [
            'style' => 'width: 500px; ',
            'class' => 'table table-striped table-bordered detail-view',
        ],
    ]) ?> -->


</div>
