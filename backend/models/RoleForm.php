<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 18:40
 */
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];
    public function rules(){
        return [
            [['name','description'],'required'],
            //可以为空的字段也必须有验证规则不然添加权限没法把数据写进$this->permissions[]数组里面
            ['permissions','safe'],//表示该字段不需要验证
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'角色名',
            'description'=>'角色描述',
            'permissions'=>'权限',
        ];
    }
    public static function getPermissionName(){
        return ArrayHelper::map(\yii::$app->authManager->getPermissions(),'name','description');
    }
    public function addRole(){
        $authManager=\yii::$app->authManager;
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已经存在');
        }else{
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            if($authManager->add($role)){//保存数据数据库
                foreach($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
                return true;
            }
        }
        return false;
    }
    public function addData($name){
        $authManager=\yii::$app->authManager;
        $role=$authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $this->name=$role->name;
        $this->description=$role->description;
        $permissions=$authManager->getPermissionsByRole($role->name);
        //return $permissions;
        foreach($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }
    public function alterRole($name){
        $authManager=\yii::$app->authManager;
        $role=$authManager->getRole($name);
        $role->name=$this->name;
        $role->description=$this->description;
        if($this->name!=$name&&$authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }else{
            if($authManager->update($name,$role)){
                $authManager->removeChildren($role);
                foreach($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
            }
            return true;
        }
        return false;
    }
}