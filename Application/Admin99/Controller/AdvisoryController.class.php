<?php
/*
		该类用于 操作用户的测试留言功能
*/
namespace Admin99\Controller;
use Common\Controller\CommonLoginController;
Class AdvisoryController extends CommonLoginController {
		function Index(){
			$Advisory=M("advisory");
			$count=$Advisory->count();
			$p = getpage($count,8);
			$data=$Advisory->order("lefttime desc")->limit($p->firstRow, $p->listRows)->select();
			
			$this->assign("data",$data);
			$this->assign("show",$p->show());
			$this->display();
			
		}
		
		function ReCall(){
			$Advisory=M("advisory");
		
			$Advisory->id=I("get.id");
			
			//开关状态
			 $RecallStatus=I("get.RecallStatus");
			if($RecallStatus==0){       //界面为 0 ，即传过来是要修改为 1
					$ReCall=1;
			}else{
				$ReCall=0;
				
			}
			
			$Advisory->recall=$ReCall;
		
			 if($Advisory->save()){
				echo "ok";
			}else{
				
				echo "0";
			
			} 
			
		}
		
		function Delete(){
				$Advisory=M("advisory");
				$data["id"]=I("get.id");
				if($Advisory->where($data)->delete()){
						echo "ok";
				}else{
				
				echo "0";
				
				}
				
				

		
			
		}
		
}