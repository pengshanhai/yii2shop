<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12
 * Time: 15:17
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new \yii\web\JsExpression(<<<EOF
            function(file, errorCode, errorMsg, errorString) {
                console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
            }
EOF
        ),
        'onUploadSuccess' => new \yii\web\JsExpression(<<<EOF
            function(file, data, response) {
                data = JSON.parse(data);
                if (data.error) {
                    console.log(data.msg);
                } else {
                    console.log(data.fileUrl);
                    //将上传成功后的图片地址(data.fileUrl)写入img标签
                    $("#img_logo").attr("src",data.fileUrl).show();
                    //将上传成功后的图片地址(data.fileUrl)写入logo字段
                    $("#goods-logo").val(data.fileUrl);
                }
            }
EOF
         ),
    ]
]);
if($model->logo){
    echo \yii\helpers\Html::img($model->logo,['height'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
}
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'brand_id')->dropDownList(yii\helpers\ArrayHelper::map($brand,'id','name'),['prompt' => '请选择品牌']);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale')->radioList(\backend\models\Goods::$is_on_sale);
echo $form->field($model,'status')->radioList(\backend\models\Goods::$status);
echo $form->field($model,'sort');
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);
//echo $form->field($goods_intro,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$date=\yii\helpers\Json::encode($goods_category);
$js=new \yii\web\JsExpression(<<<js
    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
                onClick: zTreeOnClick
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes ={$date};
    function zTreeOnClick(event, treeId, treeNode) {
            $('#goods-goods_category_id').val(treeNode.id);
        };
    $(document).ready(function(){
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);
        var node = zTreeObj.getNodeByParam("id", $('#goods-goods_category_id').val(), null);
        zTreeObj.selectNode(node);
    });
js
);
$this->registerJs($js);