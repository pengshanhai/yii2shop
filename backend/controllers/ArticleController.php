<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=Article::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new Article();
        $article_detail=new ArticleDetail();
        $article_category=ArticleCategory::find()->all();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->save();
            $article_detail->article_id=$model->id;
            $article_detail->content=$model->intro;
            $article_detail->save();
            \yii::$app->session->setFlash('success','增加成功！');
            return $this->redirect(['index']);
        }
        return $this->render('edit',['model'=>$model,'article_category'=>$article_category]);
    }
    public function actionAlter($id){
        $model=Article::findOne(['id'=>$id]);
        $article_detail=ArticleDetail::findOne(['article_id'=>$id]);
        $article_category=ArticleCategory::find()->all();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->save();
            $article_detail->content=$model->intro;
            $article_detail->save();
            \yii::$app->session->setFlash('success','修改成功！');
            return $this->redirect(['index']);
        }
        return $this->render('edit',['model'=>$model,'article_category'=>$article_category]);
    }
    public function actionDelete(){

    }
}
