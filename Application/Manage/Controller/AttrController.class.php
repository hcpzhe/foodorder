<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\AttrModel;
use Think\Model;
use Manage\Model\AttrValModel;
/**
 * 筛选属性管理
 * @author RockSnap
 *
 */
class AttrController extends ManageBaseController {
	
	public function lists($status=1,$id=null,$name=null) {
		$model = New AttrModel(); $map = array();
		
		//查询条件 处理
		$map['id'] = (int)$id;
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			$map['status'] = in_array($status, AttrModel::$status) ? $status : 1;
			if (isset($name)) {
				$map['attr_name'] =array('like', '%'.$name.'%');
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
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('请选择要查看的属性');
		}
		
		$model = New Model('Attr');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function update($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请选择要更新的属性');
		}
		$model = New AttrModel();
		$data = I('post.');
		if (false === $model->create($data,Model::MODEL_UPDATE)) {
			$this->error($model->getError());
		}
		if (false === $model->where('`id`='.$id)->save()) {
			$this->error($model->getError());
		}
		$this->success('更新成功',C('CURRENT_URL_NAME'));
	}
	
	public function add() {
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function insert() {
		$model = New AttrModel();
		if (false === $model->create($_POST,Model::MODEL_INSERT)) {
			$this->error($model->getError());
		}
		if (false === $model->add()) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/lists'));
	}
	
	/**
	 * attr status状态修改接口 删除,禁用,启用
	 */
	public function state($id,$a) {
		$acts = AttrModel::$status;
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('主要参数非法');
		}
		$act_key = $a;
		if (!key_exists($act_key, $acts)) {
			$this->error('参数非法');
		}
		
		$model = New Model('Attr');
		if (false === $model->where('`id`='.$id)->setField('status',$acts[$act_key])) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
	
	/**
	 * 属性值列表
	 */
	public function valLists($atid,$status=1,$id=null) {
		$model = New AttrValModel(); $map = array();
		
		//查询条件 处理
		$map['id'] = (int)$id;
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			$map['attr_id'] = (int)$atid;
			if ($map['attr_id'] <= 0) $this->error('参数非法');
			$map['status'] = in_array($status, AttrValModel::$status) ? $status : 1;
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	public function valRead($id) {
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('请选择要查看的属性值');
		}
		
		$model = New Model('AttrVal');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
		
		$attr_M = New Model('Attr');
		$attr = $attr_M->where('`id`='.$info['attr_id'])->find();
		$this->assign('attr',$attr); //该属性值的 所属属性
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function valUpdate($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请选择要更新的属性值');
		}
		$model = New AttrValModel();
		$data = I('post.');
		if (false === $model->create($data,Model::MODEL_UPDATE)) {
			$this->error($model->getError());
		}
		if (false === $model->where('`id`='.$id)->save()) {
			$this->error($model->getError());
		}
		$this->success('更新成功',C('CURRENT_URL_NAME'));
	}
	
	public function valAdd() {
		//需要列出所有可用的 所属属性
		$attr_M = New Model('Attr');
		$attrlist = $attr_M->where(AttrModel::$ablemap)->select();
		$this->assign('attrlist',$attrlist); //所有可用的 所属属性
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function valInsert() {
		$model = New AttrValModel();
		$data = I('post.');
		if (false === $model->myAdd($data)) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/valLists'));
	}
	
	/**
	 * attr_val status状态修改接口 删除,禁用,启用
	 */
	public function valState($id,$a) {
		$acts = AttrValModel::$status;
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('主要参数非法');
		}
		$act_key = $a;
		if (!key_exists($act_key, $acts)) {
			$this->error('参数非法');
		}
	
		$model = New Model('AttrVal');
		if (false === $model->where('`id`='.$id)->setField('status',$acts[$act_key])) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
}