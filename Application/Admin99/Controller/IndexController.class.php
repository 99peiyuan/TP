<?php
namespace Admin99\Controller;
use Common\Controller\CommonLoginController;
Class IndexController extends CommonLoginController {
		public function Index(){
			$this->redirect("User/Index");
			
		}
}