function orderAction(action,orderid,inputUrl,SystemModule){
	switch (action){
			case "Confirm":
				var go=action;
				var str="确认订单";
		
			break;	
			case "sent":
				var go=action;
				//还需拼接值进行传递
				var expressName=$("input[name='expressName']").val();
				var expressNum=$("input[name='expressNum']").val();
				var orderid=$("input[name='orderIdHidden']").val();
				var orderid=orderid+"/expressName/"+expressName+"/expressNum/"+expressNum;
				var str="发货";
			break;	
			case "Delete":
				var go=action;
				var str="删除";
				
			break;
		
		
	}
	/* /web/index.php/Admin99/Order/Confirm/System/Ejiao/orderid=967b8cefa419 */

	 url=""+inputUrl+"/"+go+"/System/"+SystemModule+"/orderid/"+orderid;
	//alert(url);
		 //确认
		$.ajax({
					url:url,
					
					success:function(data){
					
							if(data!=0){
							
									alert(str+"成功！");
									if(str=="确认订单"){
										$("#"+data+"").html("");
									$("#"+data+"").html("<font color='blue'>已确认</font>");
										
										
										
									}
									 if(str=="发货"){
											closeBg();
											
									}
								
							
							}else{
								
									alert("error,"+str+"失败！");
								
							} 
								var orderid=$("input[name='orderIdHidden']").val("");
		$("#add").text("");
	window.location.reload(); 
							
					}
		
		});
		
	
	}
/*导航效果*/

$(function(){
	$('.admin-nav li').click(function(e) {
        
		$(this).addClass('active').siblings().removeClass();	//屏幕的工作
    });
	
	})