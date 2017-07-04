<?php

namespace backend\controllers;

use app\components\Aliyunoss;
use backend\models\Brand;
//use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class BrandController extends BackendController
{
    public function actionIndex()
    {
        $model=Brand::find()->where(['!=','status',-1])->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model=new Brand();
        if($model->load(\yii::$app->request->post())){
           //$model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
//                if($model->imgFile){
//                    $imgName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\yii::getAlias('@webroot').$imgName,false);
//                    $model->logo=$imgName;
//                }
                    $model->save();
                    \yii::$app->session->setFlash('success','新增成功！');
                    return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionAlter($id){
        $model=Brand::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())){
            //$model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
//                if($model->imgFile){
//                    $imgName='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\yii::getAlias('@webroot').$imgName,false);
//                    $model->logo=$imgName;
//                }
                    $model->save();
                    \yii::$app->session->setFlash('success','修改成功！');
                    return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        \yii::$app->session->setFlash('success','删除成功！');
        return $this->redirect(['brand/index']);
    }
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //得到图片
                    $path = $action->getWebUrl();
                    //获取该文件在本地的绝对路径
                    $filepath = \Yii::getAlias('@webroot').'/'.$path;
                    //var_dump($filepath);exit;
                    //获取保存到阿里云oss的文件名$object  strrchr找到/最后出现的位置返回并返回/加后面的文件名，然后substr把/去除
                    $object = substr(strrchr($path, '/'), 1);
                    $aliyun = new Aliyunoss();
                    //执行上传,失败返回false
                    if($aliyun->upload($object,$filepath)){
                        //如果成功得到文件的外网路径
                        $logo = $aliyun->getUrl($object);
                        //赋值
                        $action->output['fileUrl'] = $logo;
                    }
                    //$action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }

}
