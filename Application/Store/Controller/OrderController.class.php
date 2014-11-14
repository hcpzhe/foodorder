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
	 * @param number $status	状态
	 * @param number $sn		order_sn 订单编号
	 * @param string $ph		phone 电话(like查询)
	 * @param number $cfm		confirm 客户收货确认 0-否 1-是(订单完结)
	 * @param number $unr		unreceived 用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	 */
	public function lists($status=null,$sn=null,$ph=null,$cfm=null,$unr=null) {
		$model = new OrderModel(); $map = array('store_id'=>STID);
		$status_view = array('default'=>'所有','del'=>'已删除','forbid'=>'禁用','allow'=>'正常');
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
}