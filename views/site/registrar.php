<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroForm */
/* @var $form ActiveForm */

$this->title = 'Registro';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-registrar">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['style'=>'width:300px']); ?>
        <?= $form->field($model, 'password')->passwordInput(['style'=>'width:300px']); ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-registrar -->
