<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\CategoryModel;
use Think\Model;

class CategoryController extends ManageBaseController {
	/**
	 * 店铺分类筛选列表
	 * 主键id 店铺id 和 父id 最少要有一个
	 * @param number $status	状态
	 * @param number $id		分类id  主键id
	 * @param number $sid		store_id 所属店铺id
	 * @param number $pid		parent_id 父id
	 * @param string $name		cate_name 分类名称
	 */
	public function lists($status=1,$id=null,$sid=null,$pid=null,$name=null) {
		$model = new CategoryModel(); $map = array();

		//查询条件 处理
		$map['id'] = (int)$id;
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			
			//所属店铺id 和 父id 最少要有一个
			if (isset($sid)) $map['store_id'] = (int)$sid;
			if (isset($pid)) $map['parent_id'] = (int)$pid;
			if ($map['store_id']<=0 && $map['parent_id']<=0) {
				// 店铺id 和 父id 都没有
				$this->error('参数非法');
			}
			
			$map['status'] = in_array($status, CategoryModel::$mystat) ? $status : 1;
			if (isset($name)) {
				$map['cate_name'] =array('like', '%'.$name.'%');
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	/**
	 * 指定店铺的 商品分类 树
	 * @param number $sid		店铺ID
	 * @param string $status	状态
	 */
	public function tree($sid,$status=null) {
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常'); //默认是 所有 未删除
		
		//店铺id必须有
		$map['store_id'] = (int)$sid;
		if ($map['store_id']<=0) $this->error('参数非法');
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
		
		$model = new CategoryModel(); $cate_tree = array();
		$list = $model->where($map)->order('sort asc,id desc')->select();
		foreach ($list as $row) {
			$tmp = array(
					'id' => $row['id'],
					'pId' => $row['parent_id'],
					'name' => $row['cate_name'],
					'open' => true,
					'sort' => $row['sort'],
					'status' => $row['status']
			);
			$tmp['pname'] = $tmp['pId'] > 0? $model->where('id='.$tmp['pId'])->getField('cate_name') : '顶级分类';
			
			$cate_tree[] = $tmp;
		}
		$cate_tree = json_encode($cate_tree);
		
		$this->assign('tree_json', $cate_tree); //
		$this->assign('now_status',$now_status); //当前页面筛选的状态
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function read($id) {
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('请选择要查看的分类');
		}
		$model = New Model('Category');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
	
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	public function update($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请选择要更新的分类');
		}
		$model = New CategoryModel();
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
		$model = New CategoryModel();
		$data = I('post.');
		if (false === $model->myAdd($data)) {
			$this->error($model->getError());
		}
		$this->success('新建成功',U(CONTROLLER_NAME.'/lists'));
	}

	/**
	 * category status 状态修改接口 删除,禁用,启用
	 */
	public function state($id,$a) {
		$this->_state($id, $a, 'Category');
	}
}