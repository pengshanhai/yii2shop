<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends BackendController
{
    public function actionIndex()
    {
        $model=GoodsCategory::find()->orderBy(['tree'=>SORT_ASC,'lft'=>SORT_ASC])->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionTest(){
        //创建一级分类
//        $countries = new GoodsCategory(['name' => 'Countries','parent_id'=>0]);
//        $countries->makeRoot();
//        var_dump($countries);
//创建子分类
        $countries=GoodsCategory::findOne(['id'=>1]);
        $russia = new GoodsCategory(['name' => 'Russia','parent_id'=>$countries->id]);
        $russia->prependTo($countries);
    }
    public function actionAdd(){
        $model=new GoodsCategory();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->parent_id){
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{
                $model->makeRoot();
            }
            \yii::$app->session->setFlash('success','增加成功');
            return $this->redirect(['index']);
        }
        $countries=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->all());
        return $this->render('edit',['model'=>$model,'countries'=>$countries]);
    }
    public function actionAlter($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在！');
        }
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            if($model->parent_id){
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{
                //判断他是不是顶级分类，是的话就保存，不然要异常错误
                if($model->getOldAttribute('parent_id')==0){
                    $model->save();
                }else{
                    $model->makeRoot();
                }
            }
            \yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['index']);
        }
        $countries=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->all());
        return $this->render('edit',['model'=>$model,'countries'=>$countries]);
    }
}
