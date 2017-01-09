<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use app\assets\AppAsset;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
?>
<script>
function upvote(id){
    var parametros = {
        "id" : id
    };

    $.ajax({
        data:  parametros,
        url:   '<?php echo Url::toRoute(['posts/upvote']) ?>',
        type:  'post',
        success:  function (response) {
                $('#' + id).html("Votos: " + response);
        }
    });
}

function downvote(id){
    var parametros = {
        "id" : id
    };

    $.ajax({
        data:  parametros,
        url:   '<?php echo Url::toRoute(['posts/downvote']) ?>',
        type:  'post',
        success:  function (response) {
                $('#' + id).html("Votos: " + response);
        }
    });
}
</script>
<div class="post-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <table style="width: 500px; margin: 0 auto; ">
        <?php foreach ($posts as $post) {
    ?>
    <tr>
        <td><h1><?= Html::encode($post->titulo) ?></h1></td>
    </tr>
    <tr>
        <td><?= Html::a(Html::img('' . Html::encode($post->imageurl), ['class' => 'img-rounded', 'style' => 'margin-bottom: 20px;']), ['posts/view', 'id' => $post->id]); ?></td>
    </tr>
    <tr>
        <td>
            <p id='<?= $post->id ?>'>Votos: <?= $post->getVotos() ?></p>
            <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $post->id ?>"
                onclick="upvote(<?= $post->id ?>)">
              <span class="glyphicon glyphicon-thumbs-up"></span>
            </button>
            <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $post->id ?>"
                onclick="downvote(<?= $post->id ?>)">
              <span class="glyphicon glyphicon-thumbs-down"></span>
            </button>
            <button type="button" class="btn btn-default btn-lg" aria-label="Left Align" value="<?= $post->id ?>">
              <span class="glyphicon glyphicon-comment"></span>
            </button>
        </td>
    </tr>
    <?php

} ?>
    </table>


</div>
