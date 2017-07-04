<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 14:10
 */
namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\base\Object;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers;

class ApiController extends Controller{
    public $enableCsrfValidation = false;
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //会员api
    public function actionAddUser(){
        $request=\yii::$app->request;
        if($request->isPost){
            $model= new Member();
            $model->username = $request->post('username');
            $model->repassword = $request->post('repassword');
            $model->password = $request->post('password');
            $model->email = $request->post('email');
            $model->tel = $request->post('tel');
            if($model->validate()){
                $model->save();
                return ['status'=>'1','msg'=>'','data'=>$model->toArray()];
            }
            //验证失败
            return ['status'=>'-1','msg'=>$model->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请求方式错误'];
    }
    public function actionLogin(){
        $request=\yii::$app->request;
        if($request->isPost){
            $model=new LoginForm();
            $model->username=$request->post('username');
            $model->password=$request->post('password');
            $model->rememberMe=$request->post('rememberMe');
            if($model->validate()){
                if($model->login()){
                    return ['status'=>'1','msg'=>'登录成功'];
                }
                return ['status'=>'-1','msg'=>$model->getErrors()];
            }
        }
        return ['status'=>'-1','msg'=>'请求方式错误'];
    }
    public function actionAlterPassword(){
        $request=\yii::$app->request;
    }
    public function actionGainUser(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请登录！'];
        }
        return ['status'=>'1','msg'=>'','data'=>\yii::$app->user->identity->toArray()];
    }

    //收货地址api
    public function actionAddAddress(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请登录！'];
        }
        $request=\yii::$app->request;
        if($request->isPost){
            $model=new Address();
            $model->name=$request->post('name');
            $model->province=$request->post('province');
            $model->city=$request->post('city');
            $model->county=$request->post('county');
            $model->detail=$request->post('detail');
            $model->tel=$request->post('tel');
            $model->member_id=\yii::$app->user->id;
            $model->is_default=$request->post('is_default')?$request->post('is_default'):0;
            if($model->validate()){
                $model->save();
                return ['status'=>'1','msg'=>'','data'=>$model->toArray()];
            }
            return ['status'=>'-1','msg'=>$model->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请求方式错误'];
    }
    public function actionModifyAddress(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请登录！'];
        }


        $request=\yii::$app->request;
        if($request->isGet) {
            if ($id = $request->get('id')) {
                $model = Address::find()->where(['id' => $id])->asArray()->all();
                return ['status' => 1, 'msg' => '', 'data' => $model];
            } else {
                return ['status' => '-1', 'msg' => '参数不正确'];
            }
        }else{//post

            $post = \Yii::$app->request->post();
            $model = Address::findOne($post['id']);
            $model->bhdjq=$post['bshca'];
            $model->bhdjq=$post['bshca'];
            $model->bhdjq=$post['bshca'];
            $model->bhdjq=$post['bshca'];
            $model->bhdjq=$post['bshca'];
        }
        return ['status'=>'-1','msg'=>'请求方式错误'];

    }
//获取地址的数据
    public function actionGetAddressinfo($id){
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请登录！'];
        }
        $model = Address::find()->where(['id' => $id])->asArray()->all();
        if($model) {
            return ['status' => 1, 'msg' => '', 'data' => $model];
        }
        return ['status' => '-1', 'msg' => '没有地址信息'];
    }
}