<?php
namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    //记住我
    public $rememberMe;
    public $code;

    public function rules()
    {
        return [
            [['username','password'/*,'code'*/],'required'],
            ['rememberMe','boolean'],
            //['code','captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名：',
            'password'=>'密码：',
            'rememberMe'=>'保存登录信息',
            'code'=>'验证码：'
        ];
    }

    //用户登录
    public function login(){
        //1 根据用户名查找用户
        $user = Member::findOne(['username'=>$this->username]);
        if($user){
            //2 验证密码
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //3 登录
                //自动登录


                $user->last_login_time = time();
//                $user->touch('updated_at');这个要打开user模型里面的行为behaviors
                $user->last_login_ip=ip2long(\Yii::$app->request->userIP);

                $user->save(false);
                $duration = $this->rememberMe?7*24*3600:0;
                \Yii::$app->user->login($user,$duration);
                //var_dump($user->getErrors());exit;
                //return var_dump($user->getErrors());exit;
                return true;
            }else{
                $this->addError('password','密码不正确');
            }
        }else{
            $this->addError('username','用户名不存在');
        }
        return false;
    }
}