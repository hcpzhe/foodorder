<?php
namespace Store\Controller;
use Common\Controller\StoreBaseController;
use Think\Model;
use Store\Model\OrderModel;

/**
 * 店铺订单管理
 * @author RockSnap
 *
 */
class OrderController extends StoreBaseController {
	/**
	 * 订单筛选列表
	 * 存在  sn 则忽略其他查询条件
	 * @param number $status	启用,删除的状态
	 * @param number $sn		order_sn 订单编号
	 * @param string $ph		phone 电话(like查询)
	 * @param number $cfm		confirm 客户收货确认 0-否 1-是(订单完结)
	 * @param number $unr		unreceived 用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	 * @param number $st		store_status 店铺接收订单状态 0-否 1-是;
	 * @param number $ship		ship_status 配送状态 0-未开始 1-是;
	 */
	public function lists($status=null,$sn=null,$ph=null,$cfm=null,$unr=null,$st=null,$ship=null) {
		$model = new OrderModel(); $map = array('store_id'=>STID);
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
		//查询条件 处理
		 $now_status = $status_view['default'];
		if (isset($sn)) {
			$map['order_sn'] = $sn;
		}else {
			if (isset($ph)) {
				$map['phone'] =array('like', '%'.$ph.'%');
			}
			if (isset($cfm) && in_array($cfm, OrderModel::$S_confirm)) {
				$map['confirm'] = $cfm;
			}
			if (isset($unr) && in_array($unr, OrderModel::$S_unreceived)) {
				$map['unreceived'] = $unr;
			}
			if (isset($st) && in_array($unr, OrderModel::$S_store_status)) {
				$map['store_status'] = $st;
			}
			if (isset($ship) && in_array($unr, OrderModel::$S_ship_status)) {
				$map['ship_status'] = $ship;
			}
			if (isset($status) && key_exists($status, OrderModel::$mystat)) { //指定查询状态
				$map['status'] = OrderModel::$mystat[$status];
				$now_status = $status_view[$status];
			}else {
				$map['status'] = array('EGT',0); //默认查询状态为未删除的数据
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		$this->assign('now_status',$now_status); //当前页面筛选的状态
		
		//支付方式
		if (!empty($list)) {
			$payment_M = new Model('Payment');
			$pay_ids = field_unique($list, 'payment_id');
			$map = array('id'=>array('in',$pay_ids));
			$paylist = $payment_M->where($map)->getField('id,pay_name');
			$this->assign('paylist',$paylist); //列表用到的支付方式, ID为key索引
		}
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	/**
	 * 修改订单总金额 接口
	 * @param int $oid		订单id
	 * @param money $amt	总金额
	 */
	public function amount($oid,$amt) {
		if (!preg_match('/^\d+(\.\d+)?$/',$amt)) $this->error('输入的金额不合法, 最多2位小数');
		if ($oid <= 0) $this->error('订单不合法, 请重新选择订单');
		$model = new OrderModel();
		if (false === $model->checkAuth($oid)) $this->error('没有权限');
		
		$model->amount = $amt;
		$model->update_time = NOW_TIME;
		if (false ===$model->where("`id`=$oid")->save()) $this->error('更新失败, 请重试');
		$this->success('更新成功');
	}
	/**
	 * 店铺接收
	 * @param int $oid		订单id
	 */
	public function store($oid) {
		if ($oid <= 0) $this->error('订单不合法, 请重新选择订单');
		$model = new OrderModel();
		if (false === $model->checkAuth($oid)) $this->error('没有权限');
		
		$order = $model->find($oid);
		if ($order['store_status'] == '1') $this->error('已经接收, 无需重复接收');
		
		$model->store_status = '1';
		$model->store_time = NOW_TIME;
		$model->update_time = NOW_TIME;
		if (false ===$model->where("`id`=$oid")->save()) $this->error('接收失败, 请重试');
		$this->success('接收成功');
	}
	/**
	 * 发货
	 * @param int $oid		订单id
	 */
	public function ship($oid) {
		if ($oid <= 0) $this->error('订单不合法, 请重新选择订单');
		$model = new OrderModel();
		if (false === $model->checkAuth($oid)) $this->error('没有权限');
		
		$order = $model->find($oid);
		if ($order['ship_status'] == '1') $this->error('已经发货, 无需重复发货');
		
		$model->ship_status = '1';
		$model->ship_time = NOW_TIME;
		$model->update_time = NOW_TIME;
		if (false ===$model->where("`id`=$oid")->save()) $this->error('发货失败, 请重试');
		$this->success('更新成功, 开始发货');
	}
	
	/**
	 * 订单查看页面
	 */
	public function read($id) {
		$map['id'] = (int)$id;
		if ($map['id'] <= 0) {
			$this->error('请选择要查看的订单');
		}
		$model = new OrderModel();
		if (false === $model->checkAuth($map['id'])) $this->error('没有权限');
		
		$info = $model->where($map)->find();
		$this->assign('info',$info); //订单信息
		
		$pay_M = new Model('Payment');
		$pay_info = $pay_M->find($info['payment_id']);
		$this->assign('pay_info',$pay_info);//支付方式信息
		
		$og_M = new Model('OrderGoods');
		$ordergoods = $og_M->where('order_id='.$id)->select();
		$this->assign('ordergoods',$ordergoods); //订单商品列表
		if (!empty($ordergoods)) {
			$goods_M = new Model('goods');
			$goods_ids = field_unique($ordergoods, 'goods_id');
			$map = array('id'=>array('in',$goods_ids));
			$goodslist = $goods_M->where($map)->getField('id,goods_name,image');
			$this->assign('goodslist',$goodslist); //列表用到的, ID为key索引
		}
		
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
}