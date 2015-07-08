<?php

//在线交易订单支付处理函数
 //函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
 //返回值：如果订单已经成功支付，返回true，否则返回false；
 function checkorderstatus($ordid){
	 $system=session("system");    //支付的产品类型，选择对应的数据库
	C('DB_NAME',$system);
    $Ord=M('orderlist');
	$where["ordid"]=$ordid;
    $ordstatusRes=$Ord->where($where)->find();
    if($ordstatusRes["ordstatus"]==1){
        return true;
    }else{
        return false;    
    }
 }
 
 
 //处理订单函数
 //更新订单状态，写入订单支付后返回的数据
 function orderhandle($parameter){
	 $system=session("system");    //支付的产品类型，选择对应的数据库
	 C('DB_NAME',$system);
    //更新支付宝详细表
	$ordid=$parameter['out_trade_no'];
    $data['payment_trade_no']=$parameter['trade_no'];
    $data['payment_trade_status']=$parameter['trade_status'];
    $data['payment_notify_id']=$parameter['notify_id'];
    $data['payment_notify_time']=$parameter['notify_time'];
    $data['payment_buyer_email']=$parameter['buyer_email'];
    $data['ordstatus']=1;
    $Ord=M('orderlist');
	$OrderlistWhere["ordid"]=$ordid;
    $bool=$Ord->where($OrderlistWhere)->save($data);
	
	//更新订单表
	if(!empty($bool)){
		
			$order=M("order");
			$sql="update tp_order set status=1 where ordernum='{$ordid}'";
			$order->execute($sql);
			
	}
	
 } 
 //底部 icp  跟备案号的 填写
function BottomInfo($Host){
		$TencentMonitorCode='
<div style="display:none" >
<iframe src="tencent://message/?Menu=yes&amp;uin=938016786&amp;Service=58&amp;SigT=A7F6FEA02730C988D2E77B85166922B9318C169E0197B5FDD0AC08AF8E6AE90533967A12CE18AA0B5E3776001C0AECC696B0D950C6AF983517DA1699084E650A0DD8FFBEA29ED3E0A613A2B835E4FCB3B87FF7E4FD8EF3719831DCA3E72C66D58382EB8321105BD6EE810DCB678B6D1BA1E28B736295B6FB&amp;SigU=30E5D5233A443AB209D5AF5AB597AC034100572414421F733640065350E2C8096837127CFB641F5ABFFDEDF286E0A74A390A4482F1775D52F66417C4FE8F4156364F60C461BACFEC">


</iframe>';             //腾讯 监控代码，每个页面都添加
		$HostKeyStr=array(
			0=>"haoli13",
			/* 1=>"ahautojob", */
			1=>"peiyuanajiao",
	
		);
		//循环主机域名的关键字符串，查看是否对应该字符串
		
		foreach($HostKeyStr as $v){
				
				$n=strpos(strtolower($Host),strtolower($v));
				if($n>-1){
					//找到了，在判断 域名 然后，赋予  company跟 icp备案号的值
					switch($v){
						case "haoli13":
							$Company="深圳市澳凌峰科技有限公司";
							$ICP="ICP备14037646号-5";
							$MonitorCode='<script>                    
											var _hmt = _hmt || [];
											(function() {
											  var hm = document.createElement("script");
											  hm.src = "//hm.baidu.com/hm.js?d3c6fab423a990aeb139bfca2c949931";
											  var s = document.getElementsByTagName("script")[0]; 
											  s.parentNode.insertBefore(hm, s);
											})();
											</script>';
											
						;
						 break;	
						 case "haoli16":
							$Company="";
							$ICP="";
							$MonitorCode='<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?01b680e244ad74f886517577554da8d1";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
';
											
						;
						 break;
						 case "peiyuanajiao":
							$Company="天全县腾越建材经营部";
							$ICP="粤ICP备05058608";
							$MonitorCode='<script>
															var _hmt = _hmt || [];
															(function() {
															  var hm = document.createElement("script");
															  hm.src = "//hm.baidu.com/hm.js?ea9205ffaa1385131bf6a1aa1c0b1705";
															  var s = document.getElementsByTagName("script")[0]; 
															  s.parentNode.insertBefore(hm, s);
															})();
															</script>

											';
						;
						 break;
						/*  case "localhost":
							$Company="localhost";
							$ICP="粤localhost";
						;
						 break; */
						default:
							$Company="";
							$ICP="";
							$MonitorCode="";
						;
						
					}
				break;	 //已经找到 退出循环
				}else{
					//全都没有找到，赋空值
					$Company="";
					$ICP="";
					$MonitorCode="";
				}
			
			
		}
		
		
		$BottomInfo=array($Company,$ICP,$MonitorCode.$TencentMonitorCode);
		// 返回值
		return $BottomInfo;
		
	
}

function alipayFaild($ordernum){
	$system=session("system");    //支付的产品类型，选择对应的数据库
	C('DB_NAME',$system);
	//支付宝支付失败，删除数据库对应订单号
	$contact=M("contact");
	$where["ordernum"]=$ordernum;
	$contact->where($where)->delete();
	$order=M("order");
	$order->where($where)->delete();
	$orderList=M("orderlist");
	$orderList->where($where)->delete();
	
}
/*PHPExcel导出类操作方法*/
function ExportExcel($orderData){
	
		//1.创建对象
		vendor('PHPExcel.PHPExcel');
        $objExcel = new \PHPExcel();
		
		//2. 常量属性 设置
			$date=date("Y-d-m-H-i");
			$system=I("get.System");
			$outfile = $system."-用户购买信息{$date}.xls";
			$title=$system."--用户购买信息{$date}";

			$objActSheet = $objExcel->getActiveSheet();
			$wordArray=array("A","B","C","D","E","F","G","H","I","J","K","L","M","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","Z","U","V","X","Y","Z","AA","AB","AC","AD");
		
		//3. 表格的属性设置	
					
					//  3.1.设置 文件 名的 title
						$objActSheet->setTitle($title); 
		
				    // 3.2.合并表格
					
						$objActSheet->mergeCells('A1:G1'); 
					//var_dump();exit;
						
						$cellNames=array("姓名","地址","电话","产品类别(套餐）","客户来源","备注（购买日期，购买数量，症状）","金额");     //列头名     
						$cellNamesLength=count($cellNames);             // 列的属性长度
						
					//   3.3 设置水平居中    
					$objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					for($i=0;$i<$cellNamesLength;$i++){
						
							$objActSheet->getStyle("{$wordArray[$i]}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
						
								
									$objActSheet->getColumnDimension("{$wordArray[$i+1]}")->setWidth(40);  //设置每一列 的宽度
							

						
					}
					
						
		//4. 表格内容的属性设置
		
					//4.1 设置列头
					$objActSheet->setCellValue('A1', "{$title}");
					for($i=0;$i<=$cellNamesLength;$i++){
							  $objActSheet->setCellValue("{$wordArray[$i]}2", $cellNames[$i]);           //从A2 ，即 第二列开始 设置值，第一列为 合并的列头
						
					}
					
				
		//5.表格内容的赋予 
				//5.1	
				//var_dump($orderData);
				$keyArr=array("name","address","phone","productname","client");      //需要获取的键值		
				$keyArrLength=count($keyArr);           //获取总数据的条数
		
			
					
						foreach($orderData as $key=> $v){
						
							 //从  A3 开始设置数据库调出来的用户信息的值
									
								$objActSheet->setCellValue("{$wordArray[0]}".($key+3)."", $v["name"]);             
								$objActSheet->setCellValue("{$wordArray[1]}".($key+3)."", $v["address"]);             
								$objActSheet->setCellValue("{$wordArray[2]}".($key+3)."", $v["phone"]);             
								$objActSheet->setCellValue("{$wordArray[3]}".($key+3)."", $v["productname"]);             
								$objActSheet->setCellValue("{$wordArray[4]}".($key+3)."", $v["client"]);             
								$orderTime=date("Y-d-m H:i:s",$v["ordertime"]);
								$objActSheet->setCellValue("{$wordArray[5]}".($key+3)."", "备注({$orderTime},{$v["num"]}件,{$v["word"]})"); 
								$objActSheet->setCellValue("{$wordArray[6]}".($key+3)."", $v["total"]); 
					}	
			
	
							
					
        //输出操作 
	
		//$objExcel->setActiveSheetIndex(0); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');

        header("Content-Type: application/force-download");

        header("Content-Type: application/octet-stream");

        header("Content-Type: application/download");

        header('Content-Disposition:inline;filename="'.$outfile.'"');

        header("Content-Transfer-Encoding: binary");

        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        header("Pragma: no-cache");

        $objWriter->save('php://output');

        exit;
    } 

   /*************end***********PHPExcel导出类操作方法***********/
