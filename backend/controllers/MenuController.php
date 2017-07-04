<?php

namespace backend\controllers;

use backend\models\Menu;

class MenuController extends BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd(){
        $model=new Menu();
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->parent_id=$model->parent_id?$model->parent_id:0;
            $model->save();
            \yii::$app->session->setFlash('success','增加菜单成功');
            return $this->redirect(['index']);
        }
        $parent=$model->find()->where(['=','parent_id',0])->all();
        return $this->render('edit',['model'=>$model,'parent'=>$parent]);
    }
    public function actionAlter($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            $model->parent_id=$model->parent_id?$model->parent_id:0;
            $model->save();
            \yii::$app->session->setFlash('success','修改菜单成功');
            return $this->redirect(['index']);
        }
        $parent=$model->find()->where(['=','parent_id',0])->all();
        return $this->render('edit',['model'=>$model,'parent'=>$parent]);
    }
    public function actionDelete($id){
        $model=Menu::findOne(['id'=>$id]);
        $model->delete();
        \yii::$app->session->setFlash('success','删除菜单成功');
        return $this->redirect(['index']);
    }

}
