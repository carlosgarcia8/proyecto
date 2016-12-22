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

    <table class="table table-condensed" style="width: 500px; margin: 0 auto;">
        <?php foreach ($posts as $post) {
    ?>
    <tr>
        <td><h1><?= Html::encode($post->titulo) ?></h1></td>
    </tr>
    <tr>
        <td><img src="<?= $post->imageurl ?>" class="img-rounded"></td>
    </tr>
    <?php

} ?>

    </table>
</div>
