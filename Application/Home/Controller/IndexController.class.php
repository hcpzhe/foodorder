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
		$data['member_id'] = MID;
		$data['payment_id'] = 1;
		$model = new \Manage\Model\OrderModel();
		if (false === $model->myAdd($data)) {
			$this->error($model->getError());
		}
		$this->success('下单成功');
	}
	
	/**
	 * 生成新用户 接口
	 * @param string $tk 令牌
	 */
	public function bulidMem($tk) {
		if ($this->_idtoken($tk)) {
			//验证成功
			$member_M = new MemberModel();
			$newid = $member_M->bulidNew();
			if (false === $newid) {
				$this->error($member_M->getError());
			}
			$this->success($newid);
		}else {
			//验证失败
			$this->error("非法操作!!!");
		}
	}
}