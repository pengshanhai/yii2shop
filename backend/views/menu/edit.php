<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18
 * Time: 15:55
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'url');
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($parent,'id','label'),['prompt' => '请选择父菜单(默认父菜单)']);
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();