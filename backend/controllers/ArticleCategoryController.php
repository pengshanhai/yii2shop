<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class ArticleCategoryController extends BackendController
{
    public function actionIndex()
    {

        $model=ArticleCategory::find()->where(['!=','status',-1])->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new ArticleCategory();
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionAlter($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        return $this->redirect(['article-category/index']);
    }

}
