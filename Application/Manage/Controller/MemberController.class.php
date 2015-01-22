<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\MemberModel;
use Manage\Model\CategoryModel;
use Think\Model;

class MemberController extends ManageBaseController {
	/**
	 * 用户筛选列表
	 * @param number $status	状态
	 * @param number $id		会员主键id
	 * @param string $acu		account 会员帐号
	 * @param number $reg		null-所有会员; 0-临时会员(account为null的); 1-注册会员
	 */
	public function lists($status=null,$id=null,$acu=null,$reg=null) {
		$model = new MemberModel(); $map = array(); $status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		
		//查询条件 处理
		$map['id'] = (int)$id; $now_status = $status_view['default'];
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			
			if (isset($reg)) {
				$map['account'] = $reg==='0' ? array('exp',' is NULL ') : array('exp',' is not NULL ');
			}
			if (isset($status) && key_exists($status, CategoryModel::$mystat)) { //指定查询状态
				$map['status'] = CategoryModel::$mystat[$status];
				$now_status = $status_view[$status];
			}else {
				$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
			}
			if (isset($acu)) {
				$map['account'] =array('like', '%'.$acu.'%');
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map,'reg_time desc');
		$this->assign('list', $list); //列表
		$this->assign('now_status',$now_status); //当前页面筛选的状态
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
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
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
	public function state($id,$act) {
		$acts = MemberModel::$mystat;
		if (!key_exists($act, $acts)) {
			$this->error('参数非法');
		}
		$model = new Model('Member');
		if (false === $model->where("`id`='$id'")->setField('status',$acts[$act])) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
}