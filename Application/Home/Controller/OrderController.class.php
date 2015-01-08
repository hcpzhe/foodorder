<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Think\Model;

class OrderController extends HomeBaseController {
	
	/**
	 * 订单详情
	 * @param int $oid		订单编号
	 */
	public function info($oid) {
		$model = new Model('Order');
		$info = $model->where('id='.$oid)->find();
		$this->assign('info',$info);
		if (empty($info)) $this->error('订单不存在');
		
		$store_M = new Model('Store');
		$storeinfo = $store_M->where('id='.$info['store_id'])->find();
		$this->assign('storeinfo',$storeinfo);
		
		$og_M = new Model('OrderGoods');
		$oglist = $og_M->where('order_id='.$info['id'])->select();
		$goods_M = new Model('Goods');
		$goodslist = array();
		foreach ($oglist as $row) {
			$tmp = $row;
			$tmpgoods = $goods_M->where('id='.$tmp['goods_id'])->find();
			$tmp['goods_name'] = $tmpgoods['goods_name'];
			$tmp['image'] = $tmpgoods['image'];
			$goodslist[] = $tmp;
		}
		$this->assign('goodslist',$goodslist);
		
		if ($info['store_status'] == '0') {
			//店铺未接收, 可以取消订单
			$status_str = '店家还未接收此订单';
		}elseif ($info['ship_status'] == '0') {
			//店铺已接收,未开始配送
			$status_str = '待发货';
		}elseif ($info['confirm'] == '0') {
			//待收货, 可以收货确认
			$status_str = '待收货';
			$timeshow = array('发货时间','ship_time');
		}else {
			//交易完成
			$status_str = '交易成功';
			$timeshow = array('成交时间','finish_time');
		}
		$this->assign('status_str',$status_str);
		$this->assign('timeshow',$timeshow);
		
		$this->display();
	}

	/**
	 * 取消订单 接口
	 * @param int $oid		订单编号
	 */
	public function cancel($oid) {
		$map['id'] = (int)$oid;
		if ($map['id'] <= 0) {
			$this->error('参数非法');
		}
		$model = new Model('Order');
		$info = $model->where($map)->find();
		/*
			if ($info['store_status'] == 1) {
		$this->error('取消失败, 请联系店家! 店家已经开始为您制作了');
		}
		*/
		if ($info['ship_status'] == '1') {
			$this->error('已经开始为您配送了,无法取消此订单!');
		}
		$model->where($map)->setField('status','0');
		$this->success('订单取消成功');
	}
	
	/**
	 * 确认收货 接口
	 * @param int $oid		订单编号
	 */
	public function confirm($oid) {
		$map['id'] = (int)$oid;
		if ($map['id'] <= 0) {
			$this->error('参数非法');
		}
		$model = new Model('Order');
		$info = $model->where($map)->find();
		
		if ($info['ship_status'] != '1') {
			$this->error('还没开始为您配送,无法确认收货哦!');
		}
		$data = array(
				'confirm' => '1',
				'confirm_time' => NOW_TIME,
				'confirm_expired' => '0',
				'finish_time' => NOW_TIME
		);
		$model->where($map)->save($data);
		$this->success('确认收货成功');
	}
	
}