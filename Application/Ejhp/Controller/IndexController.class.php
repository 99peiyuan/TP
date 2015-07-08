<?php
namespace Ejhp\Controller;
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
			$this->redirect("/Appejhp/Index/Index/SEOCode/{$SEOCode}");
		}  
		
				//2.调出新闻资讯列表
		$News=M("News");
		//不需要调出  content内容。浪费资源
		$newsList=$News->Field("content,pushtime",True)->order("pushtime desc")->limit(14)->select();
		$this->assign("newsList",$newsList);

			/*    3.底部信息的给予   */
		$Host=$_SERVER['HTTP_HOST'];
		$Info=BottomInfo($Host);
		list($Company,$ICP,$MonitorCode)=$Info;
			$this->assign("Company",$Company);
		$this->assign("ICP",$ICP);
		$this->assign("MonitorCode",$MonitorCode);
	
				//调出产品的数据
		$product=M("products");
		$data=$product->where("flag=1")->order('id asc')->select();
		$this->assign("data",$data);
		//调出问答
		$question=M("question");
		$questionData=$question->select();
		$this->assign("questionData",$questionData);
        $this->display();
    }
}