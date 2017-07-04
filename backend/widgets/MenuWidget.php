<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18
 * Time: 15:25
 */
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Widget;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii;

class MenuWidget extends Widget{
    //widget被实例化后执行的代码
    public function init(){
        parent::init();
    }
    //widget被调用时，需要执行的代码
    public function run(){
        NavBar::begin([
            'brandLabel' => '京西商城管理系统',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['user/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => Yii::$app->user->loginUrl];
        } else {
            //根据用户的权限显示菜单
            /*$menuItems[] = ['label'=>'用户管理','items'=>[
                ['label'=>'添加用户','url'=>['admin/add']],
                ['label'=>'用户列表','url'=>['admin/index']]
            ]];*/
            $menus=Menu::findAll(['parent_id'=>0]);
            foreach($menus as $menu){
                $item=['label'=>$menu->label,'items'=>[]];
                //var_export($menu->children);exit;
                foreach($menu->children as $child){
                    //根据用户权限判断，该菜单是否显示
                    if(yii::$app->user->can($child->url)){
                        $item['items'][]=['label'=>$child->label,'url'=>[$child->url]];
                    }
                }
                //如果该一级菜单没有子菜单，就不显示
                if(!empty($item['items'])){
                    $menuItems[] = $item;
                }
            }
            $menuItems[] = ['label' => '注销('.Yii::$app->user->identity->username.')', 'url' => ['user/logout']];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}