var body_width=$('body').width();if(body_width>640){$('body').width(640).css('margin','0 auto');}
$(document).ready(function(){

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

});

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

/* 在线订购 */
function dinggou(){
	var isMobile = /^(?:13\d|15\d|18\d)\d{5}(\d{3}|\*{3})$/; //手机号码验证规则
	var isPhone = /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
	var tel = document.form1.phone.value;
	if(document.form1.productid.value =="请选择"){
		alert("请选择产品");
		document.form1.productid.focus();
		return false;
	}
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
	if(document.form1.prov.value == "" || document.form1.prov.value == 0){
		alert("城市不能为空！");
		document.form1.prov.focus();
		return false;
	}
	if(document.form1.cityselect.value == "" || document.form1.cityselect.value == 0){
		alert("地区不能为空！");
		document.form1.cityselect.focus();
		return false;
	}
	if(document.form1.address.value ==""){
		alert("您的地址不能为空！");
		document.form1.address.focus();
		return false;
	}
	if(document.form1.payment.value =="请选择"){
		alert("请选择支付方式");
		document.form1.payment.focus();
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