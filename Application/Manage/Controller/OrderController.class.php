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
	 * @param string $ph		phone 电话(like查询)
	 * @param number $cfm		confirm 客户收货确认 0-否 1-是(订单完结)
	 * @param number $unr		unreceived 用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	 */
	public function lists($status=1,$id=null,$sn=null,$sid=null,$ph=null,$cfm=null,$unr=null) {
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
				if (isset($cfm) && in_array($cfm, OrderModel::$S_confirm)) {
					$map['confirm'] = $cfm;
				}
				if (isset($unr) && in_array($unr, OrderModel::$S_unreceived)) {
					$map['unreceived'] = $unr;
				}
				
				$map['status'] = in_array($status, OrderModel::$mystat) ? $status : 1;
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
	public function read() {
		
	}
	
	/**
	 * 订单更新接口
	 */
	public function update() {
		
	}
	
	/**
	 * 查看订单内商品列表
	 */
	public function readGoods() {
		
	}
	
	/**
	 * 更新订单商品接口
	 * 单价,数量,总价
	 */
	public function updateGoods() {
		
	}
	
	/**
	 * 删除订单内某个或多个商品 接口
	 */
	public function delGoods() {
		
	}
}