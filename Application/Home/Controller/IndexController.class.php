<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Think\Model;
use Home\Model\MemberModel;

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
	public function cart() {
		$this->assign('list','123');
		$this->display();
	}
	
	/**
	 * 确认下单页面
	 */
	public function confirm() {
		$this->display();
	}
	
	/**
	 * 生成新用户 接口
	 * @param string $tk 令牌
	 */
	public function bulidMem($tk) {
		if ($this->_idtoken($tk)) {
			//验证成功
			$member_M = new MemberModel();
			$newid = $member_M->bulidNew();
			if (false === $newid) {
				$this->error($member_M->getError());
			}
			$this->success($newid);
		}else {
			//验证失败
			$this->error("非法操作!!!");
		}
	}
}