<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17
 * Time: 10:21
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'roles')->checkboxList(\backend\models\UserForm::getRolesName());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();