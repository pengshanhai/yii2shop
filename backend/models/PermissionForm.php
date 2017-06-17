<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 15:11
 */
namespace backend\models;

use yii\base\Model;
use yii\web\NotFoundHttpException;

class PermissionForm extends Model{
    public $name;
    public $description;

    public function rules(){
        return [
            [['name','description'],'required'],
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'权限名',
            'description'=>'描述',
        ];
    }
    public function addPermission(){
        $authManager=\yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;
            //保存数据表
            $authManager->add($permission);
            return true;
        }
        return false;
    }
    public function addData($name){
        $Permission=\yii::$app->authManager->getPermission($name);
        if($Permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $this->name=$Permission->name;
        $this->description=$Permission->description;
    }
    public function alterPermission($name){
        $authManager=\yii::$app->authManager;
        if($this->name!=$name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            $permission=$authManager->getPermission($name);
            $permission->name=$this->name;
            $permission->description=$this->description;
            $authManager->update($name,$permission);
            return true;
        }
        return false;
    }
}