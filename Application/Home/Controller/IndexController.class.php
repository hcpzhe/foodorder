<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Think\Model;

class IndexController extends HomeBaseController {

	public function index() {
		//筛选属性id为1的所有属性值
		$attrval_M = new Model('AttrVal');
		$attrval = $attrval_M->where("attr_id=1 AND status=1")->order("sort asc,id desc")->getField("id,attr_val");
		$this->assign('attrval',$attrval);//筛选分类
		
		$this->display();
	}
	
	public function welcome() {
		$this->display();
	}
}