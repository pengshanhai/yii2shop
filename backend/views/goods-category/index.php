<h1>商品分类表</h1>

<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>级别</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $goodsCategroyList):?>
        <tr>
            <td><?=$goodsCategroyList->id?></td>
            <td><?=str_repeat('－',$goodsCategroyList->depth).$goodsCategroyList->name?></td>
            <td><?=$goodsCategroyList->parent_id?></td>
            <td><?=$goodsCategroyList->intro?></td>
            <td>
                <?=yii\bootstrap\Html::a('修改',['alter','id'=>$goodsCategroyList->id],['class'=>'btn btn-warning btn-xs']) ?>
                <?=yii\bootstrap\Html::a('删除',['delete','id'=>$goodsCategroyList->id],['class'=>'btn btn-danger btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
