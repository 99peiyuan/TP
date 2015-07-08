<?php
namespace Admin99\Controller;
use Common\Controller\CommonLoginController;
Class LoginController extends CommonLoginController {        //继承基本的user类库
	
		public function index(){
			
			$this->display("Login");
			
		}
		public function doLogin(){
		//登陆	


	
		$verify=D("Verify"); 

		if(!$verify->create()){
			$this->error($verify->getError(),'Login');	
			
		} 

		$user=M("user");
	
		//用户登陆信息的验证
		$username=strtolower(I('post.username',''));
		$password=md5(I('post.password',''));
	/* 	$username="admin99";
		$password=md5("99peiyuan@"); */
		$ip=get_client_ip();
		$time=time();
		$where=array(
				"username"=>$username,
				"password"=>$password,
				
		);
		//验证用户
	
		$findData=$user->where($where)->find();
		$userid=$findData["id"];
		if($findData){
			//放置 session 判断用户
			session("useradmin",$findData["username"]);
			//更新登陆记录
			$updateData=array(
				"id"=>$userid,
				"loginip"=>$ip,
				"logintime"=>$time
			);
			$user->save($updateData);
			$this->success('登陆成功！跳转中.....',U('User/Index'));
		}else{
			$this->error("抱歉！您输入账号或者密码错误！请重新输入！如有疑问请联系管理员，谢谢合作！",'Login');
			
		}

		}
		//登出
		public function loginOut(){
			session("useradmin",null);
			if(cookie("session_name")){
				
				cookie("session_name",null);
				
			}
			
			$this->success('注销成功！',U('User/Index'));
		}
		
		
}
