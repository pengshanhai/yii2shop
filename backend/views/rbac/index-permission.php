
<table class="table">
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $permissionList):?>
        <tr>
            <td><?=$permissionList->name?></td>
            <td><?=$permissionList->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['alter-permission','name'=>$permissionList->name],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['delete-permission','name'=>$permissionList->name],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>