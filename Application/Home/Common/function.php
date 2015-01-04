<?php
use Think\Model;
//获取
function get_num_odr($oid) {
	$model = new Model('OrderGoods');
	$list = $model->where('order_id='.$oid)->select();
	$num = 0;
	foreach ($list as $row) {
		$num += $row['quantity'];
	}
	return $num;
}