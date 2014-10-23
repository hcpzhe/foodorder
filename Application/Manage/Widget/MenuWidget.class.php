<?php
namespace Manage\Widget;
use Common\Controller\ManageBaseController;
use Manage\Model\MenuModel;

/**
 * Manage菜单挂件
 * @author RockSnap
 *
 */
class MenuWidget extends ManageBaseController {
	
	public function haha() {
		static $_manage_menu = array();
		if (empty($_manage_menu)) {
			$menu_M = new MenuModel();
			$_manage_menu = $menu_M->getHash();
		}
		$active_ids = $menu_M->getDaddy(1);
		$return = $this->_menu($_manage_menu, $active_ids);
		echo $return;
	}
	
	private function _menu($list,$ids) {
		$return = '<ul class="nav nav-list">';
		foreach ($list as $row) {
			if (in_array($row['id'], $ids) || $row['id']=='1') {
				$tmpact = 'active';
				if (!empty($row['son'])) $tmpact .= ' open';
			}else $tmpact='';
			
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
	
	private function _submenu($list,$ids) {
		$return = '<ul class="submenu">';
		foreach ($list as $row) {
			if (in_array($row['id'], $ids) || $row['id']=='1') {
				$tmpact = 'active';
				if (!empty($row['son'])) $tmpact .= ' open';
			}else $tmpact='';
			
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