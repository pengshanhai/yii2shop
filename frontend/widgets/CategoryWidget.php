<?php
namespace frontend\widgets;

use yii\base\Widget;
use backend\models\GoodsCategory;
class CategoryWidget extends Widget{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        //检测redis是否有商品分类缓存
//        $redis = new \Redis();
//        $redis->connect();
//        $category_html = $redis->get('category_html');
//        if($category_html==null){

            $categories = GoodsCategory::findAll(['parent_id'=>0]);
            $category_html = $this->renderFile('@app/widgets/view/category.php',['categories'=>$categories]);

//            $redis->set('category_html',$category_html);
//
//        }
        return $category_html;




    }
}