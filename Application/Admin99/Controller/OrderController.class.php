<?php
/*
	该类用于 订单操作

*/
	namespace Admin99\Controller;
	use Common\Controller\CommonLoginController;
	Class OrderController extends CommonLoginController{
		public function Add(){
					//添加订单
				$product=M("products");
				$productData=$product->select();
				$this->assign("data",$productData);
				$this->display();
					
			
		}	
		public function DoAdd(){
					
						//验证通过了。现在插入数据
				
					
					$randNum=rand(0,9999999999999);         //生成  订单号的操作
					$time=time();
					$str=$randNum.$time;
					$ordernum=substr(md5($str),1,12);
				
				//共有两个操作 1.用户联系方式  2.购买信息的操作
				
				//1.用户联系方式的操作 
				
				$contact=M("contact");
				$_POST["ordernum"]=$ordernum;
				if($contact->field("name,phone,address,word,ordernum")->create()){
					
						if(!$contactId=$contact->add()){                   //$contactId 用于下面的操作，如果 购买的产品信息插入失败，则可以通过该 id 删除上面插入的用户数据
							
						
							$this->error("提交订单失败，请联系管理员1！");
							
						}
					
				}else{
					
					$this->error("提交订单失败，请联系管理员2！");
				}
				
			
				//2,插入购买产品的信息
				
				
				//2,插入购买产品的信息
				
										$payment=I("post.payment");
										$word=I("post.word");
										$price=I("post.price");
										$productid=I("post.productid");
										$num=I("post.num",1);
										$products=M("products");
										$operator=session("useradmin");
										$whereProduct=array("id"=>$productid);
										$productPrice=$products->field("price")->where($whereProduct)->find();   
										if($price!=$productPrice["price"]){
											//恶意修改价格
								
											//插入失败，同时要删除掉  用户的联系信息
											$where["id"]=$contactId;
											$contact->where($where)->delete();
											
											$this->error("请勿恶意修改相关提交内容，谢谢合作！");
											//删除已经插入的用户联系信息
											
										}
										
										
										$total=$price*$num;    //算出当前这件 总价

										$time=NOW_TIME;
										$sql="insert into tp_order (payment,productid,num,total,word,ordernum,ordertime,client,operator) values('{$payment}','{$productid}','{$num}','{$total}','{$word}','{$ordernum}','{$time}','{$client}','{$operator}')";
										$order=M("order");

				
										if(!$order->execute($sql)){
											

												//插入失败，同时要删除掉  用户的联系信息
												$where["id"]=$contactId;
												$contact->where($where)->delete();
											
												$this->error("提交订单失败，请联系管理员3！");
										
										
											
										}
										
										
												
					$this->success("自定义添加订单号成功，新添加的订单号是【{$ordernum}】！",'',4);
				
						
			
		}
		//确认
		public function Confirm(){
			
			$order=M("order");	
			$orderid=I("get.orderid");
			$data["status"]=1;
			$data["operator"]=session("useradmin");
			if($order->where("id = '{$orderid}'")->save($data)){
				echo $orderid;
				
			}else{
				
				echo "";
				
			}
			
		}
		
		
		//发货
		
		public function sent(){
		
			$order=M("order");	
			$orderid=I("get.orderid");
			 $expressname=I("get.expressName");
			 $expressnum=I("get.expressNum");
			 $operator=session("useradmin");
			$data["expressname"]=$expressname;
			$data["expressnum"]=$expressnum;
			$data["status"]=2;
			$data["senttime"]=NOW_TIME;
			$data["operator"]= $operator;
			if($order->where("id = '{$orderid}'")->save($data)){
		
				
					echo "ok";
				
			}else{
			
				echo 0;
				
			}
			
			
		}
		
			//删除
	public function Delete(){
		
			$order=M("order");	
			$id=I("get.orderid");
			if($order->where("id = '{$id}'")->delete()){
		
					echo "ok";
				
			}else{
			
				echo 0;
				
			}
			
			
		}
		
		
		//改
	public function Modify(){
		//这是调出 修改数据的方法
			//需要调出的字符串
			$orderid=I("get.orderid");                     // 购买信息表的  订单id
			$ordernum=I("get.ordernum");                     // 购买信息表的  订单id
			$needField=array("tp_contact.name","tp_contact.phone","tp_contact.address","tp_order.productid","tp_order.payment","tp_order.word","tp_order.expressnum","tp_order.expressname","tp_order.ordernum","tp_order.id as orderid","tp_order.num","tp_order.status","tp_order.ordertime","tp_order.total","tp_products.productname","tp_products.producttype","tp_products.price");

		$where=array(
				"tp_order.ordernum"=>$ordernum,
				"tp_order.id"=>$orderid
		
		);
		

		//调出数据，并进行修改
		$order=M("order");
		$data=$order->field($needField)->join(array("LEFT JOIN __CONTACT__ ON __CONTACT__.ordernum=__ORDER__.ordernum","LEFT JOIN __PRODUCTS__ ON __PRODUCTS__.id=__ORDER__.productid"))->where($where)->find();
		
		$test[]=$data;

		//调出产品选择
		$product=M("products");
		$productData=$product->select();
		$this->assign("productData",$productData);
		$this->assign("data",$test);
		$this->display();
		
		
	}
	
	//修改操作
	
	public function DoModify(){
			
				//更新 contact 跟 order表格
					// 1.  contact 需要更新
				$contact=M("contact");
				$name=I("post.name");
				$address=I("post.address");
				$phone=I("post.phone");
				$ordernum=I("post.ordernum");

				$contactWhere=array(
				
						"ordernum"=>$ordernum
				
				);
				$contactData=array(
						"name"=>$name,
						"address"=>$address,
						"phone"=>$phone
				
				);	
				
				if($contact->where($contactWhere)->save($contactData)){
					
					//更新用户数据成功，继续更新 order 表更新
						
				
							//2. order 表更新
							$order=M("order");
							$orderid=I("post.orderid");
							$whereOrder=array(
									"id"=>$orderid
								);
							$operator=session("useradmin");
							$num=I("post.num");
							$total=I("post.total");
							$status=I("post.status");
							$word=I("post.word");
							$expressName=I("post.expressname");
							$expressNum=I("post.expressnum");
							if($expressName!=""&&$expressnum!=""){
								
									$status=2;            //更改发货状态
								
							}
							
							
							$orderData=array(
										"num"=>$num,
										"total"=>$total,
										"status"=>$status,
										"word"=>$word,
										"expressName"=>$expressName,
										"expressNum"=>$expressNum,
										"operator"=>$operator
							);

						if($order->where($whereOrder)->save($orderData)){
								$this->success("更新信息成功！");
							
						}else{
								
								//更新失败了。还原 之前的 用户数据
								
						$oldName=I("post.oldname");
						$oldAddress=I("post.oldaddress");
						$oldPhone=I("post.oldphone");
					
							$oldContactData=array(
								"name"=>$oldName,
								"address"=>$oldAddress,
								"phone"=>$oldPhone
						
						);
						$contact->where($contactWhere)->save($oldContactData);
								
							
								$this->error("本次更新信息失败！请联系管理员2！");
						}
					
				}else{
					$bool=I("post.bool");
					//更新失败了。
					
					//1.可能是 未更改用户信息表
					if($bool==1){
						
						
							//2. order 表更新
							$order=M("order");
							$orderid=I("post.orderid");
							$whereOrder=array(
									"id"=>$orderid
								);
							$operator=session("useradmin");
							$num=I("post.num");
							$total=I("post.total");
							$status=I("post.status");
							$word=I("post.word");
							$expressName=I("post.expressname");
							$expressNum=I("post.expressnum");
							if($expressName!=""&&$expressnum!=""){
								
									$status=2;            //更改发货状态
								
							}
							
							
							$orderData=array(
										"id"=>$orderid,
										"num"=>$num,
										"total"=>$total,
										"status"=>$status,
										"word"=>$word,
										"expressname"=>$expressName,
										"expressnum"=>$expressNum,
										"operator"=>$operator
							);
							
						
					
						if($order->save($orderData)){
								$this->success("更新信息成功！");
							
						}else{
								
								//更新失败了。还原 之前的 用户数据
								die($order->getError());
						$oldName=I("post.oldname");
						$oldAddress=I("post.oldaddress");
						$oldPhone=I("post.oldphone");
					
							$oldContactData=array(
								"name"=>$oldName,
								"address"=>$oldAddress,
								"phone"=>$oldPhone
						
						);
						$contact->where($contactWhere)->save($oldContactData);
								
							
								$this->error("本次更新信息失败！请联系管理员4！");
						}
						
						
						
						
					}else{
						
							//2.真的更新失败了
					
						$oldName=I("post.oldname");
						$oldAddress=I("post.oldaddress");
						$oldPhone=I("post.oldphone");
					
							$oldContactData=array(
								"name"=>$oldName,
								"address"=>$oldAddress,
								"phone"=>$oldPhone
						
						);
						$contact->where($contactWhere)->save($oldContactData);
					
						$this->error("本次更新信息失败！本次操作未更改任何内容！".$contact->getError());
						
					}
					
					
				
					
				}
			
		
	}
		


		public function Search(){
				$this->display();
			
			}
		public function DoSearch(){
				$order=M("order");
				$SearchType=I("get.SearchType");
				switch($SearchType){       
					case "NoConfirm":    //未确定订单
					
							$where=array(
								"tp_order.status"=>0,
								
						);
						
						
				
						break;	
						case "Confirm":    //确定订单
						$where=array(
								"tp_order.status"=>1,
								
						);
						
				
				
						break;
						case "Sented":    //已经发货的订单
							$where=array(
								"tp_order.status"=>2,
								
						);
						
				
						break;
						case "ExpressNum":    //快递单号搜索
							$ExpressNum=I("get.ExpressNum");
							$where=array(
									"tp_order.expressnum"=>array("like","%{$ExpressNum}%")
							
							);
							
						break;	
						case "Name":    //名字搜索
							$Name=I("get.Name");
								$where=array(
									"tp_contact.name"=>array("like","%{$Name}%")
							
							);
						
				
						break;
						case "ordernum":    //订单号搜索
							$orderNum=I("get.ordernum");
								$where=array(
									"tp_order.ordernum"=>array("like","%{$orderNum}%")
							
							);
				
						break;	
						case "Phone":    //电话搜索
							$Phone=I("get.Phone");
								$where=array(
									"tp_contact.phone"=>array("like","%{$Phone}%")
							
							);
							
				
				
						break;
						case "Date":    //日期范围搜索
					
							$Start=I("get.Start");
							$End=I("get.End");
							//转换时间戳
							$str1=" 00:00:00";
							$str2=" 23:59:59";
							 $StartStr=$Start.$str1;
							 $EndStr=$End.$str2;
							$StartUnix=strtotime($StartStr);
							$EndUnix=strtotime($EndStr);
								$where=array(
									"tp_order.ordertime"=>array("between",array("{$StartUnix}","{$EndUnix}"))
							
							);
							
							
				
						break;
					
					
					
					
					
				}
				
				
						//需要调出的字符串
						
						$needField=array("tp_contact.ordernum","tp_contact.name","tp_contact.phone","tp_contact.address","tp_order.payment","tp_order.word","tp_order.status","tp_order.expressnum","tp_order.expressname","tp_order.id as orderid","tp_order.ordertime","tp_order.client","tp_order.operator","tp_products.productname","tp_order.total","tp_products.producttype","tp_products.price","tp_order.num");
						
						
					$count = $order->join(array("LEFT JOIN __CONTACT__ ON __CONTACT__.ordernum=__ORDER__.ordernum","LEFT JOIN __PRODUCTS__ ON __PRODUCTS__.id =__ORDER__.productid"))->where($where)->count();
			
					 $p = getpage($count,$count);
					$data = $order->field($needField)->join(array("LEFT JOIN __CONTACT__ ON __CONTACT__.ordernum=__ORDER__.ordernum","LEFT JOIN __PRODUCTS__ ON __PRODUCTS__.id=__ORDER__.productid"))->where($where)->order('ordertime')->limit($p->firstRow, $p->listRows)->select();	
						
				 
				
						//拼合相同的訂單
	

					foreach($data as  $v){
							
										 
										 if($v["ordernum"]){              //合并订单号 ，将相同订单的 合并在同一个数组中 
											 
											 $test[$v["ordernum"]][]=$v;
											 
										 }
										 
										  if($v["total"]){
											
											 $total[$v["ordernum"]]+=$v["total"];
											 
										 } 

					}
			

				$this->assign('test',$test);// 赋值数据集$this->assign('page',$show);// 赋值分页输出
				$this->assign('total',$total);// 赋值数据集$this->assign('page',$show);// 赋值分页输出
				$this->assign('show',$p->show());// 赋值数据集$this->assign('page',$show);// 赋值分页输出
				$this->display();
		}
		

		public function AllSent(){
			//批发发货
			$order=M("order");
			//$arr=I("get.AllSent");
			$arr=array(0=>"f9442fca944e",1=>"d9eddc4c2db2");
			$arrStr=implode(",",$arr);
			$data["status"]=2;
			$data["orderid"]=array("in",$arrStr);
			var_dump($data);
			if($order->save($data)){
				echo "ok!";
				
			}else{
				echo 0;
			}
			
			
		}
		
		/*PHPExcel 订单导出操作*/
	function Export(){//导出Excel
       // 1.接收需要导出的  id数组
	   
	   $exportArr=I("get.exportArr");
		 $ids="(0,{$exportArr})";
		$where=array(
			"tp_order.id"=>array("in",$ids)
		
		);
	
		$order=M("order");
			//需要调出的字符串
	//array("姓名","地址","电话","产品类别(套餐）","客户来源","备注（购买日期，购买数量，症状）","金额");     //列头名  
						$needField=array("tp_contact.ordernum","tp_contact.name","tp_contact.phone","tp_contact.address","tp_order.word","tp_order.ordertime","tp_order.client","tp_products.productname","tp_order.total","tp_products.producttype","tp_products.price","tp_order.num");
						
						
					$orderData = $order->join(array("LEFT JOIN __CONTACT__ ON __CONTACT__.ordernum=__ORDER__.ordernum","LEFT JOIN __PRODUCTS__ ON __PRODUCTS__.id =__ORDER__.productid"))->where($where)->select();
		//var_dump($orderData);
	
		ExportExcel($orderData);
    }
		
		
	}