<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->titulo;
// $this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($autor) {
    ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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

    <table>
        <tr>
            <td>
                <p>Votos: <?= $model->votos ?></p>
                <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $model->id ?>">
                  <span class="glyphicon glyphicon-thumbs-up"></span>
                </button>
                <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $model->id ?>">
                  <span class="glyphicon glyphicon-thumbs-down"></span>
                </button>
                <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $model->id ?>">
                  <span class="glyphicon glyphicon-comment"></span>
                </button>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px;"><?= Html::img('@web/uploads/' . $model->id . '-resized.' . $model->extension);?></td>
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
