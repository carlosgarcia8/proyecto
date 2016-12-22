<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
?>
<div class="post-index">

    <h1 style="text-align:center;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'summary' => '',
        'columns' => [
            [
                'attribute' => 'titulo',
                'label' => false,
            ],
            [
                'attribute' => 'votos',
                'label' => false,
                'value' => function ($model) {
                    return 'Votos: ' . $model->votos;
                },
            ],
            array('format' => 'image','value'=>function ($data) {
                return $data->imageurl;
            },
            ),
            [
                'attribute' => 'usuario_id',
                'value' => 'usuario.nick',
                'label' => false,
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
