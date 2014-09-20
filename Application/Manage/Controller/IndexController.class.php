<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;

class IndexController extends ManageBaseController {

	public function index(){
		$this->display();
	}
	
	public function welcome() {
		$this->display();
	}
}