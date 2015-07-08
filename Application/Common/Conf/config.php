<?php
return array(
	//''=>'?'
	"TMPL_L_DELIM"=>"<{",
	"TMPL_R_DELIM"=>"}>",
	"DB_TYPE"=>"mysqli",
	"DB_HOST"=>"localhost",
	"DB_NAME"=>"ejiao",
	"DB_USER"=>"root",
	"DB_PWD"=>"99peiyuan@qq.com..",
	"DB_PORT"=>"3306",
	'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
	"DB_PREFIX"=>"tp_",
	'URL_ROUTER_ON'   => true,
	'URL_MODEL'=>2,
	'DEFAULT_TIMEZONE'      =>  'PRC',
	'SHO_PAGE_TRACE' =>false, 
	'HTML_CACHE_ON'     =>false,
	'MODULE_ALLOW_LIST'     =>  array('Home','Ejhp','Ej99','Admin99',"Appmaka","Mk","Appejhp"), // ?4
	'DEFAULT_MODULE'        =>  'Ejhp', // ?4
//支付宝
//支付宝配置参数
'alipay_config'=>array(
       'partner' =>'2088811682032502',   //这里是你在成功申请支付宝接口后获取到的PID；
    'key'=>'ijg22b3lfbm7e90l5v2vt7ypt1b4frmp',//这里是你在成功申请支付宝接口后获取到的Key
    'sign_type'=>strtoupper('MD5'),
    'input_charset'=> strtolower('utf-8'),
    'cacert'=> getcwd().'\\cacert.pem',
    'transport'=> 'http',
      ),
     //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
    
'alipay'   =>array(
 //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
'seller_email'=>'liangchunling@99peiyuan.com',

/*  'notify_url'=>'http://www.xxx.com/Pay/notifyurl', 
 //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
 'return_url'=>'http://www.xxx.com/Pay/returnurl',
 */
//支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
'successpage'=>'Order/Success',   

//支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
'errorpage'=>'Order/Error', 
),



);

