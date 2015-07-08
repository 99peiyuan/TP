<?php
namespace Appmaka\Model;
use Think\Model;
Class VerifyModel extends Model{
	
	protected $autoCheckFields =false;  
		protected $_validate=array(
			array('name','require',"姓名未填写"),         //用户名
			array('contact','require','电话号码未填写'),         
			array('address','require','地址未填写'),                
			array('verify','checkCode','验证码输入错误',0,'callback',1),          //验证码
		
		
		
		);
		protected function checkCode($code){
			  
				$verify = new \Think\Verify();  
			return	$verify->check($code,'');
			
		}
	}