<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\OrderModel;
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
	 * @param string $ph		phone 电话
	 * @param number $pid		payment_id 支付方式id
	 */
	public function lists($status=1,$id=null,$sn=null,$sid=null,$ph=null,$pid=null) {
		$model = new OrderModel(); $map = array();
		
		//查询条件 处理
		$map['id'] = (int)$id;
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
				$map['status'] = in_array($status, OrderModel::$mystat) ? $status : 1;
				if (isset($name)) {
					$map['cate_name'] =array('like', '%'.$name.'%');
				}
			}
		}
		/******************/
		
		$list = $this->_lists($model,$map);
		$this->assign('list', $list); //列表
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
}