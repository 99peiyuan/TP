<?php
namespace AppEjhp\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function Index(){
		//调出产品的数据
		$product=M("products");
		$data=$product->where("flag=2")->order("id asc")->select();
		$this->assign("data",$data);
        $this->display();
    }
}