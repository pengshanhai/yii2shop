<h1>商品列表</h1>
<?php
/* @var $this yii\web\View */
//echo \yii\bootstrap\Html::beginForm(\yii\helpers\Url::to(['goods/index']),'get');
//echo \yii\bootstrap\Html::textInput('name');
//echo \yii\bootstrap\Html::textInput('sn');
//echo \yii\bootstrap\Html::submitButton('搜索');
//\yii\bootstrap\Html::endForm();
echo \yii\bootstrap\Html::beginForm(\yii\helpers\Url::to(['goods/index']),'get');
echo \yii\bootstrap\Html::textInput('name');
echo \yii\bootstrap\Html::textInput('sn');
echo \yii\bootstrap\Html::submitButton('搜索');
echo \yii\bootstrap\Html::endForm();
?>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>图片</th>
            <th>名称</th>
            <th>编号</th>
            <th>市场价格</th>
            <th>商品价格</th>
            <th>是否在售</th>
            <th>状态</th>
            <th>库存</th>
            <th>排序</th>
            <th>品牌</th>
            <th>分类</th>
            <th>增加时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $goodsList):?>
            <tr>
                <td><?=$goodsList->id?></td>
                <td><?=\yii\bootstrap\Html::img('@web'.$goodsList->logo,['width'=>60])?></td>
                <td><?=$goodsList->name?></td>
                <td><?=$goodsList->sn?></td>
                <td><?=$goodsList->market_price?></td>
                <td><?=$goodsList->shop_price?></td>
                <td><?=$goodsList->is_on_sale?'是':'否'?></td>
                <td><?=$goodsList->status?'正常':'回收站'?></td>
                <td><?=$goodsList->stock?></td>
                <td><?=$goodsList->sort?></td>
                <td><?=$goodsList->brand->name?></td>
                <td><?=$goodsList->goodsCategory->name?></td>
                <td><?=date('Y-m-d H:i:s',$goodsList->create_time)?></td>
                <td><?=yii\bootstrap\Html::a('修改',['alter','id'=>$goodsList->id],['class'=>'btn btn-warning btn-xs']) ?> <?=yii\bootstrap\Html::a('查看',['intro','id'=>$goodsList->id],['class'=>'btn btn-default btn-xs']) ?> <?=yii\bootstrap\Html::a('删除',['delete','id'=>$goodsList->id],['class'=>'btn btn-danger btn-xs']) ?></td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
////分页工具条
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$page,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页',
//]);