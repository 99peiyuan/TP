<?php
namespace Mk\Controller;
use Think\Controller;
Class EmptyController extends Controller {  
	function index(){

		$this->error("Sorry,404,I can't find this page!");
	}


 }
