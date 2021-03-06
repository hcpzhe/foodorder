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
	
	public function lists($status=null,$id=null,$name=null) {
		$model = New AttrModel(); $map = array();
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		
		//查询条件 处理
		$map['id'] = (int)$id; $now_status = $status_view['default'];
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			if (isset($status) && key_exists($status, AttrModel::$mystat)) { //指定查询状态
				$map['status'] = AttrModel::$mystat[$status];
				$now_status = $status_view[$status];
			}else {
				$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
			}
			if (isset($name)) {
				$map['attr_name'] =array('like', '%'.$name.'%');
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		$this->assign('now_status',$now_status); //当前页面筛选的状态
		
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
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
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
	public function state($id,$act) {
		$this->_state($id, $act, 'Attr');
	}
	
	/**
	 * 属性值列表
	 */
	public function valLists($atid,$status=null) {
		$model = New AttrValModel(); $map = array();
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		
		//查询条件 处理
		$map['attr_id'] = (int)$atid;
		if ($map['attr_id'] <= 0) $this->error('参数非法');
		$attr_M = new Model('Attr');
		$attr_info = $attr_M->find($map['attr_id']);
		if (empty($attr_info) || false===$attr_info) $this->error('类别不存在');
		
		if (isset($status) && key_exists($status, AttrValModel::$mystat)) { //指定查询状态
			$map['status'] = AttrValModel::$mystat[$status];
			$now_status = $status_view[$status];
		}else {
			$now_status = $status_view['default'];
			$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		$this->assign('attr_info',$attr_info);// 所属类别信息
		$this->assign('now_status',$now_status); //当前页面筛选的状态
		
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
				
		//cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
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
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
	}
	
	public function valAdd() {
		//需要列出所有可用的 所属属性
		$attr_M = New Model('Attr');
		$attrlist = $attr_M->where(AttrModel::$ablemap)->order('sort asc,id desc')->select();
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
		$this->success('新建成功',U(CONTROLLER_NAME.'/valLists',array('atid'=>$data['attr_id'])));
	}
	
	/**
	 * attr_val status状态修改接口 删除,禁用,启用
	 */
	public function valState($id,$act) {
		$this->_state($id, $act, 'AttrVal');
	}
}