<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\StoreModel;
use Manage\Model\StoreAttrModel;
use Think\Model;
use Manage\Model\AttrValModel;
use Manage\Model\CategoryModel;

/**
 * 店铺管理
 * @author RockSnap
 *
 */
class StoreController extends ManageBaseController {
	
	/**
	 * 店铺筛选列表
	 * @param  $status		状态
	 * @param  $id			店铺ID编号
	 * @param  $name		店铺名称 store_name
	 * @param  $account		帐号
	 * @param  $attrs		筛选属性值 以,间隔
	 * @param  $recom		是否推荐
	 * @param  $close		是否暂停营业
	 * @param  $minsd		起送价
	 */
	public function lists($status=null,$id=null,$name=null,$account=null,$attrs=null,$recom=null,$close=null) {
		$model = New StoreModel(); $map = array();
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		
		//查询条件 处理
		$map['id'] = (int)$id; $now_status = $status_view['default'];
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			if (isset($status) && key_exists($status, CategoryModel::$mystat)) { //指定查询状态
				$map['status'] = CategoryModel::$mystat[$status];
				$now_status = $status_view[$status];
			}else {
				$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
			}
			if (isset($name)) {
				$map['store_name'] =array('like', '%'.$name.'%');
			}
			if (isset($account)) {
				$map['account'] = array('like', '%'.$account.'%');
			}
			if (isset($attrs)) {
				$attrs = explode(',', $attrs);
				$StoreAttr_M = new StoreAttrModel();
				$tmp_store_ids = $StoreAttr_M->getStoreByAttr($attrs);
				$map['id'] = array('in', $tmp_store_ids);
			}
			if (isset($recom) && in_array($recom, $haystack)) {
				$map['store_name'] = array('like', '%'.$name.'%');
			}
			if (isset($recom)) {
				$map['is_recom'] = in_array($recom, StoreModel::$recom) ? $recom : 1;
			}
			if (isset($close)) {
				$map['is_close'] = in_array($close, StoreModel::$close) ? $close : 1;
			}
			
			$map['min_send'] = array('EGT', (int)I('minsd')); //起送价
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
			$this->error('请选择要查看的店铺');
		}
		$model = New Model('Store');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function update($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请选择要更新的店铺');
		}
		$model = New StoreModel();
		$data = I('post.');
    	if (!empty($_FILES['store_logo'])) {
    		$data['store_logo'] = $_FILES['store_logo'];
    	}
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
		$model = New StoreModel();
		$data = I('post.');
    	if (!empty($_FILES['store_logo'])) {
    		$data['store_logo'] = $_FILES['store_logo'];
    	}
		if (false === $model->myAdd($data)) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/lists'));
	}
	
	/**
	 * store status 状态修改接口 删除,禁用,启用
	 */
	public function state($id,$act) {
		$this->_state($id, $act, 'Store');
	}
	
	/**
	 * store状态的切换
	 */
	public function toggle($id,$fd) {
		$fields = array('recom'=>'is_recom','close'=>'is_close');
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('主要参数非法');
		}
		$field_key = $fd;
		if (!key_exists($field_key, $fields)) {
			$this->error('参数非法');
		}
		$model = New Model('Store');
		$org = $model->where($map)->getField($fields[$field_key]);
		$new = $org==='1' ? '0' : '1';
		if (false === $model->where($map)->setField($fields[$field_key],$new)) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
	
	/**
	 * 店铺属性查看页面
	 * @param int $id	店铺ID
	 */
	public function attrRead($id) {
		$map['store_id'] = (int)$id;
		if ($map['store_id'] <= 0) {
			$this->error('请选择店铺');
		}
		$this->assign('store_id',$id);
		
		$AttrVal_M = new AttrValModel();
		$attr_hash = $AttrVal_M->hashList();
		$this->assign('attr_hash',$attr_hash);//属性-属性值 的hash数组
		
		$StoreAttr_M = new StoreAttrModel();
		$store_attrs = $StoreAttr_M->where($map)->getField('attr_val_id',true);
		$this->assign('store_attrs',$store_attrs);//该店铺所有的属性值数组
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	/**
	 * 店铺属性更新接口
	 * @param int $id		店铺ID
	 * @param array $vals	属性值id数组
	 */
	public function attrUpdate($id,$vals=array()) {
		$id = (int)$id;
		if ($id <= 0) $this->error('店铺非法');
		if (!is_array($vals)) $this->error('属性非法');
		
		$model = new StoreAttrModel();
		if (false === $model->update($id, $vals)) $this->error($model->getError());
		$this->success('更新成功');
	}
}