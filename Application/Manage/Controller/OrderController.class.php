<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\OrderModel;
use Think\Model;
use Manage\Model\OrderGoodsModel;
/**
 * 订单管理
 * @author RockSnap
 */
class OrderController extends ManageBaseController {
	/**
	 * 订单筛选列表
	 * 存在 主键id 或 sn 则忽略其他查询条件
	 * @param number $status	状态
	 * @param number $id		分类id  主键id
	 * @param number $sn		order_sn 订单编号
	 * @param number $sid		store_id 所属店铺id
	 * @param string $ph		phone 电话(like查询)
	 * @param number $cfm		confirm 客户收货确认 0-否 1-是(订单完结)
	 * @param number $unr		unreceived 用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	 */
	public function lists($status=null,$id=null,$sn=null,$sid=null,$ph=null,$cfm=null,$unr=null) {
		$model = new OrderModel(); $map = array();
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		
		//查询条件 处理
		$map['id'] = (int)$id; $now_status = $status_view['default'];
		if (!isset($id) || $map['id']<=0) {
			unset($map['id']);
			
			if (isset($sn)) {
				$map['order_sn'] = $sn;
			}else {
				if ($sid>0) {
					$map['store_id'] = $sid;
				}
				if (isset($ph)) {
					$map['phone'] =array('like', '%'.$ph.'%');
				}
				if (isset($cfm) && in_array($cfm, OrderModel::$S_confirm)) {
					$map['confirm'] = $cfm;
				}
				if (isset($unr) && in_array($unr, OrderModel::$S_unreceived)) {
					$map['unreceived'] = $unr;
				}
				if (isset($status) && key_exists($status, OrderModel::$mystat)) { //指定查询状态
					$map['status'] = OrderModel::$mystat[$status];
					$now_status = $status_view[$status];
				}else {
					$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
				}
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		$this->assign('now_status',$now_status); //当前页面筛选的状态

		if (!empty($list)) {
			$store_M = new Model('Store');
			$store_ids = field_unique($list, 'store_id');
			$map = array('id'=>array('in',$store_ids));
			$storelist = $store_M->where($map)->getField('id,store_name');
			$this->assign('storelist',$storelist); //列表用到的店铺, ID为key索引
		}
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}

	/**
	 * store状态的切换
	 */
	public function toggle($id,$fd) {
		$fields = array('store'=>'store_status','ship'=>'ship_status');
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('主要参数非法');
		}
		$field_key = $fd;
		if (!key_exists($field_key, $fields)) {
			$this->error('参数非法');
		}
		$model = New Model('Order');
		$org = $model->where($map)->getField($fields[$field_key]);
		$new = $org==='1' ? '0' : '1';
		if (false === $model->where($map)->setField($fields[$field_key],$new)) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
	
	/**
	 * 创建订单页面
	 * 必须先选择好 用户 和 店铺
	 */
	public function add() {
		//需要列出店铺所有商品
	}
	
	public function insert() {
		
	}
	
	/**
	 * 订单查看页面
	 */
	public function read($id) {
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('请选择要查看的订单');
		}
		$model = New Model('Order');
		$info = $model->where($map)->find();
		$this->assign('info',$info);
		
		$store_M = new Model('Store');
		$store_info = $store_M->find($info['store_id']);
		$this->assign('store_info',$store_info);
		
		$pay_M = new Model('Payment');
		$pay_info = $pay_M->find($info['payment_id']);
		$this->assign('pay_info',$pay_info);
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
	/**
	 * 订单更新接口
	 */
	public function update($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请选择要更新的订单');
		}
		$model = New OrderModel();
		$data = I('post.');
		if (false === $model->myUpdate($data)) {
			$this->error($model->getError());
		}
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
	}
	
	public function state($id,$act) {
		$this->_state($id, $act, 'Order');
	}
	
	/**
	 * 查看订单内商品列表
	 * @param int $id 订单id
	 */
	public function readGoods($id) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('请先选择订单');
		}
		$og_M = new Model('OrderGoods');
		$list = $og_M->where('order_id='.$id)->select();
		$this->assign('list',$list);
		
		$store_M = new Model('Store');
		$store_info = $store_M->find($id);
		$this->assign('store_info',$store_info);
		
		if (!empty($list)) {
			$goods_M = new Model('goods');
			$goods_ids = field_unique($list, 'goods_id');
			$map = array('id'=>array('in',$goods_ids));
			$goodslist = $goods_M->where($map)->getField('id,goods_name');
			$this->assign('goodslist',$goodslist); //列表用到的, ID为key索引
		}
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	/**
	 * 更新订单商品 page
	 * @param int $oid	订单id
	 * @param int $gid	商品id
	 */
	public function editGoods($oid,$gid) {
		$oid = (int)$oid; $gid = (int)$gid;
		if ($oid <= 0 || $gid <= 0) {
			$this->error('参数非法');
		}
		
		$goods_M = new Model('goods');
		$goods_info = $goods_M->find($gid);
		if (empty($goods_info)) $this->error('此商品不存在');
		$this->assign('goods_info',$goods_info);
		
		$where = array(
			'order_id' => $oid,
			'goods_id' => $gid
		);
		$og_M = new Model('OrderGoods');
		$info = $og_M->where($where)->find();
		if (empty($info)) $this->error('订单内此商品不存在');
		$this->assign('info',$info);
		
		$this->display();
	}
	
	/**
	 * 更新订单商品接口
	 * 单价,数量,总价
	 */
	public function updateGoods($order_id,$goods_id) {
		$oid = (int)$order_id; $gid = (int)$goods_id;
		if ($oid <= 0 || $gid <= 0) {
			$this->error('参数非法');
		}
		
		$model = new OrderGoodsModel();
		$data = I('post.');
		if (false === $model->myUpdate($data)) {
			$this->error($model->getError());
		}
		$this->success('更新成功',cookie(C('CURRENT_URL_NAME')));
		
	}
	
	/**
	 * 删除订单内某个商品 接口
	 * @param int $oid	订单id
	 * @param int $gid	商品id
	 */
	public function delGoods($oid,$gid) {
		$where = array(
			'order_id' => $oid,
			'goods_id' => $gid
		);
		$og_M = new Model('OrderGoods');
		$og_M->where($where)->delete();
		$this->success('删除成功');
	}
}