<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use yii\filters\AccessControl;

class UserController extends \yii\web\Controller
{
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
            \yii::$app->session->setFlash('success','增加用户成功！');
            return $this->redirect(['index']);
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionAlter(){

    }
    public function actionDelete(){

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
    public function behaviors()
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
    }
}