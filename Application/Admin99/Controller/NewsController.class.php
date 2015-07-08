<?php
/*
	该类用于 新闻操作

*/
	namespace Admin99\Controller;
	use Common\Controller\CommonLoginController;
	Class NewsController extends CommonLoginController{
		public function Index(){
					
				$news=M("news");
				$count=$news->count();
				 $p = getpage($count,8);
				$newsData=$news->order("pushtime desc")->limit($p->firstRow, $p->listRows)->select();
				
			$this->assign('show',$p->show());// 赋值数据集$this->assign('page',$show);// 赋值分页输出

				$this->assign("newsData",$newsData);
				$this->display();
					
			
		}	
		public function Ueditor(){
					$data = new \Org\Util\Ueditor();
					echo $data->output();
		}
		public function Add(){
			//添加操作页面
			
			$this->display();
			
			
		}
		public function DoAdd(){
			$news=M("news");
			
		
			$rules=array(
					array("pushtime",NOW_TIME)
					
				
			);
			if($news->auto($rules)->create()){
					if($news->add()){
						$this->success("添加新的新闻资讯成功！");
						
					}else{
						
						$this->error("插入新闻资讯失败！");
					}
				
			}else{
				$this->error("自动创建失败，插入失败！");
				
			}
				
			
		}
	
		
		
	
		
			//删除
	public function Delete(){
		
			$news=M("news");	
			$id=I("get.id");
			$title=I("get.title");
			if($news->where("id = '{$id}'")->delete()){
		
				
			$this->success("删除【{$title}】成功！");
				
			}else{
			
				$this->error("删除【{$title}】失败！");
			}
			
			
		}
		
		
		//改
	public function Modify(){
		//这是调出 修改数据的方法
	
		$news=M("news");	
		$id=I("get.id");
		//调出数据，并进行修改
		$data=$news->where("id = '{$id}'")->select();

		$this->assign("data",$data);
		$this->assign("hiddenid",$id);
		$this->assign("data",$data);
		$this->display();
		
		
	}
	
	//修改操作
	
	public function DoModify(){
		$news=M("news");
		$data["id"]=I("post.id");
		$data["title"]=I("post.title");
		$data["author"]=I("post.author");
		$data["content"]=I("post.content");
		$time=I("post.pushtime");
		$data["pushtime"]=strtotime($time);
		
				if($news->save($data)){
					$this->success("修改成功！");
					
				}else{
					$this->error("修改失败！");
				
				}

	}
		

		
	}