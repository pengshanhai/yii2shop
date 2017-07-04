<?php
/* @var $this yii\web\View */
?>
<h1>用户表</h1>
<table class="table">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>角色</th>
        <th>操作</th>
    </tr>
    <?php foreach($model as $userList):?>
    <tr>
        <td><?=$userList->id?></td>
        <td><?=$userList->username?></td>
        <td><?=$userList->email?></td>
        <td>
            <select>
                <?php
                foreach (\yii::$app->authManager->getRolesByUser($userList->id) as $role){
                    echo '<option>'.$role->description.'</option>';
                }
                ?>
            </select>
        </td>
        <td>
            <?=yii\bootstrap\Html::a('修改用户角色',['alter','id'=>$userList->id],['class'=>'btn btn-warning btn-xs']).yii\bootstrap\Html::a('删除用户角色',['delete','id'=>$userList->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>

