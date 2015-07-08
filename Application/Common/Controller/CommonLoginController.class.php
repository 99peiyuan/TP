<?php
namespace Common\Controller;
use Think\Controller;
Class CommonLoginController extends Controller {        //继承基本的user类库
   
   //登陆判断 
     public function _initialize(){
       // 自动运行方法
	   $controllerName=strtolower(CONTROLLER_NAME);
	   if($controllerName!=strtolower("login")){
		  
		    if(!session('useradmin')){
		  	
				$this->redirect("Login/Login");
		   
			} 
		   
		   
	   }
	   	   	$System=I("get.System");
			if(!empty($System)){
				//判断 数据库的模型类型
				$System=I("get.System");
				if($System=="Maka"){               //maka
						C('DB_NAME','maka');
					
				}else if($System=="Ejiao"){       //  ejiao
						C('DB_NAME','ejiao');
					
				}
				
				$this->assign("System",$System);
		
	
	   
				}else{
					//默认是玛卡
					$this->assign("System","Maka");
					C('DB_NAME','maka');
				}
   
	//注册
	//登陆
	//验证
	 }

}