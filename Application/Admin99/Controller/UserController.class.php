<?php
namespace Admin99\Controller;
use Common\Controller\CommonLoginController;
Class UserController extends CommonLoginController {        //继承基本的user类库
  	
	public function index(){
	
		$order=M("order");
		$System=I("get.System");

			//需要调出的字符串
			
			$needField=array("tp_contact.ordernum","tp_contact.name","tp_contact.phone","tp_contact.address","tp_order.payment","tp_order.word","tp_order.status","tp_order.expressnum","tp_order.expressname","tp_order.id as orderid","tp_order.num","tp_order.total","tp_order.ordertime","tp_order.client","tp_order.operator","tp_products.productname","tp_products.producttype","tp_products.price","tp_orderlist.ordstatus");
			
			
		$count = $order->join(array("LEFT JOIN __CONTACT__ ON __CONTACT__.ordernum=__ORDER__.ordernum","LEFT JOIN __PRODUCTS__ ON __PRODUCTS__.id =__ORDER__.productid"))->count();
		 $p = getpage($count,4);
		$data = $order->field($needField)->join(array("LEFT JOIN __CONTACT__ ON __CONTACT__.ordernum=__ORDER__.ordernum","LEFT JOIN __PRODUCTS__ ON __PRODUCTS__.id=__ORDER__.productid","LEFT JOIN __ORDERLIST__ ON __ORDERLIST__.ordid=__ORDER__.ordernum"))->order('ordertime')->limit($p->firstRow, $p->listRows)->select();	
			
	
			//拼合相同的訂單
	

		foreach($data as  $v){
				
							 
							 if($v["ordernum"]){              //合并订单号 ，将相同订单的 合并在同一个数组中 
 								 
								 $test[$v["ordernum"]][]=$v;
								 
							 }
							 
							 
							
							 
							  if($v["total"]){
								
								 $total[$v["ordernum"]]+=$v["total"];
								 
							 } 
							 
						
					
			
		}
	
		
		
		//$this->assign('data',$data);// 赋值数据集$this->assign('page',$show);// 赋值分页输出
		$this->assign('test',$test);// 赋值数据集$this->assign('page',$show);// 赋值分页输出
		$this->assign('total',$total);// 赋值数据集$this->assign('page',$show);// 赋值分页输出
		$this->assign('show',$p->show());// 赋值数据集$this->assign('page',$show);// 赋值分页输出
		$this->display();
	
    } 
	public function reg(){
		//注册 undefined
		$this->display();
	}


	
		
		
	}

	
	

	

	

