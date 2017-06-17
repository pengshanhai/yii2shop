<?php
/* @var $this yii\web\View */
?>
<h1>文章表</h1>
<?=\yii\bootstrap\Html::a('增加文章',['add'],['class'=>'btn btn-primary'])?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>分类名</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>增加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $articleList):?>
    <tr>
        <td><?=$articleList->id?></td>
        <td><?=$articleList->name?></td>
        <td><?=$articleList->article_category_id?></td>
        <td><?=$articleList->intro?></td>
        <td><?=$articleList->sort?></td>
        <td><?=$articleList->status?></td>
        <td><?=date('Y-m-d h:i:s',$articleList->inputtime)?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['alter','id'=>$articleList->id],['class'=>'btn btn-warning btn-xs'])?></td>
    </tr>
    <?php endforeach;?>
</table>
