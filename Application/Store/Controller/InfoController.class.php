<?php
namespace Store\Controller;
use Common\Controller\StoreBaseController;
use Store\Model\StoreModel;
use Think\Model;

/**
 * 店铺信息管理
 * @author RockSnap
 *
 */
class InfoController extends StoreBaseController {
	
	/**
	 * 店铺信息资料
	 */
	public function info() {
		$model = new Model('Store');
		$info = $model->find(STID);
		$this->assign('info',$info);
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	/**
	 * 店主信息
	 */
	public function onwerInfo(){
		$model = new Model('Store');
		$info = $model->find(STID);
		$this->assign('info',$info);
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	/**
	 * 资料更新接口
	 */
	public function update() {
		$model = New StoreModel();
		$data = I('post.');
		$data['store_logo'] = $_FILES['store_logo'];
    	if (empty($_FILES['store_logo']) || $data['store_logo']['error']=='4') {
    		unset($data['store_logo']);
    	}
		if (false === $model->myUpdate($data)) {
			$this->error($model->getError());
		}
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
	}

	/**
	 * 店铺属性查看页面
	 */
	public function attrRead() {
		$map['store_id'] = STID;
		
		$AttrVal_M = new \Manage\Model\AttrValModel();
		$attr_hash = $AttrVal_M->hashList();
		$this->assign('attr_hash',$attr_hash);//属性-属性值 的hash数组
		
		$StoreAttr_M = new \Manage\Model\StoreAttrModel();
		$store_attrs = $StoreAttr_M->where($map)->getField('attr_val_id',true);
		$this->assign('store_attrs',$store_attrs);//该店铺所有的属性值数组
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	/**
	 * 店铺属性更新接口
	 * @param array $vals	属性值id数组
	 */
	public function attrUpdate($vals=array()) {
		if (!is_array($vals)) $this->error('属性非法');
	
		$model = new \Manage\Model\StoreAttrModel();
		if (false === $model->update(STID, $vals)) $this->error($model->getError());
		$this->success('更新成功');
	}
}