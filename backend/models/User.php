<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $updated_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $roles=[];
    public $password;

    const SCENARIO_ADD = 'add';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['password','required','on'=>self::SCENARIO_ADD],
            //限制密码长度8-32位
            ['password','string','length'=>[6,32],'tooShort'=>'密码不能小于6位'],
            [['username', 'password', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['roles','safe']//表示该字段不需要验证
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_ip' => 'Updated Ip',
            'roles'=>'角色',
        ];
    }
//    public function behaviors(){
//        return [
//            'time'=>[
//                'class'=>TimestampBehavior::className(),
//                'attributes'=>[
//                    self::EVENT_BEFORE_INSERT=>['created_at'],
//                ]
//            ]
//        ];
//    }
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
            //return $roles;exit;
            foreach ($roles as $role) {
                $this->roles[] = $role->name;
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
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
//            $this->status = 1;
            //生成随机字符串用作自动登录令牌，写在里面是注册的时候保存了密文，不能自动改变，可以设置一段时间让它自动改变
//            $this->auth_key = Yii::$app->security->generateRandomString();
        }
         //生成随机字符串用作自动登录令牌，写在外面登录的时候就重写一次密文，缺点是两个电脑登录了，前一个电脑无法实现自动登录
        $this->auth_key = Yii::$app->security->generateRandomString();
        if($this->password){
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }



    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {

        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;

    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {

        return $this->getAuthKey() == $authKey;
    }

}
