<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\MemberModel;

class MemberController extends ManageBaseController {
	/**
	 * 用户筛选列表
	 * @param number $status	状态
	 * @param number $id		会员主键id
	 * @param string $acu		account 会员帐号
	 * @param number $reg		null-所有会员; 0-临时会员(account为null的); 1-注册会员
	 */
	public function lists($status=1,$id=null,$acu=null,$reg=null) {
		$model = new MemberModel(); $map = array();
		
		//查询条件 处理
		$map['id'] = (int)$id;
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			
			if (isset($reg)) {
				$map['account'] = $reg==='0' ? array('exp',' is NULL ') : array('exp',' is not NULL ');
			}
			$map['status'] = in_array($status, CategoryModel::$status) ? $status : 1;
			if (isset($acu)) {
				$map['account'] =array('like', '%'.$acu.'%');
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	public function read($id) {
		$map['id'] = $id;
		$model = New Model('Member');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
	
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function update() {
		$model = New MemberModel();
		$data = I('post.');
		if (false === $model->myUpdate($data)) {
			$this->error($model->getError());
		}
		$this->success('更新成功',C('CURRENT_URL_NAME'));
	}
	
	public function add() {
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function insert() {
		$model = New MemberModel();
		$data = I('post.');
		if (false === $model->create($data,Model::MODEL_INSERT)) {
			$this->error($model->getError());
		}
		if (false === $model->add()) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/lists'));
	}
	
	/**
	 * member status 状态修改接口 删除,禁用,启用
	 */
	public function state($id,$a) {
		$acts = MemberModel::$status;
		if (!key_exists($a, $acts)) {
			$this->error('参数非法');
		}
		$model = new Model('Member');
		if (false === $model->where('`id`='.$id)->setField('status',$acts[$a])) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
}