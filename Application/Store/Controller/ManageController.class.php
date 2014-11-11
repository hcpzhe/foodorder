<?php
namespace Store\Controller;
use Common\Controller\StoreBaseController;
use Store\Model\StoreModel;
use Think\Model;

/**
 * 店铺管理
 * @author RockSnap
 *
 */
class ManageController extends StoreBaseController {
	
	/**
	 * 信息资料
	 */
	public function info() {
		$model = new Model('Store');
		$info = $model->find(SID);
		$this->assign('info',$info);
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
	
	/**
	 * 商品分类树
	 * @param string $status	状态
	 */
	public function cateTree($status=null) {
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常'); //默认是 所有 未删除
		
		//店铺id必须有
		$map['store_id'] = SID;
		$store_M = new Model('Store');
		$store_info = $store_M->find($map['store_id']);
		if (empty($store_info)) $this->error('店铺不存在');
		$this->assign('store_info',$store_info);
		
		$now_status = $status_view['default'];
		if (isset($status) && key_exists($status, CategoryModel::$mystat)) { //指定查询状态
			$map['status'] = CategoryModel::$mystat[$status];
			$now_status = $status_view[$status];
		}else {
			$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
		}
		
		$model = new \Manage\Model\CategoryModel();
		$cate_tree = $model->ztreeArr($map);
		$cate_tree = json_encode($cate_tree);
		$this->assign('tree_json', $cate_tree); //ztree json
		
		$this->assign('now_status',$now_status); //当前页面筛选的状态
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	/**
	 * 商品分类更新接口
	 */
	public function cateUpdate($id) {
		$id = (int)$id;
		if ($id <= 0) $this->error('请选择要更新的分类');
		
		$model = new \Manage\Model\CategoryModel();
		$data = I('post.');
		
		//验证更新的分类 是当前店铺的
		$cateinfo = $model->where("id=$id AND store_id=".SID)->find();
		if (empty($cateinfo)) $this->error('更新的分类不存在');
		
		if (false === $model->myUpdate($data)) {
			$this->error($model->getError());
		}
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
		
	}
}