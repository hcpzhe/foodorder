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
			
			$map['status'] = in_array($status, CategoryModel::$status) ? $status : 1;
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
		$this->success('更新成功',C('CURRENT_URL_NAME'));
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