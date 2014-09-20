<?php
namespace Store\Controller;
use Common\Controller\StoreBaseController;

class IndexController extends StoreBaseController {

	public function index(){
		$this->display();
	}
	
	public function welcome() {
		$this->display();
	}
}