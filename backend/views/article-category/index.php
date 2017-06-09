<?php
/* @var $this yii\web\View */

?>
<h1>文章分类</h1>

<table class="table">
    <tr>
        <th>ID</th>
        <th>类型</th>
        <th>名称</th>
        <th>状态</th>
        <th>排序</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $brandList):?>
        <tr>
            <td><?=$brandList->id?></td>
            <td><?=$brandList->is_help?></td>
            <td><?=$brandList->name?></td>
            <td><?=\backend\models\Brand::$statusList[$brandList->status]?></td>
            <td><?=$brandList->sort?></td>
            <td><?=$brandList->intro?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['brand/alter','id'=>$brandList->id],['class'=>'btn btn-warning btn-xs']) ?>
                <?=yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brandList->id],['class'=>'btn btn-danger btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
