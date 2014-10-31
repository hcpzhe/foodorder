<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Think\Model;
use Manage\Model\GoodsModel;
use Manage\Model\CategoryModel;

/**
 * 商品管理
 * @author RockSnap
 *
 */
class GoodsController extends ManageBaseController {
	
	/**
	 * 指定店铺 的 商品列表
	 * @param number $sid		所属店铺ID
	 * @param string $status	状态string
	 */
	public function lists($sid,$status=null) {
		$model = New GoodsModel(); $map = array();
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		
		//查询条件 处理 
		$map['store_id'] = (int)$sid;
		if ($map['store_id']<=0) $this->error('参数非法');
		$store_M = new Model('Store');
		$store_info = $store_M->find($map['store_id']);
		if (empty($store_info)) $this->error('店铺不存在');
		$this->assign('store_info',$store_info); //店铺信息
		
		if (isset($status) && key_exists($status, GoodsModel::$mystat)) { //指定查询状态
			$map['status'] = GoodsModel::$mystat[$status];
			$now_status = $status_view[$status];
		}else {
			$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
			$now_status = $status_view['default'];
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		$this->assign('now_status',$now_status); //当前页面筛选的状态
		
		if (!empty($list)) {
			$cate_M = new Model('Category');
			$cate_ids = field_unique($list, 'cate_id'); //列表中用到的会员ID
			$map = array('id'=>array('in',$cate_ids));
			$catelist = $cate_M->where($map)->getField('id,cate_name');
			$this->assign('catelist',$catelist); //列表用到的分类, ID为key索引
		}
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	public function read($id) {
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('请选择商品');
		}
		$model = New Model('Goods');
		$info = $model->where($map)->find();
		
		$store_M = New Model('Store');
		$store_info = $store_M->find($info['store_id']);
		$this->assign('store_info',$store_info); //所属店铺
		
		$cate_M = new CategoryModel();
		if ($info['cate_id'] > 0) {
			$info['cate_name'] = $cate_M->where('id='.$info['cate_id'])->getField('cate_name');
		}else {
			$info['cate_name'] = '无分类';
		}
		
		$map = array(
			'store_id' => $info['store_id'],
			'status' => 1
		);
		$cate_tree = $cate_M->ztreeArr($map); //ztree json
		$tmp = array(array('id'=>null, 'pId'=>0, 'name'=>'无分类'));
		$cate_tree = array_merge(array(array('id'=>0, 'pId'=>0, 'name'=>'无分类','open'=>true)),$cate_tree);//$tmp + $cate_tree;
		$cate_tree = json_encode($cate_tree);
		$this->assign('tree_json', $cate_tree); //ztree json
		$this->assign('info',$info); //商品信息
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function update($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请选择要更新的商品');
		}
		$model = New GoodsModel();
		$data = I('post.');
		if (false === $model->myUpdate($data)) {
			$this->error($model->getError());
		}
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
	}
	
	/**
	 * 新建商品
	 * @param number $sid	店铺ID
	 */
	public function add($sid) {
		//验证店铺是否存在
		$map['store_id'] = (int)$sid;
		if ($map['store_id']<=0) $this->error('参数非法');
		$store_M = new Model('Store');
		$store_info = $store_M->find($map['store_id']);
		if (empty($store_info)) $this->error('店铺不存在');
		$this->assign('store_info',$store_info); //店铺信息
		
		$map['status'] = '1';//只要状态正常商品分类
		$cate_M = new CategoryModel();
		$cate_tree = $cate_M->ztreeJson($map); //ztree json
		$this->assign('tree_json', $cate_tree); //ztree json
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function insert() {
		$model = New GoodsModel();
		$data = I('post.');
		if (false === $model->myAdd($data)) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/lists'));
	}
	
	/**
	 * 状态修改接口
	 * @param number $id	主键ID
	 * @param string $act	del,forbid,allow
	 */
	public function state($id,$act) {
		$this->_state($id, $act, 'Goods');
	}
	
}