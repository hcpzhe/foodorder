<?php
namespace Home\Widget;
use Common\Controller\HomeBaseController;

/**
 * Home挂件
 * @author RockSnap
 *
 */
class PageWidget extends HomeBaseController {
	//$key 区分大小写
	public function foot($key='Index/index') {
		$arr = array(
				'Index/index' => array('icon'=>'fa-home','name'=>'首页'),
				'Index/heart' => array('icon'=>'fa-heart-o','name'=>'我的收藏'),
				'Index/order' => array('icon'=>'fa-file-text-o','name'=>'我的订单'),
				'Index/user' => array('icon'=>'fa-user','name'=>'个人中心'),
		);
		$str = '
<div class="navbar navbar-default navbar-fixed-bottom" role="navigation">
	<div class="container-fluid">
		<div class="row RS_idx_foot">';
		foreach ($arr as $k => $v) {
			$tmp = ($k == $key)? ' active ': '';
			$str .= '
			<div class="col-xs-3 text-center '.$tmp.'">
				<a class="icon" href="'.U($k).'"><i class="fa '.$v['icon'].'"></i></a>
				<a href="'.U($k).'">'.$v['name'].'</a>
			</div>';
		}
		$str .= '
		</div>
	</div>
</div>';
		echo $str;
	}
	
}