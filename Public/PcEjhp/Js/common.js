//文字的组装函数
 function ows(e)
	  {		  
				var ws=$(".word").val()+",";
				var   ws=ws+e;
				$(".word").val(ws);
		 }
		function SubWord()
	  {		  	var ws=$(".word").val();       //组装好的
				var UserWord=$(".UserWord").val();         //用户填写的
				var   ws=ws+","+UserWord;
				$(".word").val(ws);    //重新赋值到 隐藏域中
		 }
		 


//获取范围内的随机数
function random(min,max){
    return Math.floor(min+Math.random()*(max-min));
}
//切换函数
function hover_cur(btn,cnt,cur,ant){
	var btn = $(btn);  
	var cnt = $(cnt);
	var ant = $(ant);
	btn.mouseover(function(){
		var current = $(this).index();
		cnt.hide().eq(current).show();
		ant.hide().eq(current).show();
		if(cur != null){
			btn.removeClass(cur).eq(current).addClass(cur);
		}
	}).eq(0).trigger("mouseenter");
}

//修改商务通 好东西 误删。
function revise_link(){
	var LRdiv = $('#LRdiv1').html();
	var ims_src = $('#LRdiv1 img').eq(0).attr('src');
	if(ims_src != '/Public/PcEjhp/images/advice.png'){
		if(LRdiv != ''){
			$('#LRfloater1').attr('style','z-index: 2147483647; position: fixed ! important; left: 50%; margin-left: -200px; top: 50%; margin-top: -150px;_margin-top:-150px;_margin-left:-200px;_position:absolute;_left:expression(eval(document.documentElement.clientWidth/2+document.documentElement.scrollLeft));_top:expression(eval(document.documentElement.clientHeight/2+document.documentElement.scrollTop))');
			//修改商务通的内容
			var img_html = '<img src="/Public/PcEjhp/images/advice.png" usemap="#Map1LR"><map name="Map1LR"><area onclick="LR_RefuseChat();LR_HideInvite();;return false;" href="javascript:void(0)" coords="219,142,307,172" shape="rect"><area onclick="LR_RefuseChat();LR_HideInvite();;return false;" href="javascript:void(0)" coords="424,1,452,26" shape="rect"><area onclick="openZoosUrl();LR_HideInvite();;return false;" href="javascript:void(0)" coords="318,140,412,172" shape="rect"></map>';
			$('#LRfloater1').show().html(img_html);

		}
	}
}
$(document).ready(function(){
	//执行商务通链接修改
	setInterval(revise_link,1000);
	//悬浮
	$(".navframe").smartFloat();

	//返回顶部
	if(window.screen.width >= 1024){
		var scrollBtn_right = (window.screen.width - 1200) / 2 - $('.scrollBtn').width()-20;
		$('.scrollBtn').show().css({'right':scrollBtn_right});
		$(window).scroll(function() {
			if($(window).scrollTop() > 300){
				$('.scrollBtn a.goTop').show();
			}else{
				$('.scrollBtn a.goTop').hide();
			}
		});
		$("a.goTop").click(function(){
			$('body,html').animate({scrollTop:0},500);
			return false;
		});
	}
	//返回顶部end
	//在线订单
	$('.box-10-1 ul li').click(function(){
		var this_class = $(this).attr('class');
		var current = $(this).index() + 1;
		var commodity_price = parseInt($('.commodity_price em').html());
		var price = parseInt($(this).children('.info').children('.des').children('.price').html());
	    var total_price = parseInt($('.box-10-2 tr').eq(current).children('td').eq(3).children('.total_price').children('em').html());

		if(this_class == '' || this_class == undefined){
			$(this).addClass('hover');
			$('.box-10-2 tr').eq(current).show();
			$('.commodity_price em').html(commodity_price + price);
			$('#total').val(commodity_price + price);
			$('.box-10-2 tr').eq(current).children('td').eq(2).children('input').eq(0).val('1');
			$('.total_price').eq(current - 1).children('em').html(price);
		}else{
			$(this).removeClass('hover');
			$('.box-10-2 tr').eq(current).hide();
			if(commodity_price != 0){
				$('.commodity_price em').html(commodity_price - total_price);
				$('#total').val(commodity_price - total_price);
				
			}
			$('.box-10-2 tr').eq(current).children('td').eq(2).children('input').eq(0).val('0');
			$('.box-10-2 tr').eq(current).children('td').eq(2).children('input').eq(1).remove();
		}
		
	});
	
	$('.box-10-2 .plus').click(function(){
		var current = $(this).index();
		var siblings_num = parseInt($(this).siblings('.num').val());
		var plus_price = parseInt($(this).attr('rel'));
		var siblings_num = parseInt($(this).siblings('.num').val());
		var commodity_price = parseInt($('.commodity_price em').html());

		$(this).siblings('.num').val( siblings_num + 1);
		$('.commodity_price em').html(commodity_price + plus_price);
		$('#total').val(commodity_price + plus_price);
		
		if(plus_price == 488){
			var total_price = parseInt($('.total_price em').eq(0).html());
			$('.total_price em').eq(0).html(total_price + plus_price);
		}else if(plus_price == 976){
			var total_price = parseInt($('.total_price em').eq(1).html());
			$('.total_price em').eq(1).html(total_price + plus_price);
		}else if(plus_price == 1952){
			var total_price = parseInt($('.total_price em').eq(2).html());
			$('.total_price em').eq(2).html(total_price + plus_price);
		}else if(plus_price == 199){
			var total_price = parseInt($('.total_price em').eq(3).html());
			$('.total_price em').eq(3).html(total_price + plus_price);
		}

	})
	$('.minus').click(function(){
		var siblings_num = parseInt($(this).siblings('.num').val());
		var minus_price = parseInt($(this).attr('rel'));
		var commodity_price = parseInt($('.commodity_price em').html());
		


		if(siblings_num != 1){
			$(this).siblings('.num').val( siblings_num - 1);
			$('.commodity_price em').html(commodity_price - minus_price);
			$('#total').val(commodity_price - minus_price);

			if(minus_price == 488){
				var total_price = parseInt($('.total_price em').eq(0).html());
				$('.total_price em').eq(0).html(total_price - minus_price);
			}else if(minus_price == 976){
				var total_price = parseInt($('.total_price em').eq(1).html());
				$('.total_price em').eq(1).html(total_price - minus_price);
			}else if(minus_price == 1952){
				var total_price = parseInt($('.total_price em').eq(2).html());
				$('.total_price em').eq(2).html(total_price - minus_price);
			}else if(minus_price == 199){
				var total_price = parseInt($('.total_price em').eq(3).html());
				$('.total_price em').eq(3).html(total_price - minus_price);
			}
		}
	})

	$('.close').click(function(){
		var current = $(this).attr('rel');
		$('.box-10-2 tr').eq(current).hide();
		
		$('.box-10-2 tr').eq(current).children('td').eq(2).children('input').eq(1).remove();
		$('.box-10-1 ul li').eq(current - 1).removeClass('hover');
		var num = parseInt($('input.num').eq(current - 1).val());
		var price = parseInt($('span.plus').eq(current - 1).attr('rel'));
		var total_price_num = num * price;
		$('.total_price em').eq(current - 1).html(price);
		$('input.num').eq(current - 1).val('1');
	    var commodity_price = parseInt($('.commodity_price em').html());
		$('.commodity_price em').html(commodity_price - total_price_num);
		$('#total').val(commodity_price - total_price_num);
		$('.box-10-2 tr').eq(current).children('td').eq(2).children('input').eq(0).val(0);
	});


	$('.prov').blur(function(){
		//地区码
		var areaID = getAreaID();
		//地区名
		var areaName = getAreaNamebyID(areaID) ;
		if(areaName != '请选择'){
			$('.address').val(areaName);
		}
	});
	$('.cityselect').blur(function(){
		//地区码
		var areaID = getAreaID();
		//地区名
		var areaName = getAreaNamebyID(areaID) ;
		if(areaName != '请选择'){
			$('.address').val(areaName);
		}
		
	});
	$('.dist').blur(function(){
		//地区码
		var areaID = getAreaID();
		//地区名
		var areaName = getAreaNamebyID(areaID) ;
		if(areaName != '请选择'){
			$('.address').val(areaName);
		}
	});
	initComplexArea('prov', 'cityselect', 'dist', area_array, sub_array, '0', '0', '0');

	jQuery(".box-8-2").slide( { mainCell:".bd ul",effect:"leftMarquee",vis:3,interTime:30,autoPlay:true } );
	jQuery(".box-10-7-slide").slide( { mainCell:".bd ul",effect:"topLoop",vis:4,scroll:1,trigger:"click"} );
});

/* 表单一判断 */
function mycheck(){
	var isMobile = /^(?:13\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/; //手机号码验证规则
	var isPhone = /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
	var tel = document.form1.phone.value;
	if(document.form1.name.value ==""){
		alert("您的姓名不能为空！");
		document.form1.name.focus();
		return false;
	}
	if(tel ==""){
		alert("联系电话不能为空！");
		document.form1.phone.focus();
		return false;
	}
	if(!isMobile.test(tel) && !isPhone.test(tel)){ //如果用户输入的值不同时满足手机号和座机号的正则
		alert("请正确填写电话号码，例如:13415764179或020-61396139");  //就弹出提示信息
		document.form1.phone.focus();
		return false; //返回一个错误，不向下执行
	}
	
}

/* 表单二判断 */
function mycheck_form(){
	var isMobile = /^(?:13\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/; //手机号码验证规则
	var isPhone = /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
	var tel = document.form2.phone.value;
	if(document.form2.name.value ==""){
		alert("您的姓名不能为空！");
		document.form2.name.focus();
		return false;
	}
	if(tel ==""){
		alert("联系电话不能为空！");
		document.form2.phone.focus();
		return false;
	}
	if(!isMobile.test(tel) && !isPhone.test(tel)){ //如果用户输入的值不同时满足手机号和座机号的正则
		alert("请正确填写电话号码，例如:13415764179或020-61396139");  //就弹出提示信息
		document.form2.phone.focus();
		return false; //返回一个错误，不向下执行
	}

	
}

/* 在线订购 */
function dinggou(){
	for(var i = 0;i < $('input.num').length;i++){
		var num = $('input.num').eq(i).val();
		$('input.num_two').eq(i).val(num);
		$('input.num').eq(i).val(0);
	}
	var isMobile = /^(?:13\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/; //手机号码验证规则
	var isPhone = /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
	var tel = document.form3.phone.value;
	var is_class = true;
	for(var i = 0;i < $('.box-10-1 ul li').length;i++){
		var li_class = $('.box-10-1 ul li').eq(i).attr('class');
		if(li_class == 'hover'){
			is_class = false;
		}
	}
	if(is_class){
		alert('请选择商品！');
		document.form3.p.focus();
		return false;
	}
	if(document.form3.name.value ==""){
		alert("您的姓名不能为空！");
		document.form3.name.focus();
		return false;
	}
	if(tel ==""){
		alert("联系电话不能为空！");
		document.form3.phone.focus();
		return false;
	}
	if(!isMobile.test(tel) && !isPhone.test(tel)){ //如果用户输入的值不同时满足手机号和座机号的正则
		alert("请正确填写电话号码，例如:13415764179或020-61396139");  //就弹出提示信息
		document.form3.phone.focus();
		return false; //返回一个错误，不向下执行
	}
	if(document.form3.prov.value == "" || document.form3.prov.value == "请选择"){
		alert("城市不能为空！");
		document.form3.prov.focus();
		return false;
	}
	if(document.form3.city.value == "" || document.form3.city.value == "请选择"){
		alert("地区不能为空！");
		document.form3.city.focus();
		return false;
	}
	
	
	
	
}

//得到地区码
function getAreaID(){
	var area = 0;          
	if($("#dist").val() != "0"){
		area = $("#dist").val();                
	}else if ($("#cityselect").val() != "0"){
		area = $("#cityselect").val();
	}else{
		area = $("#prov").val();
	}
	return area;
}

//根据地区码查询地区名
function getAreaNamebyID(areaID){
	var areaName = "";
	if(areaID.length == 2){
		areaName = area_array[areaID];
	}else if(areaID.length == 4){
		var index1 = areaID.substring(0, 2);
		areaName = area_array[index1] + sub_array[index1][areaID];
	}else if(areaID.length == 6){
		var index1 = areaID.substring(0, 2);
		var index2 = areaID.substring(0, 4);
		areaName = area_array[index1] + sub_array[index1][index2] + sub_arr[index2][areaID];
	}
	return areaName;
}

/*	
 *	焦点图插件
 */
(function($){
	$.fn.slide=function(options){
		$.fn.slide.deflunt={
		effect : "fade", //效果 || fade：渐显； || top：上滚动；|| left：左滚动；|| topLoop：上循环滚动；|| leftLoop：左循环滚动；|| topMarquee：上无缝循环滚动；|| leftMarquee：左无缝循环滚动；
		autoPlay:false, //自动运行
		delayTime : 500, //效果持续时间
		interTime : 2500,//自动运行间隔。当effect为无缝滚动的时候，相当于运行速度。
		defaultIndex : 0,//默认的当前位置索引。0是第一个
		titCell:".hd li",//导航元素
		mainCell:".bd",//内容元素的父层对象
		trigger: "mouseover",//触发方式 || mouseover：鼠标移过触发；|| click：鼠标点击触发；
		scroll:1,//每次滚动个数。
		vis:1,//visible，可视范围个数，当内容个数少于可视个数的时候，不执行效果。
		titOnClassName:"on",//当前位置自动增加的class名称
		autoPage:false,//系统自动分页，当为true时，titCell则为导航元素父层对象，同时系统会在titCell里面自动插入分页li元素(1.2版本新增)
		prevCell:".prev",//前一个按钮元素。
		nextCell:".next"//后一个按钮元素。
		};

		return this.each(function() {
			var opts = $.extend({},$.fn.slide.deflunt,options);
			var index=opts.defaultIndex;
			var prevBtn = $(opts.prevCell, $(this));
			var nextBtn = $(opts.nextCell, $(this));
			var navObj = $(opts.titCell, $(this));//导航子元素结合
			var navObjSize = navObj.size();
			var conBox = $(opts.mainCell , $(this));//内容元素父层对象
			var conBoxSize=conBox.children().size();
			var slideH=0;
			var slideW=0;
			var selfW=0;
			var selfH=0;
			var autoPlay = opts.autoPlay;
			var inter=null;//setInterval名称 
			var oldIndex = index;

			if(conBoxSize<opts.vis) return; //当内容个数少于可视个数，不执行效果。

			//处理分页
			if( navObjSize==0 )navObjSize=conBoxSize;
			if( opts.autoPage ){
				var tempS = conBoxSize-opts.vis;
				navObjSize=1+parseInt(tempS%opts.scroll!=0?(tempS/opts.scroll+1):(tempS/opts.scroll)); 
				navObj.html(""); 
				for( var i=0; i<navObjSize; i++ ){ navObj.append("<li>"+(i+1)+"</li>") }
				var navObj = $("li", navObj);//重置导航子元素对象
			}

			conBox.children().each(function(){ //取最大值
				if( $(this).width()>selfW ){ selfW=$(this).width(); slideW=$(this).outerWidth(true);  }
				if( $(this).height()>selfH ){ selfH=$(this).height(); slideH=$(this).outerHeight(true);  }
			});

			switch(opts.effect)
			{
				case "top": conBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; height:'+opts.vis*slideH+'px"></div>').css( { "position":"relative","padding":"0","margin":"0"}).children().css( {"height":selfH} ); break;
				case "left": conBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+opts.vis*slideW+'px"></div>').css( { "width":conBoxSize*slideW,"position":"relative","overflow":"hidden","padding":"0","margin":"0"}).children().css( {"float":"left","width":selfW} ); break;
				case "leftLoop":
				case "leftMarquee":
					conBox.children().clone().appendTo(conBox).clone().prependTo(conBox); 
					conBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; width:'+opts.vis*slideW+'px"></div>').css( { "width":conBoxSize*slideW*3,"position":"relative","overflow":"hidden","padding":"0","margin":"0","left":-conBoxSize*slideW}).children().css( {"float":"left","width":selfW}  ); break;
				case "topLoop":
				case "topMarquee":
					conBox.children().clone().appendTo(conBox).clone().prependTo(conBox); 
					conBox.wrap('<div class="tempWrap" style="overflow:hidden; position:relative; height:'+opts.vis*slideH+'px"></div>').css( { "height":conBoxSize*slideH*3,"position":"relative","padding":"0","margin":"0","top":-conBoxSize*slideH}).children().css( {"height":selfH} ); break;
			}

			//效果函数
			var doPlay=function(){
				switch(opts.effect)
				{
					case "fade": case "top": case "left": if ( index >= navObjSize) { index = 0; } else if( index < 0) { index = navObjSize-1; } break;
					case "leftMarquee":case "topMarquee": if ( index>= 2) { index=1; } else if( index<0) { index = 0; } break;
					case "leftLoop": case "topLoop":
						var tempNum = index - oldIndex; 
						if( navObjSize>2 && tempNum==-(navObjSize-1) ) tempNum=1;
						if( navObjSize>2 && tempNum==(navObjSize-1) ) tempNum=-1;
						var scrollNum = Math.abs( tempNum*opts.scroll );
						if ( index >= navObjSize) { index = 0; } else if( index < 0) { index = navObjSize-1; }
					break;
				}
				switch (opts.effect)
				{
					case "fade":conBox.children().stop(true,true).eq(index).fadeIn(opts.delayTime).siblings().hide();break;
					case "top":conBox.stop(true,true).animate({"top":-index*opts.scroll*slideH},opts.delayTime);break;
					case "left":conBox.stop(true,true).animate({"left":-index*opts.scroll*slideW},opts.delayTime);break;
					case "leftLoop":
						if(tempNum<0 ){
								conBox.stop(true,true).animate({"left":-(conBoxSize-scrollNum )*slideW},opts.delayTime,function(){
								for(var i=0;i<scrollNum;i++){ conBox.children().last().prependTo(conBox); }
								conBox.css("left",-conBoxSize*slideW);
							});
						}
						else{
							conBox.stop(true,true).animate({"left":-( conBoxSize + scrollNum)*slideW},opts.delayTime,function(){
								for(var i=0;i<scrollNum;i++){ conBox.children().first().appendTo(conBox); }
								conBox.css("left",-conBoxSize*slideW);
							});
						}break;// leftLoop end

					case "topLoop":
						if(tempNum<0 ){
								conBox.stop(true,true).animate({"top":-(conBoxSize-scrollNum )*slideH},opts.delayTime,function(){
								for(var i=0;i<scrollNum;i++){ conBox.children().last().prependTo(conBox); }
								conBox.css("top",-conBoxSize*slideH);
							});
						}
						else{
							conBox.stop(true,true).animate({"top":-( conBoxSize + scrollNum)*slideH},opts.delayTime,function(){
								for(var i=0;i<scrollNum;i++){ conBox.children().first().appendTo(conBox); }
								conBox.css("top",-conBoxSize*slideH);
							});
						}break;//topLoop end

					case "leftMarquee":
						var tempLeft = conBox.css("left").replace("px",""); 

						if(index==0 ){
								conBox.animate({"left":++tempLeft},0,function(){
									if( conBox.css("left").replace("px","")>= 0){ for(var i=0;i<conBoxSize;i++){ conBox.children().last().prependTo(conBox); }conBox.css("left",-conBoxSize*slideW);}
								});
						}
						else{
								conBox.animate({"left":--tempLeft},0,function(){
									if(  conBox.css("left").replace("px","")<= -conBoxSize*slideW*2){ for(var i=0;i<conBoxSize;i++){ conBox.children().first().appendTo(conBox); }conBox.css("left",-conBoxSize*slideW);}
								});
						}break;// leftMarquee end

						case "topMarquee":
						var tempTop = conBox.css("top").replace("px",""); 
							if(index==0 ){
									conBox.animate({"top":++tempTop},0,function(){
										if( conBox.css("top").replace("px","") >= 0){ for(var i=0;i<conBoxSize;i++){ conBox.children().last().prependTo(conBox); }conBox.css("top",-conBoxSize*slideH);}
									});
							}
							else{
									conBox.animate({"top":--tempTop},0,function(){
										if( conBox.css("top").replace("px","")<= -conBoxSize*slideH*2){ for(var i=0;i<conBoxSize;i++){ conBox.children().first().appendTo(conBox); }conBox.css("top",-conBoxSize*slideH);}
									});
							}break;// topMarquee end


				}//switch end
					navObj.removeClass(opts.titOnClassName).eq(index).addClass(opts.titOnClassName);
					oldIndex=index;
			};
			//初始化执行
			doPlay();

			//自动播放
			if (autoPlay) {
					if( opts.effect=="leftMarquee" || opts.effect=="topMarquee"  ){
						index++; inter = setInterval(doPlay, opts.interTime);
						conBox.hover(function(){if(autoPlay){clearInterval(inter); }},function(){if(autoPlay){clearInterval(inter);inter = setInterval(doPlay, opts.interTime);}});
					}else{
						 inter=setInterval(function(){index++; doPlay() }, opts.interTime); 
						$(this).hover(function(){if(autoPlay){clearInterval(inter); }},function(){if(autoPlay){clearInterval(inter); inter=setInterval(function(){index++; doPlay() }, opts.interTime); }});
					}
			}

			//鼠标事件
			var mst;
			if(opts.trigger=="mouseover"){
				navObj.hover(function(){ clearTimeout(mst); index=navObj.index(this); mst = window.setTimeout(doPlay,200); }, function(){ if(!mst)clearTimeout(mst); });
			}else{ navObj.click(function(){index=navObj.index(this);  doPlay(); })  }
			nextBtn.click(function(){ index++; doPlay(); });
			prevBtn.click(function(){  index--; doPlay(); });

    	});//each End

	};//slide End

})(jQuery);

$.fn.smartFloat = function() {
    var position = function(element) { 
        var top = element.position().top; //当前元素对象element距离浏览器上边缘的距离 
        var pos = element.css("position"); //当前元素距离页面document顶部的距离 
        $(window).scroll(function() { //侦听滚动时 
            var scrolls = $(this).scrollTop(); 
            if (scrolls > top) { //如果滚动到页面超出了当前元素element的相对页面顶部的高度 
                if (window.XMLHttpRequest) { //如果不是ie6 
                    element.css({ //设置css 
                        position: "fixed", //固定定位,即不再跟随滚动
						display:"block",
                        top: 0 //距离页面顶部为0 
                    }).addClass("shadow"); //加上阴影样式.shadow 
                } else { //如果是ie6 
                    element.css({ 
						 display:"block",
						 position: "absolute", //固定定位,即不再跟随滚动 
                         top: scrolls  //与页面顶部距离 
                    });     
                } 
            }else { 
                element.css({ //如果当前元素element未滚动到浏览器上边缘，则使用默认样式 
                    display:"none",
					position: pos, 
                    top: top 
                }).removeClass("shadow");//移除阴影样式.shadow 
            } 
        }); 
    }; 
    return $(this).each(function() { 
        position($(this));                          
    }); 
}; //智能定位END



/*底部链接*/
$(function(){
	$('.footer-ps1').click(function(e){
			$('.footer-p1').css({'display':'block'})							//索引值同步
    });
	$('.footer').mouseout(function(e){
			$('.footer-p1').css({'display':'none'})							//索引值同步
    });
	
		$('.footer-ps2').click(function(e){
			$('.footer-p2').css({'display':'block'})							//索引值同步
    });
	$('.footer').mouseout(function(e){
			$('.footer-p2').css({'display':'none'})							//索引值同步
    });
	
})
