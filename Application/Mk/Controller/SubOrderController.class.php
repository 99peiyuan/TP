<?php
	namespace Mk\Controller;
	use Think\Controller;
	Class SubOrderController extends Controller{
			//该类是前台提交订单类
			function submit(){
				
				//获取 自定义的 验证规则
				$verify=D("Verify"); 

				if(!$verify->create()){
				$this->error($verify->getError());	
			
				}  
				
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
				
										$payment=I("post.payment");
										$word=I("post.word");
										$client=I("post.client");
										$productid=I("post.productid");
										$num=I("post.num");		
										$products=M("products");
										$whereProduct=array("id"=>$productid);
										$productPrice=$products->field("price")->where($whereProduct)->find();   
										$price=$productPrice["price"];
										$total=$price*$num;    //算出当前这件 总价
									$time=NOW_TIME;
										$sql="insert into tp_order (payment,productid,num,total,word,ordernum,ordertime,client) values('{$payment}','{$productid}','{$num}','{$total}','{$word}','{$ordernum}','{$time}','{$client}')";
										$order=M("order");
										if(!$order->execute($sql)){
											//插入失败，同时要删除掉  用户的联系信息
												$where["id"]=$contactId;
												$contact->where($where)->delete();	
												$this->error("提交订单失败，请联系管理员3！");	
										}
										
										
												
					$this->success("提交订单成功，您的订单号是【{$ordernum}】,我们将在1个工作日内和您联系！",'',4);
				
				
				
			}
	}
	
	
	