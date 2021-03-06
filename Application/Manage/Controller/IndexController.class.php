<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\UserModel;

class IndexController extends ManageBaseController {

	public function index(){
		$this->display();
	}
	
	/**
	 * user 修改密码 页面
	 */
	public function changePwd() {
		$this->display();
	}
	
	/**
	 * user 修改密码 接口
	 * @param string $org	原始密码
	 * @param string $new	新密码
	 * @param string $renew	确认新密码
	 */
	public function chgPwd($org,$new,$renew) {
		if (empty($org) || empty($new)) {
			$this->error('密码不能为空');
		}
		if ($new!==$renew) {
			$this->error('新密码两次输入不一致');
		}
		$user_M = new UserModel();
		if (false === $user_M->chgPwd($org, $new)) {
			$this->error($user_M->getError());
		}
		$this->success('密码修改成功！');
	}
}