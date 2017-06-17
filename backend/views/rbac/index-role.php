
<table class="table">
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <td>权限</td>
        <th>操作</th>
    </tr>
    <?php foreach($model as $roleList):?>
        <tr>
            <td><?=$roleList->name?></td>
            <td><?=$roleList->description?></td>
            <td>
                <select>
                    <?php
                        foreach (Yii::$app->authManager->getPermissionsByRole($roleList->name) as $permission){
                            echo '<option>'.$permission->description.'</option>';
                        }
                    ?>
                </select>
            </td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['alter-role','name'=>$roleList->name],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['delete-role','name'=>$roleList->name],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>