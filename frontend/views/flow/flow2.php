<?php
$this->registerCssFile('@web/style/fillin.css');
$this->registerJsFile('@web/js/cart2.js',['depends'=>\yii\web\JqueryAsset::className()]);
use yii\helpers\Html;
?>

	<!-- 主体部分 start -->
	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>

		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
				<div class="address_info">
					<?php foreach($address as $k=>$addresList):?>
				<p <?=$k?'':"class='cur'"?>>
					<input type="radio" value="<?=$addresList->id?>" name="address_id" <?=$addresList->is_default?'checked':''?>/><?=$addresList->name.' '.$addresList->tel.' '.$addresList->province.'省 '.$addresList->city.'市 '.$addresList->county.' '.$addresList->detail?>
				</p>
					<?php endforeach;?>
				</div>


			</div>
			<!-- 收货人信息  end-->

			<!-- 配送方式 start -->
			<div class="delivery">
				<h3>送货方式 </h3>


				<div class="delivery_select">
					<table>
						<thead>
							<tr>
								<th class="col1">送货方式</th>
								<th class="col2">运费</th>
								<th class="col3">运费标准</th>
							</tr>
						</thead>
						<tbody id="deliver">
						<?php foreach(frontend\models\Order::$delivery as $k=>$delivery):?>
							<tr <?=$k?'':"class='cur'"?>>
								<td>
									<input type="radio" value="<?=$delivery['id']?>" name="delivery" <?=$k?'':'checked'?>/><?=$delivery['name']?>

								</td>
								<td class="yufei">￥<span><?=$delivery['price']?></span></td>
								<td><?=$delivery['intro']?></td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>

				</div>
			</div> 
			<!-- 配送方式 end --> 

			<!-- 支付方式  start-->
			<div class="pay">
				<h3>支付方式 </h3>


				<div class="pay_select">
					<table>
						<tbody id="pay_select">
						<?php foreach(frontend\models\Order::$payment as $k=>$payment):?>
							<tr <?=$k?'':"class='cur'"?>>
								<td class="col1"><input type="radio" value="<?=$payment['id']?>" name="pay" <?=$k?'':'checked'?>/><?=$payment['name']?></td>
								<td class="col2"><?=$payment['intro']?></td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>

				</div>
			</div>
			<!-- 支付方式  end-->

			<!-- 发票信息 start-->
			<div class="receipt none">
				<h3>发票信息 </h3>


				<div class="receipt_select ">
					<form action="">
						<ul>
							<li>
								<label for="">发票抬头：</label>
								<input type="radio" name="type" checked="checked" class="personal" />个人
								<input type="radio" name="type" class="company"/>单位
								<input type="text" class="txt company_input" disabled="disabled" />
							</li>
							<li>
								<label for="">发票内容：</label>
								<input type="radio" name="content" checked="checked" />明细
								<input type="radio" name="content" />办公用品
								<input type="radio" name="content" />体育休闲
								<input type="radio" name="content" />耗材
							</li>
						</ul>						
					</form>

				</div>
			</div>
			<!-- 发票信息 end-->

			<!-- 商品清单 start -->
			<div class="goods">
				<h3>商品清单</h3>
				<table>
					<thead>
						<tr>
							<th class="col1">商品</th>
							<th class="col3">价格</th>
							<th class="col4">数量</th>
							<th class="col5">小计</th>
						</tr>	
					</thead>
					<tbody>
					<?php foreach($models as $goodsList):?>
						<tr class="jisuan">
							<td class="col1"><a href=""><img src="<?='http://admin.yii2shop.com/'.$goodsList['logo']?>" alt="" /></a>  <strong><a href=""><?=$goodsList['name']?></a></strong></td>
							<td class="col3">￥<span><?=$goodsList['shop_price']?></span></td>
							<td class="col4"> <?=$goodsList['amount']?></td>
							<td class="col5">￥<span></span></td>
						</tr>
					<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<span>4 件商品，总商品金额：</span>
										<em>￥<span id="total"></span></em>
									</li>
									<!--<li>
										<span>返现：</span>
										<em>-￥240.00</em>
									</li>-->
									<li>
										<span>运费：</span>
										<em>￥<span  id="yunfei"></span></em>
									</li>
									<li>
										<span>应付总额：</span>
										<em>￥<span id="yingfu"></span></em>
									</li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-- 商品清单 end -->
		
		</div>

		<div class="fillin_ft">
			<a href="javascript:" id="add-order"><span>提交订单</span></a>
			<p>应付总额：<strong>￥<span id="zonge"></span></strong></p>
			
		</div>
	</div>
	<!-- 主体部分 end -->
<?php
$url = \yii\helpers\Url::to(['flow/add-order']);
$url2=\yii\helpers\Url::to(['flow/flow3']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
	<<<JS
           //监听提交按钮的点击事件
        $("#add-order").click(function(){
            //console.log($(this));
            var address_id =$(".address_info").find(".cur input").val();
            var delivery_id=$("#deliver").find(".cur").find("td input").val();
            var payment_id=$("#pay_select").find(".cur").find("td input").val();
            var total=$("#zonge").text();
            //发送ajax post请求到site/update-cart  {goods_id,amount}
            $.post("$url",{"total":total,"address_id":address_id,"delivery_id":delivery_id,"payment_id":payment_id,"_csrf-frontend":"$token"},function(data){
				if(data.status=='success'){
					$(location).attr('href', '$url2');
				}else{
					alert(data.msg);
				}
            },'json');
        });
JS

));