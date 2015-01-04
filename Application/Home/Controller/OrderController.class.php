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
		if ($info['store_status'] == 1) {
			$this->error('取消失败, 请联系店家! 店家已经开始为您制作了');
		}
	}
	
}