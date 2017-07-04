<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use backend\models\Goods;
use yii\web\Cookie;

class FlowController extends Controller{
    public $layout='flow';
    //添加到购物车
    public function actionAdd()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
                //$cart = [2=>10];
            }


            //将商品id和数量存到cookie   id=2 amount=10  id=1 amount=3
            $cookies = Yii::$app->response->cookies;
            /*$cart=[
                ['id'=>2,'amount'=>10],['id'=>1,'amount'=>3]
            ];*/
            //检查购物车中是否有该商品,有，数量累加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
//            $cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);



        }else{

            //已登录 操作数据库
            $goods=Cart::findOne(['member_id'=>Yii::$app->user->getId(), 'goods_id'=>$goods_id]);
            if($goods){
                $goods->amount+=$amount;
                $goods->save();
            }else{
                $model=new Cart();
                $model->member_id=Yii::$app->user->getId();
                $model->goods_id=$goods_id;
                $model->amount=$amount;
                $model->save();
            }
        }
        return $this->redirect(['flow/cart']);
    }
    //购物车
    public function actionCart()
    {
        if(Yii::$app->user->isGuest) {
            //取出cookie中的商品id和数量
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
            //var_dump($models);exit;

        }else{

            //从数据库获取购物车数据
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if (!$cookie) {
                //cookie中没有购物车数据
                $cart = [];
                $member_carts=Cart::findAll(['member_id'=>Yii::$app->user->getId()]);
                foreach($member_carts as $member_cart){
                    $cart[$member_cart->goods_id]=$member_cart->amount;
                }
            } else {
                $cart = unserialize($cookie->value);
                //$cart['12'] =3;

                foreach($cart as $good_id => $amount){
                    $goods=Cart::findOne(['member_id'=>Yii::$app->user->getId(), 'goods_id'=>$good_id]);
                    if($goods){
                        $goods->amount+=$amount;
                        $goods->save();
                    }else{
                        $model=new Cart();
                        $model->member_id=Yii::$app->user->getId();
                        $model->goods_id=$good_id;
                        $model->amount=$amount;
                        $model->save();
                    }

                }
                $member_carts=Cart::findAll(['member_id'=>Yii::$app->user->getId()]);
                foreach($member_carts as $member_cart){
                    $cart[$member_cart->goods_id]=$member_cart->amount;
                }
            }
            $models = [];
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
            //保存到数据库成功删除cookie
            \yii::$app->response->cookies->remove('cart');
        }
        return $this->render('flow1', ['models' => $models]);
    }
    public function actionUpdateCart()
    {
        $goods_id = Yii::$app->request->post('goods_id');
        $amount = Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(Yii::$app->user->isGuest){
            //未登录
            //先获取cookie中的购物车数据
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
                //$cart = [2=>10];
            }


            //将商品id和数量存到cookie   id=2 amount=10  id=1 amount=3
            $cookies = Yii::$app->response->cookies;
            /*$cart=[
                ['id'=>2,'amount'=>10],['id'=>1,'amount'=>3]
            ];*/
            //检查购物车中是否有该商品,有，数量累加
            /*if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }*/
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
            }

//            $cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);



        }else{
            //已登录  修改数据库里面的购物车数据
            $model=Cart::findOne(['member_id'=>Yii::$app->user->getId(), 'goods_id'=>$goods_id]);
            if($amount) {
                $model->amount = $amount;
                $model->save();
            }else{
                $model->delete();
            }
        }

    }
    public function actionFlow2(){
        $carts=Cart::findAll(['member_id'=>Yii::$app->user->getId()]);
        //var_dump($carts);exit;
        $models = [];
        foreach ($carts as $cart) {
            //var_dump($good_id);
            $goods = Goods::findOne(['id' => $cart->goods_id])->attributes;
            $goods['amount'] = $cart->amount;
            $models[] = $goods;
        }
        //exit;
        $address=Address::find()->where(['member_id'=>Yii::$app->user->getId()])->orderBy(['is_default'=>SORT_DESC,'id'=>SORT_ASC])->all();
        return $this->render('flow2',['address'=>$address,'models'=>$models]);
    }

    public function actionAddOrder(){
        $model=new Order();
        //继续获取其他属性的值
        $address_id = Yii::$app->request->post('address_id');
        $address = Address::findOne(['id'=>$address_id,'member_id'=>Yii::$app->user->id]);
        $delivery_id=Yii::$app->request->post('delivery_id');
        foreach(Order::$delivery as $delivery_intro){
            if($delivery_intro['id']==$delivery_id){
                $delivery_name=$delivery_intro['name'];
                $delivery_price=$delivery_intro['price'];
            }
        }
        $payment_id=Yii::$app->request->post('payment_id');
        foreach(Order::$payment as $payment_intro){
            if($payment_intro['id']==$payment_id){
                $payment_name=$payment_intro['name'];
            }
        }
        $total=Yii::$app->request->post('total');
        if($address == null){
            throw new NotFoundHttpException('地址不存在');

        }
        $model->member_id=Yii::$app->user->id;
        $model->name=$address->name;
        $model->province = $address->province;
        $model->city=$address->city;
        $model->area=$address->county;
        $model->address=$address->detail;
        $model->tel=$address->tel;
        $model->delivery_id=$delivery_id;
        $model->delivery_name=$delivery_name;
        $model->delivery_price=$delivery_price;
        $model->payment_id=$payment_id;
        $model->payment_name=$payment_name;
        //还是要在后台计算总金额，前台提交的数据可以改
        $model->total=$total;
        $model->status=1;
        $model->create_time=time();
        //回滚--事务--innnodb存储引擎

        //开启事务
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->save();
            //$model->id;//保存后就有id属性


            //订单商品详情表
            //根据购物车数据，把商品的详情查询出来，逐条保存
            $carts = Cart::findAll(['member_id'=>Yii::$app->user->id]);
            foreach($carts as $cart){
                $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                if($goods==null){
                    //商品不存在
                    throw new Exception($goods->name.'商品已售完');
                }
                if($goods->stock < $cart->amount){
                    //库存不足
                    throw new Exception($goods->name.'商品库存不足');
                }
                $order_goods = new OrderGoods();
                $order_goods->order_id= $model->id;
                $order_goods->goods_id=$goods->id;
                $order_goods->goods_name=$goods->name;
                $order_goods->logo=$goods->logo;
                $order_goods->price=$goods->shop_price;
                $order_goods->amount=$cart->amount;
                $order_goods->total = $order_goods->price*$order_goods->amount;
                $order_goods->save();
                //扣库存 //扣减该商品库存
                $goods->stock -= $cart->amount;
                $goods->save();
                //删除购物车数据
                Cart::deleteAll(['member_id'=>Yii::$app->user->id]);
            }
            //提交
            $transaction->commit();
            return Json::encode(['status'=>'success','msg'=>'操作成功！']);
        }catch (Exception $e){
            //回滚
            $transaction->rollBack();
            return Json::encode(['status'=>'error','msg'=>$e->getMessage()]);
        }

    }

    public function actionFlow3(){
        return $this->render('flow3');
    }
    public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['flow3','flow2'],//该过滤器作用的操作 ，默认是所有操作
                'rules'=>[
                    [//未认证用户允许执行view操作
                        'allow'=>true,//是否允许执行
                        'actions'=>[''],//指定操作
                        'roles'=>['?'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    [//已认证用户允许执行add操作
                        'allow'=>true,//是否允许执行
                        'actions'=>['flow3','flow2'],//指定操作
                        'roles'=>['@'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    //其他都禁止执行
                ]
            ],
        ];
    }
}