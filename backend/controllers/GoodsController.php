<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use xj\uploadify\UploadAction;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $goods=Goods::find();
        if($name=\yii::$app->request->get('name')){
            $goods->andWhere(['like','name',$name]);
        }
        if($sn=\yii::$app->request->get('sn')){
            $goods->andWhere(['like','sn',$sn]);
        }
        $model=$goods->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionIntro($id){
        $model=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('intro',['model'=>$model]);
    }
    public function actionAdd(){

        $brand=Brand::find()->all();
        $goods_category=GoodsCategory::find()->all();
        $model=new Goods();
        $goods_intro=new GoodsIntro();
        if($model->load(\yii::$app->request->post())&&$model->validate()&&$goods_intro->load(\yii::$app->request->post())&&$goods_intro->validate()){
            //var_dump($model);exit;
            $date=date('Y-m-d');
            $goodsCount = new GoodsDayCount();
            if($goodsCount->findOne(['day'=>$date])==null){
                $goodsCount->day=$date;
                $goodsCount->count=0;
                $goodsCount->save();
            }
            //字符串长度补全
            //第一种方法substr('000'.($goodsCount->count+1),-4,4);
            //第二种方法str_pad这个函数也可以补零
            //"%'14d"除了零之外的补位要用’才可以
            $model->sn=date('Ymd').sprintf("%04d",$goodsCount->count+1);
            $model->save();
            $goods_intro->goods_id=$model->id;
            $goods_intro->save();
            /*$goodsCount->count++;
                $goodsCount->save();*/
            //updateAllCounters这个方法可以让count字段自加1
            GoodsDayCount::updateAllCounters(['count'=>1],['day'=>$date]);
            \yii::$app->session->setFlash('success','增加商品成功');
            return $this->redirect(['index']);
        }
        return $this->render('edit', ['model' => $model,'goods_intro'=>$goods_intro, 'brand' => $brand, 'goods_category' => $goods_category]);
    }
    public function actionAlter($id){
        $brand=Brand::find()->all();
        $goods_category=GoodsCategory::find()->all();
        $model=Goods::findOne(['id'=>$id]);
        if($model->load(\yii::$app->request->post())&&$model->validate()){
            //var_dump($model);exit;
            $model->sn=date('Ymd',time());
            $model->save();
            \yii::$app->session->setFlash('success','修改商品成功');
            return $this->redirect(['index']);
        }
        return $this->render('edit', ['model' => $model, 'brand' => $brand, 'goods_category' => $goods_category]);
    }
    public function actionDelete($id){

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
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                /*'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
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
//                    //得到图片
//                    $path = $action->getWebUrl();
//                    //获取该文件在本地的绝对路径
//                    $filepath = \Yii::getAlias('@webroot').'/'.$path;
//                    //var_dump($filepath);exit;
//                    //获取保存到阿里云oss的文件名$object  strrchr找到/最后出现的位置返回并返回/加后面的文件名，然后substr把/去除
//                    $object = substr(strrchr($path, '/'), 1);
//                    $aliyun = new Aliyunoss();
//                    //执行上传,失败返回false
//                    if($aliyun->upload($object,$filepath)){
//                        //如果成功得到文件的外网路径
//                        $logo = $aliyun->getUrl($object);
//                        //赋值
//                        $action->output['fileUrl'] = $logo;
//                    }
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],
        ];
    }
}
