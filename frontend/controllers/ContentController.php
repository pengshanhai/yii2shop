<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21
 * Time: 16:32
 */
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsIntro;
use yii\web\Controller;
use frontend\models\Address;
use yii\web\NotFoundHttpException;

class ContentController extends Controller{
    public $layout='content';
    public function actionAddress(){
        $model= new Address();
        $address=Address::find()->where(['=','member_id',\yii::$app->user->getId()])->all();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->member_id=\yii::$app->user->getId();
            $model->save();
            return $this->redirect(['address']);
        }
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }
    public function actionList($cate_id){
        $model=Goods::findAll(['goods_category_id'=>$cate_id]);
        return $this->render('list',['model'=>$model]);
    }
    public function actionIndex(){
        return $this->render('index');
    }
    public function actionGoods($goods_id){
        $goods=Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        $model=GoodsIntro::findOne(['goods_id'=>$goods_id]);
        return $this->render('goods',['model'=>$model,'goods'=>$goods]);
    }
}