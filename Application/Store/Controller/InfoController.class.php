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
	 * 信息资料
	 */
	public function info() {
		$model = new Model('Store');
		$info = $model->find(SID);
		$this->assign('info',$info);
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	/**
	 * 资料更新接口
	 */
	public function infoUpdate() {
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
	
}