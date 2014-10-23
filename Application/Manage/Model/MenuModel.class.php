<?php
namespace Manage\Model;
use Think\Model;

/**
 * 菜单
 */
class MenuModel extends Model {
	
	public function getHash($pid=0) {
		$return = array();
		$map = array();
		$map['pid'] = $pid;
		$map['hide'] = '0';
		$list = $this->where($map)->select();
		if (empty($list)) return $return;
		
		foreach ($list as $row) {
			$tmprow = $row;
			$tmpson = $this->getHash($tmprow['id']);
			if (!empty($tmpson)) $tmprow['son'] = $tmpson;
			$return[] = $tmprow;
		}
		return $return;
	}
	
	/**
	 * 获取指定菜单所有父级菜单
	 */
	public function getDaddy($id) {
		$return = array();
		$map = array();
		$map['id'] = $id;
		$map['hide'] = '0';
		$info = $this->where($map)->find();
		if (!empty($info)) {
			$return[] = $info['id'];
			if ($info['pid'] <= 0) return $return;
			$daddy = $this->getDaddy($info['pid']);
			$return = array_merge($return,$daddy);
		}
		return $return;
	}
	
}
