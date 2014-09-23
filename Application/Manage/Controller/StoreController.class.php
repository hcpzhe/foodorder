<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\StoreModel;
use Manage\Model\StoreAttrModel;
use Think\Model;

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
	public function lists($status=1,$id=null,$name=null,$account=null,$attrs=null,$recom=null,$close=null) {
		$model = New StoreModel();
		
		//查询条件 处理
		$map['status'] = in_array($status, StoreModel::$status) ? $status : 1;
		
		if (isset($id) && $id>0) {
			$map['id'] = $id;
		}else {
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
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	public function read() {
		$id = (int)I('id');
		if ($id <= 0) {
			$this->error('请选择要查看的店铺');
		}
		$map['id'] = $id;
		$model = New Model('Store');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function add() {
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function insert() {
		$model = New StoreModel();
		if (false === $model->create($_POST,Model::MODEL_INSERT)) {
			$this->error($model->getError());
		}
		if (false === $model->add()) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/lists'));
	}
}