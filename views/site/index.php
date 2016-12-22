<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Proyecto';
?>
<div class="site-index">
    <?php Yii::$app->response->redirect(['posts/index']); ?>
</div>
