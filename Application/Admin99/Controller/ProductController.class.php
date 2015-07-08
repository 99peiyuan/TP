<?php
	/*
	该类用于产品信息的操作

*/
	namespace Admin99\Controller;
	use Common\Controller\CommonLoginController;
	Class ProductController extends CommonLoginController{
			function All(){
				//默认显示全部
				$products=M("products");
				$count=$products->count();
				$p = getpage($count,8);
				$data=$products->order('uptime')->limit($p->firstRow, $p->listRows)->select();
				
				$this->assign("data",$data);
				$this->assign("show",$p->show());
				$this->display();
				
			}
		
		//添加
		
		function Add(){
			$this->display();
			
		}
		function DoAdd(){
				$Products=M("products");
				
				$rules=array(
					 array('uptime','time')
				);
			
				if($Products->create()){
				$Products->add();
					$this->success("插入新的产品信息成功！");
						

					
				}else{
					
					$this->error("插入新的产品信息失败!".$Products->getError(),"");
				
				}
			
		
			
		}
		
		
		
		//修改
		function Modify(){
			$products=M("products");
			$where["id"]=I("get.id");
			$data=$products->where($where)->select();
			$this->assign("data",$data);
			
			$this->display();
			}
		
		function DoModify(){
				$Products=M("products");
				$rules=array(
					 array('uptime','time')
				);
				if($Products->create()){
					if($Products->auto($rules)->save()){
						
						
						$this->success("更新的产品信息成功！");
						
					}else{
								$this->error("更新的产品信息失败！!".$Products->getError(),"");
				
						
					}
					
					
				}else{
					
					$this->error($Products->getError(),"");
				
				}
			
		
		
	
		}
		
		
		
		//删除
		function Delete(){
				$products=M("products");
				$data["id"]=I("get.id");
				if($products->where($data)->delete()){
					$this->success("删除产品信息成功！");
					
				}else{
					
					$this->error("删除产品信息失败！!");
				}
			
		}
		
		
	}