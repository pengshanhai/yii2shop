<?php
/* @var $this yii\web\View */
?>
<h1>品牌列表</h1>

<table class="table">
    <tr>
        <th>ID</th>
        <th>logo</th>
        <th>名称</th>
        <th>状态</th>
        <th>排序</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $brandList):?>
        <tr>
            <td><?=$brandList->id?></td>
            <td><?=$brandList->logo?\yii\bootstrap\Html::img($brandList->logo,['width'=>60]):''?></td>
            <td><?=$brandList->name?></td>
            <td><?=\backend\models\Brand::$statusList[$brandList->status]?></td>
            <td><?=$brandList->sort?></td>
            <td><?=$brandList->intro?></td>
            <td><?=yii\bootstrap\Html::a('修改',['brand/alter','id'=>$brandList->id],['class'=>'btn btn-warning btn-xs']) ?> <?=yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brandList->id],['class'=>'btn btn-danger btn-xs']) ?></td>
        </tr>
    <?php endforeach;?>
</table>
