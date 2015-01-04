<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Think\Model;
use Home\Model\MemberModel;

class IndexController extends HomeBaseController {
	
	public function index() {
		//筛选属性id为1的所有属性值
		$attrval_M = new Model('AttrVal');
		$attrval = $attrval_M->where("attr_id=1 AND status=1")->order("sort asc,id desc")->getField("id,attr_val");
		$this->assign('attrval',$attrval);//筛选分类
		
		$this->display();
	}
	
	public function welcome() {
		$this->display();
	}
	
	public function cart($sid) {
		$store_M = new Model('Store');
		$storeinfo = $store_M->find($sid);
		$this->assign('storeinfo',$storeinfo);
		
		$this->display();
	}
	
	public function cartFlush($sid,$cart=array()) {
		$cart = (array)$cart;
		unset($cart['time']);
		if (!empty($cart)) {
			$goods_M = new Model('Goods');
			$map = array(
					'store_id'=>$sid,
					'id' => array('in',array_keys($cart)),
					'status' => '1'
			);
			$list = $goods_M->where($map)->order("sort ASC,id DESC")->select();
			$goodslist = array(); $total = array('num'=>0,'price'=>0);
			foreach ($list as $row) {
				$tmp = $row;
				$tmp['num'] = $cart[$tmp['id']];
				if ($tmp['num'] > 0) {
					$total['num'] += $tmp['num'];
					$total['price'] += $tmp['num']*$tmp['price'];
					$goodslist[] = $tmp;
				}
			}
			$total['price'] = number_format($total['price'],2,".","");
			$this->assign('goodslist',$goodslist);
			$this->assign('total',$total);
		}
		$this->display();
	}
	
	/**
	 * 送餐信息页面
	 */
	public function ship($sid,$totalprice) {
		$this->assign('totalprice',$totalprice);
		$this->display();
	}
	
	/**
	 * 确定订单,生成订单接口
	 */
	public function bulidOdr() {
		$data = I('post.');
		$data['member_id'] = $data['mid'];
		$data['payment_id'] = 1;
		$data['order_note'] = trim($data['order_note']);
		$model = new \Manage\Model\OrderModel();
		if (false === $model->myAdd($data)) {
			$this->error($model->getError());
		}
		$this->success('下单成功',U('order?cfm=0'));
		//跳转至"我的订单"页面
	}
	
	/**
	 * 自动登录, 首次登录则生成会员
	 * @param string $tk 令牌
	 */
	public function bulidMem($tk,$mid=null) {
		if ($this->_idtoken($tk)) {
			//验证成功
			$member_M = new MemberModel();
			$newid = $member_M->bulidNew($mid);
			if (false === $newid) {
				$this->error($member_M->getError());
			}
			$this->success($newid);
		}else {
			//验证失败
			$this->error("非法操作!!!");
		}
	}
	
	/**
	 * 订单筛选列表
	 * 存在 主键id 或 sn 则忽略其他查询条件
	 * @param number $cfm		confirm 客户收货确认 0-否 1-是(订单完结)
	 * @param number $unr		unreceived 用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	 * @param number $ship		ship_status 配送状态 0-未开始 1-配送中
	 */
	public function order($cfm=null,$unr=null,$ship=null) {
		if (!MID) $this->error("非法操作!!!");
		$map = array();
		$map['member_id'] = MID;
		$map['status'] = 1; //状态正常的订单
		if (isset($cfm) && in_array($cfm, \Manage\Model\OrderModel::$S_confirm)) {
			$map['confirm'] = $cfm;
		}
		if (isset($unr) && in_array($unr, \Manage\Model\OrderModel::$S_unreceived)) {
			$map['unreceived'] = $unr;
		}
		if (isset($ship) && in_array($ship, \Manage\Model\OrderModel::$S_ship_status)) {
			$map['ship_status'] = $ship;
		}
		/*************///筛选状态
		if ($map['ship_status'] === '0') {
			$condition = '1';
		}else if ($map['confirm'] === '0'){
			$condition = '2';
		}else {
			$condition = '0';
		}
		$this->assign('condition',$condition);//筛选状态
		/**************/
		$model = new Model('Order');
		$list = $model->where($map)->order("add_time DESC")->select();
		$this->assign('list', $list); //列表
		
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
}