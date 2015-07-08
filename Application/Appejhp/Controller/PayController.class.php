<?php
namespace Appejhp\Controller;
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
										$payment=I("post.payment");
										$word=I("post.word");
										$client=I("post.client");
										$num=I("post.num");
										$productid=I("post.productid");
										$subject=I("post.subject");
										$products=M("products");
										$whereProduct=array("id"=>$productid);
										$productPrice=$products->field("price,productname")->where($whereProduct)->find();   
										$productname=$productPrice["productname"];
										$total=$productPrice["price"]*$num;    //这是单品，所以该价格就是这张订单的总价格了
										$time=NOW_TIME;
										$sql="insert into tp_order (payment,productid,num,total,word,ordernum,ordertime,client) values('{$payment}','{$productid}','{$num}','{$total}','{$word}','{$ordernum}','{$time}','{$client}')";
										$order=M("order");
										if(!$order->execute($sql)){
											
												//插入失败，同时要删除掉  用户的联系信息
												$where["id"]=$contactId;
												$contact->where($where)->delete();
											
												$this->error("提交订单失败，请联系管理员3！");	
											
										}
										
					//插入到对应的支付宝详细表中，插入预付款 选项
					
					
		$orderAddData=array(
				"username"=>I("post.name"),
				"ordid"=>$ordernum,
				"ordtime"=>NOW_TIME,
				"productid"=>$productid,
				"ordtitle"=>$productname,
				"ordbuynum"=>$num,
				"ordprice"=>$price,
				"ordfee"=>$total,
		
		);

		$orderList->add($orderAddData);
									
		//2.支付宝的 付款与 详情表的操作
 //这里我们通过TP的C函数把配置项参数读出，赋给$alipay_config；
       $alipay_config=C('alipay_config');  
 
        /**************************请求参数**************************/
        $payment_type = "1"; //支付类型 //必填，不能修改
   
        $seller_email = C('alipay.seller_email');//卖家支付宝帐户必填										
        $out_trade_no = $ordernum;//商户订单号 通过支付页面的表单进行传递，注意要唯一！
		$subject=I("post.subject");

	
		/****************  测试金额  ************/

        $total_fee = $total;   //付款金额  //必填 通过支付页面的表单进行传递

		/************订单描述，即产品名字的全部拼写***********/

        $body = $productname;  //订单描述 通过支付页面的表单进行传递
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
		
		
        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }
    
      
 }
 ?>