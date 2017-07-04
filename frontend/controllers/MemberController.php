<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\helpers\Url;

class MemberController extends \yii\web\Controller
{
    public $layout='member';
    public function actionAddMember(){
        $model=new Member();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->save();

            return $this->redirect(['index']);
        }
        return $this->render('regist',['model'=>$model]);
    }
    public function actionLogin()
    {
        //判断是游客就跳转到登录页面

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(\yii::$app->request->post()) && $model->validate()) {
            if ($model->login()) {
                //跳回登录之前的页面
                return $this->goBack();
            }
        }
        //var_dump(\Yii::$app->request->referrer);exit;
        //\Yii::$app->request->referrer这里面有跳到这里之前的地址。setReturnUrl是把地址记录起来goBack用
        \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);
        return $this->render('login', ['model' => $model]);
    }
    public function actionIndex()
    {
        //var_dump(\yii::$app->user->getIdentity());
        return $this->redirect(['content/index']);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['index']);
    }
    public function actionSend(){
        // 配置信息
        $config = [
            'app_key'    => '24478989',//阿里大于的应用列表设置里面的appkey
            'app_secret' => 'a687cfcb742d91ba83e0eb8415775dea',//阿里大于的应用列表设置里面的App Secret
            // 'sandbox'    => true,  // 是否为沙箱环境（测试环境，不发短信），默认false
        ];
        Client::configure($config);  // 全局定义配置（定义一次即可，无需重复定义）

        $resp = Client::request('alibaba.aliqin.fc.sms.num.send', function (IRequest $req) {
            $req->setRecNum('18881810622')//客户的手机号码
                ->setSmsParam([
                    'code' => rand(1000, 9999)//随机四位数字
                ])
                ->setSmsFreeSignName('彭善海')//短信签名名称
                ->setSmsTemplateCode('SMS_71685111');//配置短信模版的id
        });

        // 返回结果打印，成功返回一个对象，失败返回false
        print_r($resp);
        print_r($resp->result->model);
    }
}
