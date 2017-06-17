<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 15:17
 */
namespace backend\controllers;


use backend\models\PermissionForm;
use backend\models\RoleForm;
use backend\models\UserForm;
use yii\web\Controller;

class RbacController extends Controller{
    public function actionAddPermission(){
        $model=new PermissionForm();
            if($model->load(\yii::$app->request->post())&&$model->validate()){
                if($model->addPermission()){
                    \yii::$app->session->setFlash('success','增加权限成功');
                    return $this->redirect(['index-permission']);
                }
            }
        return $this->render('edit-permission',['model'=>$model]);
    }
    public function actionAlterPermission($name){
        $model=new PermissionForm();
        $model->addData($name);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->alterPermission($name)){
                \yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index-permission']);
            }
        }
        return $this->render('edit-permission',['model'=>$model]);
    }
    public function actionIndexPermission(){
        $model=\yii::$app->authManager->getPermissions();
        return $this->render('index-permission',['model'=>$model]);
    }
    public function actionDeletePermission($name){
        $permission=\yii::$app->authManager->getPermission($name);
        \yii::$app->authManager->remove($permission);
        \yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index-permission']);
    }
    public function actionAddRole(){
        $model=new RoleForm();
            if($model->load(\yii::$app->request->post())&&$model->validate()){
                if($model->addRole()){
                    \yii::$app->session->setFlash('success','增加角色成功');
                    return $this->redirect(['index-role']);
                }
            }
        return $this->render('edit-role',['model'=>$model]);
    }
    public function actionIndexRole(){
        $model=\yii::$app->authManager->getRoles();
        return $this->render('index-role',['model'=>$model]);
    }
    public function actionAlterRole($name){
        $model=new RoleForm();
        $model->addData($name);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->alterRole($name)){
                \yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['index-role']);
            }
        }

        return $this->render('edit-role',['model'=>$model]);
    }
    public function actionDeleteRole($name){
        $authManager=\yii::$app->authManager;
        $role=$authManager->getRole($name);
        $authManager->remove($role);
        \yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index-role']);
    }
    public function actionAddUser($id){
        $model=new UserForm();
        //var_dump(\backend\models\User::findOne(['id'=>$id]));exit;
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->addUser($id)){
                \yii::$app->session->setFlash('success','增加用户角色成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('edit-user',['model'=>$model]);
    }
    public function actionAlterUser($id){
        $model=new UserForm();
        $model->addData($id);
        //var_dump($model);exit;
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->alterUser($id)){
                \yii::$app->session->setFlash('success','修改用户角色成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('edit-user',['model'=>$model]);
    }
    public function actionIndexUser(){

    }
    public function actionDeleteUser($id){
        \yii::$app->authManager->revokeAll($id);
        \yii::$app->session->setFlash('success','删除用户角色成功');
        return $this->redirect(['user/index']);
    }
}