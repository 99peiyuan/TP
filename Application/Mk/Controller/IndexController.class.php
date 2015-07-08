<?php
namespace Mk\Controller;
use Think\Controller;
class IndexController extends Controller {
		
    public function Index(){
		$Url=__SELF__  ;             //搜索引擎代码
	
			//先获取最后一个  / 的位置 ，然后用 现在的长度减去 该位置，剩下的就是最后一个 / 后面的内容了，截取该部分即可
			
			 $LastStr=strrchr($Url,"/");
			 $UrlLength=strlen($Url);
			$LastStrLength=strlen($LastStr)-2;  //去掉  /?  占用两个字符
			$PosStrLength=$UrlLength-$LastStrLength;
			
			 $SEOCode=substr($Url,$PosStrLength,$UrlLength);
			
			//$SEOCode=substr($Url,"");
	//$appUrl=__APP__."/Appmaka/Index";
		//判断是否手机端
		$isMoblie=D("Phone");
		//var_dump($isMoblie);
		if($isMoblie->judge()){
			//手机端
				$this->redirect("/Appmaka/Index/Index/SEOCode/{$SEOCode}");
		}   
			/*底部版权  icp备案 的判断   */
			$Host=$_SERVER['HTTP_HOST'];  //当前网址
			//粤ICP备14090790号
			
			//  1 .haoli13
			$Str1="haoli13";
			
			
			/**审核阶段****************************************/
/* if(strpos($Host,$Str1)>-1){
		
		//查看是否为玛卡 页面
		if(strtolower(MODULE_NAME)=="mk"){
			
			$this->Redirect("Test/Index");
			
		}
		
	
	
}if(strpos($Host,$Str1)>-1){
		
		//查看是否为玛卡 页面
		if(strtolower(MODULE_NAME)=="mk"){
			
			$this->Redirect("Test/Index");
			
		}
		
	
	
} */
			


		/****************************************************************/
			
		
		
			
				/*    3.底部信息的给予   */
		$Host=$_SERVER['HTTP_HOST'];
		$Info=BottomInfo($Host);
		list($Company,$ICP,$MonitorCode)=$Info;

			$this->assign("Company",$Company);
		$this->assign("ICP",$ICP);
		$this->assign("MonitorCode",$MonitorCode);
		
		
		
				//调出产品的数据
		$product=M("products");
		$data=$product->select();
		
		
		$this->assign("data",$data);
		$this->assign("Company",$Company);
		$this->assign("ICP",$ICP);
        $this->display();
    }
}
