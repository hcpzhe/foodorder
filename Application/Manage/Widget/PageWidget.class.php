<?php
namespace Manage\Widget;
use Common\Controller\ManageBaseController;
use Manage\Model\MenuModel;

/**
 * Manage挂件
 * @author RockSnap
 *
 */
class PageWidget extends ManageBaseController {
	
	public function top() {
		echo '
	<div class="navbar-container" id="navbar-container">
		<div class="navbar-header pull-left">
			<a href="'.U('Index/index').'" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					平台管理中心
				</small>
			</a>
		</div>
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="light-blue">
					<a href="#">
						欢迎, admin
					</a>
				</li>
				<li class="green">
					<a  href="'.U('Index/webcom').'">
						<i class="ace-icon fa fa-lock"></i>
						修改密码
					</a>
				</li>
				<li class="red">
					<a class="bat_a_handle" href="'.U('Common/logout').'">
						<i class="ace-icon fa fa-power-off"></i>
						退出
					</a>
				</li>
			</ul>
		</div>
	</div>
		';
	}
	
	public function foot() {
		echo '
<div class="footer">
	<div class="footer-inner">
		<div class="footer-content">
			<span class="bigger-120"><span class="blue bolder">RcokSnap</span> &copy; 2013-2014</span>
		</div>
	</div>
</div>';
	}
	
	public function menu($id=1) {
		static $_manage_menu = array();
		if (empty($_manage_menu)) {
			$menu_M = new MenuModel();
			$_manage_menu = $menu_M->getHash();
		}
		$active_ids = $menu_M->getDaddy($id);
		echo $this->_menu($_manage_menu, $active_ids);
	}
	
	private function _menu($list,$ids) {
		$return = '<ul class="nav nav-list">';
		foreach ($list as $row) {
			if (empty($ids) && $row['id']=='1') $tmpact = 'active';
			elseif (in_array($row['id'], $ids)) {
				$tmpact = 'active';
				if (!empty($row['son'])) $tmpact .= ' open';
			}else {
				$tmpact='';
			}
			
			$return .= '<li class="'.$tmpact.'">';
			
			$tmpclass = !empty($row['son']) ? 'class="dropdown-toggle"' : '';
			$return .= empty($row['url']) ? '<a href="#" '.$tmpclass.'>' : '<a href="'.U($row['url']).'" '.$tmpclass.'>';
			
			$return .= '<i class="menu-icon"></i>';
			$return .= '<span class="menu-text"> '.$row['title'].' </span>';
			if (!empty($row['son'])) $return .= '<b class="arrow fa fa-angle-down"></b>';
			$return .= '</a><b class="arrow"></b>';
			if (!empty($row['son'])) $return .= $this->_submenu($row['son'], $ids);
		}
		$return .= '</ul>';
		return $return;
	}
	
	private function _submenu($list,$ids) {
		$return = '<ul class="submenu">';
		foreach ($list as $row) {
			if (empty($ids) && $row['id']=='1') $tmpact = 'active';
			elseif (in_array($row['id'], $ids)) {
				$tmpact = 'active';
				if (!empty($row['son'])) $tmpact .= ' open';
			}else {
				$tmpact='';
			}
			
			$return .= '<li class="'.$tmpact.'">';
			
			$tmpclass = !empty($row['son']) ? 'class="dropdown-toggle"' : '';
			$return .= empty($row['url']) ? '<a href="#" '.$tmpclass.'>' : '<a href="'.U($row['url']).'" '.$tmpclass.'>';
			
			$return .= '<i class="menu-icon"></i>';
			$return .= '<span class="menu-text"> '.$row['title'].' </span>';
			if (!empty($row['son'])) $return .= '<b class="arrow fa fa-angle-down"></b>';
			$return .= '</a><b class="arrow"></b>';
			if (!empty($row['son'])) $return .= $this->_submenu($row, $ids);
		}
		$return .= '</ul>';
		return $return;
	}
}