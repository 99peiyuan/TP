<?php
namespace Ejhp\Controller;
use Think\Controller;
class PayController extends Controller{
       //在类初始化方法中，引入相关类库    
	 
       public function _initialize() {
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');    
    }
    
    //doalipay方法
        /*该方法其实就是将接口文件包下alipayapi.php的内容复制过来
          然后进行相关处理
        */
    public function doalipay(){
		$system=I("post.system");
		session("system",$system);
		$runUrl=I("post.runUrl");
		session("runUrl",$runUrl);
		//获取 自定义的 验证规则
				$verify=D("Verify"); 

				if(!$verify->create()){
				$this->error($verify->getError());	
			
				}  
				
		// 支付宝支付
		  //1.联系人
		  //2.本地的订单表
		  //3.插入到对应的 支付宝详细表中
		
			$productsInfo=I("post.product");	
				$randNum=rand(0,9999999999999);         //生成  订单号的操作
					$time=time();
					$str=$randNum.$time;
					$ordernum=substr(md5($str),1,12);
				
				//共有两个操作 1.用户联系方式  2.购买信息的操作
				
				//1.用户联系方式的操作 
				
				$contact=M("contact");
				$order=M("order");
				$orderList=M("orderlist");                  // 支付宝订单详细表模型
				$_POST["ordernum"]=$ordernum;
				if($contact->field("name,phone,address,word,ordernum")->create()){
					
						if(!$contactId=$contact->add()){                   //$contactId 用于下面的操作，如果 购买的产品信息插入失败，则可以通过该 id 删除上面插入的用户数据
							
						
							$this->error("提交订单失败，请联系管理员1！");
							
						}
					
				}else{
					
					$this->error("提交订单失败，请联系管理员2！");
				}		
				//2,插入购买产品的信息
				//插入到用户购买的信息表中，$key 为产品的id ，num_two 为该产品 的数量
						foreach($productsInfo as $key => $v){
						
								$num=$v["num_two"];         //订购的数量
							if($num==0){
								
								continue;
								
							}else{
										
										$payment=I("post.payment");
										$word=I("post.word");
										$client=I("post.client");
										$price=I("post.price{$key}");
										$products=M("products");
										$productid=$key;
										 $productidStr.=$key.",";
										$numStr.=$num.",";
										
										$whereProduct=array("id"=>$productid);
										$product=$products->field("price,productname")->where($whereProduct)->find(); 
										$productname=$product["productname"];
										$price=$product["price"];
										$priceStr.=$price.",";	
										$total=$price*$num;    //算出当前这件 总价	
										$time=NOW_TIME;
										$sql="insert into tp_order (payment,productid,num,total,word,ordernum,ordertime,client) values('{$payment}','{$key}','{$num}','{$total}','{$word}','{$ordernum}','{$time}','{$client}')";
										
										if(!$order->execute($sql)){
											$where["id"]=$contactId;
											$contact->where($where)->delete();
											
												$this->error("提交订单失败，请联系管理员3！");
			
										}
										
											//如果多种产品，需要 拼接最后的总价格，存入数组在计算是个好办法
										$totalArr[]=$total;
										$numArr[]=$num;
										$productnameArr[]=$productname;
							
								}		
									
							} //end foreach
											
		//2.支付宝的 付款与 详情表的操作
 //这里我们通过TP的C函数把配置项参数读出，赋给$alipay_config；
       $alipay_config=C('alipay_config');  
 
        /**************************请求参数**************************/
        $payment_type = "1"; //支付类型 //必填，不能修改
        $notify_url = C('alipay.notify_url'); //服务器异步通知页面路径
        $return_url = C('alipay.return_url'); //页面跳转同步通知页面路径
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填		
									
									
        $out_trade_no = $ordernum;//商户订单号 通过支付页面的表单进行传递，注意要唯一！
		$subject=I("post.subject");
	
		foreach($totalArr as $v){
			//付款总金额
			$sum+=$v;
			
		}
		/****************  测试金额  ************/
		

        $total_fee =$sum;   //付款金额  //必填 通过支付页面的表单进行传递
		
		
		/************订单描述，即产品名字的全部拼写***********/
		foreach($productnameArr as $v){
			//订单描述
			$productnameStr.=$v.",";
			
		}
        $body = $str;  //订单描述 通过支付页面的表单进行传递
        $show_url = "http://baojian.99peiyuan.com/ejhp";  //商品展示地址 通过支付页面的表单进行传递
        $anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址 
        
		
		/************************************************************/
		$currentUrl=$_SERVER["HTTP_HOST"];
		 $notify_url="http://".$currentUrl."/Pay/notifyurl"; 
		//这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
		$return_url="http://".$currentUrl."/Pay/returnurl";
		
		
        //构造要请求的参数数组，无需改动
    $parameter = array(
        "service" => "create_direct_pay_by_user",
        "partner" => trim($alipay_config['partner']),
        "payment_type"    => $payment_type,
        "notify_url"    => $notify_url,
        "return_url"    => $return_url,
        "seller_email"    => $seller_email,
        "out_trade_no"    => $out_trade_no,
        "subject"    => $subject,
        "total_fee"    => $total_fee,
        "body"            => $productnameStr,
        "show_url"    => $show_url,
        "anti_phishing_key"    => $anti_phishing_key,
        "exter_invoke_ip"    => $exter_invoke_ip,
        "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
		
		
		//插入新的 支付宝详细订单
		
		$orderAddData=array(
				"username"=>I("post.name"),
				"ordid"=>$ordernum,
				"ordtime"=>NOW_TIME,
				"productid"=>$productidStr,
				"ordtitle"=>$productnameStr,
				"ordbuynum"=>$numStr,
				"ordprice"=>$priceStr,
				"ordfee"=>$sum,
		
		);

		$orderList->add($orderAddData);
        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }
    
        /******************************
        服务器异步通知页面方法
        其实这里就是将notify_url.php文件中的代码复制过来进行处理
        
        *******************************/
    function notifyurl(){
                
                //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=C('alipay_config');
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
               //验证成功
                   //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
           $out_trade_no   = $_POST['out_trade_no'];      //商户订单号
           $trade_no       = $_POST['trade_no'];          //支付宝交易号
           $trade_status   = $_POST['trade_status'];      //交易状态
           $total_fee      = $_POST['total_fee'];         //交易金额
           $notify_id      = $_POST['notify_id'];         //通知校验ID。
           $notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
           $buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号；
                   $parameter = array(
             "out_trade_no"     => $out_trade_no, //商户订单编号；
             "trade_no"     => $trade_no,     //支付宝交易号；
             "total_fee"     => $total_fee,    //交易金额；
             "trade_status"     => $trade_status, //交易状态
             "notify_id"     => $notify_id,    //通知校验ID。
             "notify_time"   => $notify_time,  //通知的发送时间。
             "buyer_email"   => $buyer_email,  //买家支付宝帐号；
           );
           if($_POST['trade_status'] == 'TRADE_FINISHED') {
                     
           }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {            
		   if(!checkorderstatus($out_trade_no)){
					
					//支付成功！ 第一次 插入数据

				orderhandle($parameter); 
			 
                           //进行订单处理，并传送从支付宝返回的参数；
               }
            }
                echo "success";        //请不要修改或删除
         }else {
                //验证失败
				alipayFaild($out_trade_no);
                echo "fail";
        }    
    }
    
    /*
        页面跳转处理方法；
        这里其实就是将return_url.php这个文件中的代码复制过来，进行处理； 
        */
    function returnurl(){
                //头部的处理跟上面两个方法一样，这里不罗嗦了！
        $alipay_config=C('alipay_config');
		$system=session("system");
		$runUrl=session("runUrl");
        $alipayNotify = new \AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
        $trade_no       = $_GET['trade_no'];          //支付宝交易号
        $trade_status   = $_GET['trade_status'];      //交易状态
        $total_fee      = $_GET['total_fee'];         //交易金额
        $notify_id      = $_GET['notify_id'];         //通知校验ID。
        $notify_time    = $_GET['notify_time'];       //通知的发送时间。
        $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；
            
        $parameter = array(
            "out_trade_no"     => $out_trade_no,      //商户订单编号；
            "trade_no"     => $trade_no,          //支付宝交易号；
            "total_fee"      => $total_fee,         //交易金额；
            "trade_status"     => $trade_status,      //交易状态
            "notify_id"      => $notify_id,         //通知校验ID。
            "notify_time"    => $notify_time,       //通知的发送时间。
            "buyer_email"    => $buyer_email,       //买家支付宝帐号
        );
        
 if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
	

	
        if(!checkorderstatus($out_trade_no)){
		
             orderhandle($parameter);  //进行订单处理，并传送从支付宝返回的参数；
    }
		
        $this->success("恭喜您支付宝支付成功，订单号为【{$out_trade_no}】,我们将在一个工作日内与您联系！谢谢！","/{$runUrl}",10);//跳转到配置项中配置的支付成功页面；
    
	
	
	
	
	}else {
		
        echo "trade_status=".$_GET['trade_status'];
        $this->error("抱歉支付宝支付失败！若有疑问请拨打，首页下方的联系电话，谢谢合作！1","{$runUrl}",6);//跳转到配置项中配置的支付失败页面；
    }
 
 }else {
	

    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
	alipayFaild($out_trade_no);
      $this->error("抱歉支付宝支付失败！若有疑问请拨打，首页下方的联系电话，谢谢合作！2","{$runUrl}",6);//跳转到配置项中配置的支付失败页面；
    }
 }
 }
 ?>