<?php
	/* 
	  查看用户 支付宝的支付的详细信息

*/
	namespace Admin99\Controller;
	use Common\Controller\CommonLoginController;
	Class AlipayDetailController extends CommonLoginController{
			function Index(){
				$order=M("order");
				$where=array("tp_order.ordernum"=>I("get.ordernum"));
				$data=$order->join("LEFT JOIN __ORDERLIST__ ON __ORDERLIST__.ordid=__ORDER__.ordernum")->where($where)->find();
			
				$this->assign("data",$data);
				$this->display();
			}
		
		
	}