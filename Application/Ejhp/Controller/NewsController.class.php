<?php
/*
		该类用于管理 阿胶页面前台的  新闻资讯类
*/
namespace Ejhp\Controller;
use Think\Controller;
class NewsController extends Controller {
	
	function NewsList(){
				//显示列表
		$News=M("News");
			$Host=$_SERVER['HTTP_HOST'];
		$Info=BottomInfo($Host);
		list($Company,$ICP,$MonitorCode)=$Info;
			$this->assign("Company",$Company);
		$this->assign("ICP",$ICP);
		$this->assign("MonitorCode",$MonitorCode);
		//不需要调出  content内容。浪费资源
		$count=$News->count();
		$p=getpage($count,10);
		$data=$News->Field("content",True)->order("pushtime desc")->limit($p->firstRow, $p->listRows)->select();
		$this->assign("data",$data);
		$this->assign("show",$p->show());
		$this->display("list");
	}
	
	function Detail()
	{
			$Host=$_SERVER['HTTP_HOST'];
		$Info=BottomInfo($Host);
		list($Company,$ICP,$MonitorCode)=$Info;
			$this->assign("Company",$Company);
		$this->assign("ICP",$ICP);
		$this->assign("MonitorCode",$MonitorCode);
		//文章的详细页面的跳转操作
		$id=intval(I("get.id",0));
		$where["id"]=$id;
		$News=M("News");
		$data=$News->where($where)->find();
		
		if($data){
			unset($data);
			$data[]=$News->where($where)->find();
			$this->assign("data",$data);
	
			//上，下篇文章的设置
			
			//1.上篇文章设置
			$wherePrex["id"]=array("lt",$id);
			$prexData=$News->Field("title,id")->where($wherePrex)->order("id desc")->find();
		
				
			//只取小于当前的id的数据
			//存在即有对应的数据
			if($prexData){
				
				$this->assign("prexTitle",$prexData["title"]);
				$this->assign("prexId",$prexData["id"]);
				
			}else{
				$this->assign("prexTitle","没有了");
				$this->assign("prexId",0);
				
			}
			
					//2.下篇文章设置
			$whereNext["id"]=array("gt",$id);
			$nextData=$News->Field("title,id")->where($whereNext)->order("id asc")->find();          //只取大于当前的id的数据
			//存在即有对应的数据
			if($nextData){
				
				$this->assign("nextTitle",$nextData["title"]);
				$this->assign("nextId",$nextData["id"]);
				
			}else{
				$this->assign("nextTitle","没有了");
				$this->assign("nextId",0);
				
			}
				
			//3.当前文章
		
			
			$this->display();
			
		}else{
			
			$this->error("抱歉，您查询的新闻资讯不存在，请联系网站管理员，谢谢合作!");
		}
		
	}	
}