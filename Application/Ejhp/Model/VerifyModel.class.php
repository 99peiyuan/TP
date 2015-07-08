<?php
namespace Ejhp\Model;
use Think\Model;
Class VerifyModel extends Model{
	
	protected $autoCheckFields =false;  
		protected $_validate=array(
			array('name','require',"姓名未填写",0),         //用户名
			array('contact','require','电话号码未填写',0),    
			array('age','require','年龄未填写',0),    
			array('address','require','地址未填写',0),                
			array('verify','checkCode','验证码输入错误',0,'callback',0),          //验证码
		
		
		
		);
		protected function checkCode($code){
			  
				$verify = new \Think\Verify();  
			return	$verify->check($code,'');
			
		}
		
	}