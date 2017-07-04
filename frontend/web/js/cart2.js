/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/
$(function(){
	//收货人修改
	$("#address_modify").click(function(){
		$(this).hide();
		$(".address_info").hide();
		$(".address_select").show();
	});

	$(".new_address").click(function(){
		$("form[name=address_form]").show();
		$(this).parent().addClass("cur").siblings().removeClass("cur");

	}).parent().siblings().find("input").click(function(){
		$("form[name=address_form]").hide();
		$(this).parent().addClass("cur").siblings().removeClass("cur");
	});
//地址变色
	$("input[name=address_id]").click(function(){
		$(this).parent().addClass("cur").siblings().removeClass("cur");
	});

	//送货方式修改
	$("#delivery_modify").click(function(){
		$(this).hide();
		$(".delivery_info").hide();
		$(".delivery_select").show();
	})

	$("input[name=delivery]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//支付方式修改
	$("#pay_modify").click(function(){
		$(this).hide();
		$(".pay_info").hide();
		$(".pay_select").show();
	})

	$("input[name=pay]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//发票信息修改
	$("#receipt_modify").click(function(){
		$(this).hide();
		$(".receipt_info").hide();
		$(".receipt_select").show();
	})

	$(".company").click(function(){
		$(".company_input").removeAttr("disabled");
	});

	$(".personal").click(function(){
		$(".company_input").attr("disabled","disabled");
	});
	//小计
	$(".jisuan").each(function(){
		var subtotal = parseFloat($(this).find(".col3 span").text()) * parseInt($(this).find(".col4").text());
		$(this).find(".col5 span").text(subtotal.toFixed(2));
	});
	//总计金额
	var total = 0;
	$(".col5 span").each(function(){
		total += parseFloat($(this).text());
	});
	$("#total").text(total.toFixed(2));
	//改变运费
	var deliver=$("#deliver").find(".cur").find(".yufei span").text();
	$("#yunfei").text(deliver);
	//默认运费加金额
	$("#zonge").text((parseFloat(total)+parseFloat(deliver)).toFixed(2));
	$("#yingfu").text((parseFloat(total)+parseFloat(deliver)).toFixed(2));
	//运费改变后的金额
	$("input[name=delivery]").change(function(){
		//获取改变后的运费
		deliver=$(this).closest("tr").find(".yufei span").text();
		$("#yunfei").text(deliver);
		//改变后的运费加金额
		$("#zonge").text((parseFloat(total)+parseFloat(deliver)).toFixed(2));
		$("#yingfu").text((parseFloat(total)+parseFloat(deliver)).toFixed(2));
	});
});