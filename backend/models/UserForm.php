<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17
 * Time: 10:01
 */
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UserForm extends Model{
    //public $name;
    //public $description;
    public $roles=[];
    public function rules(){
        return [
            //[['name','description'],'required'],
            ['roles','safe']//表示该字段不需要验证
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'用户名',
            'description'=>'描述',
            'roles'=>'角色',
        ];
    }
    public static function getRolesName(){
        return ArrayHelper::map(\yii::$app->authManager->getRoles(),'name','description');
    }
    public function addUser($id){
        if(\backend\models\User::findOne(['id'=>$id])==null){
            throw new NotFoundHttpException('用户不存在');
        }else{
            $authManager=\yii::$app->authManager;
            foreach($this->roles as $roleName){
                $role= $authManager->getRole($roleName);
                $authManager->assign($role,$id);
            }
            return true;
        }
        return false;
    }
    public function addData($id){
        if(\backend\models\User::findOne(['id'=>$id])==null){
            throw new NotFoundHttpException('用户不存在');
        }else {
            $roles = \yii::$app->authManager->getRolesByUser($id);
            foreach ($roles as $role) {
                $this->roles = $role->name;
            }
        }
    }
    public function alterUser($id){
        if(\backend\models\User::findOne(['id'=>$id])==null){
            throw new NotFoundHttpException('用户不存在');
        }else{
            $authManager=\yii::$app->authManager;
            $authManager->revokeAll($id);
            foreach($this->roles as $roleName){
                $role= $authManager->getRole($roleName);
                $authManager->assign($role,$id);
            }
            return true;
        }
    }
}