<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\LoginForm;
use backend\models\User;
use yii\filters\AccessControl;

class UserController extends \yii\web\Controller
{
    public function behaviors()
        {
            return [
                'rbac'=>[
                    'class'=>RbacFilter::className(),
                    'only'=>['add','alter','delete'],
                ]
            ];
        }
    public function actionInit()
    {

        $admin = new User();
        $admin->username = 'admin';
        $admin->password = '12345678';
        $admin->email = 'admin@admin.com';
        $admin->auth_key = \Yii::$app->security->generateRandomString();
        $admin->save();
        return $this->redirect(['user/login']);
        //注册完成后自动帮用户登录账号
        //\Yii::$app->user->login($admin);
    }
    public function actionIndex()
    {
        $model=User::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new User();
//        if(\yii::$app->request->isPost){
//            $model->load(\yii::$app->request->post());
//
//            if($model->validate()){
//                $model->save();exit;
//            }
//        }
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            //$model->password_hash=\yii::$app->security->generatePasswordHash($model->password_hash);
            $model->save();
            if($model->addUser($model->id)){
                \yii::$app->session->setFlash('success','增加用户角色成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionAlter($id){
        $model=User::findOne(['id'=>$id]);
        //var_dump($model->addData($id));exit;
        $model->addData($id);
        //var_dump($model);exit;
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->alterUser($id)){
                \yii::$app->session->setFlash('success','修改用户角色成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=User::findOne(['id'=>$id]);
        $model->delete();
        \yii::$app->authManager->revokeAll($id);
        \yii::$app->session->setFlash('success','删除用户角色成功');
        return $this->redirect(['user/index']);
    }

    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            //var_dump($model->login());exit;
            if($model->login()){
                \yii::$app->session->setFlash('success','登录成功！');
                return $this->redirect(['index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
    /*public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['alter','delete','index','add'],//该过滤器作用的操作 ，默认是所有操作
                'rules'=>[
                    [//未认证用户允许执行view操作
                        'allow'=>true,//是否允许执行
                        'actions'=>[''],//指定操作
                        'roles'=>['?'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    [//已认证用户允许执行add操作
                        'allow'=>true,//是否允许执行
                        'actions'=>['alter','delete','index','add'],//指定操作
                        'roles'=>['@'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    //其他都禁止执行
                ]
            ],
        ];
    }*/
}
