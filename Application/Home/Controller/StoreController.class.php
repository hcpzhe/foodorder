<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
use Think\Model;
use Manage\Model\StoreAttrModel;

class StoreController extends HomeBaseController {
	
	/**
	 * 店铺筛选列表 (只查询status=1的)
	 * @param  $id			店铺ID编号
	 * @param  $keyword		like 店铺名称 store_name || 商品名称 goods_name
	 * @param  $attrs		筛选属性值id 以,间隔
	 * @param  $recom		是否推荐
	 * @param  $close		是否暂停营业
	 * @param  $minsd		起送价
	 */
	public function lists($id=null,$keyword=null,$attrs=null,$minsd=null) {
		//筛选属性id为1的所有属性值
		$attrval_M = new Model('AttrVal');
		$attrval = $attrval_M->where("attr_id=1 AND status=1")->order("sort asc,id desc")->getField("id,attr_val");
		$this->assign('attrval',$attrval);//筛选分类
		
		$model = New Model('Store'); $map = array();
		$order = 'is_recom DESC, sort ASC, id ASC';
		//查询条件 处理
		$map['status'] = array('EGT',0);
		if (isset($keyword)) {
			$this->assign('so_keyword',$keyword);
			$so_map = array();
			$so_map['_logic'] = 'or';
			//匹配店铺名称
			$so_map['store_name'] =array('like', '%'.$keyword.'%');
			//匹配商品名称
			$goods_M = new Model('Goods');
			$so_goods_map = array(
					'goods_name' => array('like', '%'.$keyword.'%'),
					'status' => 1
			);
			$so_goods_store_ids = $goods_M->where($so_goods_map)->getField('store_id',true);
			$so_goods_store_ids = array_unique($so_goods_store_ids);
			if (!empty($so_goods_store_ids)) $so_map['id'] = array('in',$so_goods_store_ids);
			
			$map['_complex'] = $so_map;
		}
		$map['is_close'] = '0';
		$minsd = (int)$minsd;
		if ($minsd > 0) {
			$map['min_send'] = array('ELT', $minsd); //起送价
		}
		/******************/
		if (isset($attrs)) {
			$attrs = explode(',', $attrs);
			$StoreAttr_M = new StoreAttrModel();
			$tmp_store_ids = $StoreAttr_M->getStoreByAttr($attrs);
			if (!empty($tmp_store_ids)) {
				$map['id'] = array('in', $tmp_store_ids);
				$list = $model->where($map)->order($order)->select();
				//$list = $this->_lists($model,$map,$order);
			}else {
				$list = array();
			}
		}else {
			$list = $model->where($map)->order($order)->select();
			//$list = $this->_lists($model,$map,$order);
		}
		$this->assign('list', $list); //列表
		
		// 记录当前列表页的cookie
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		
		$this->display();
	}
	
	/**
	 * 店铺商品
	 * @param int $sid		店铺id
	 */
	public function goods($sid) {
		$sid = (int)$sid;
		if ($sid<=0) $this->error('请选择正确的店铺');
		
		//店铺名称
		$store_M = new Model('Store');
		$store_info = $store_M->find($sid);
		if (empty($store_info) || false === $store_info) $this->error('店铺不存在, 请选择正确的店铺');
		$this->assign('storeinfo',$store_info);//店铺信息
		
		//先取出所有分类, 然后把商品放进去
		$cate_M = new Model('Category');
		$cate_where = array(
				'status' => '1',
				'store_id' => $sid
		);
		$cate_list = $cate_M->where($cate_where)->order('sort ASC, id DESC')->select();
		
		$returnlist = array(); $goods_M = New Model('Goods');
		//先取出无分类的商品
		$tmpmap = array('status'=>'1','cate_id'=>'0','store_id' => $sid);
		$nocate_goods = $goods_M->where($tmpmap)->order('sort ASC, id DESC')->select();
		if (!empty($nocate_goods)) {
			$returnlist[] = array('id'=>'0','goods'=>$nocate_goods,'cate_name'=>'默认分类');
		}
		
		foreach ($cate_list as $cate_row) {
			$tmpmap = array('status'=>array('EGT',0),'cate_id'=>$cate_row['id']);
			$tmp_goods = $goods_M->where($tmpmap)->order('sort ASC, id DESC')->select();
			if (!empty($tmp_goods)) {
				$tmp_cate = $cate_row;
				$tmp_cate['goods'] = $tmp_goods;
				$returnlist[] = $tmp_cate;
			}
		}
		
		$this->assign('list',$returnlist);//分类-商品 列表
		cookie(C('CURRENT_URL_NAME'),$_SERVER['REQUEST_URI']);
		$this->display();
	}
	
}