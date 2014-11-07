<?php
namespace Manage\Controller;
use Common\Controller\ManageBaseController;
use Manage\Model\ConfigModel;

/**
 * 系统配置
 * @author RockSnap
 *
 */
class ConfigController extends ManageBaseController {
	
	/**
	 * 按分组查看
	 * @param int $gid	group分组编号
	 */
	public function group($gid=null) {
		$groups = ConfigModel::$group;
		$this->assign('groups',$groups); //分组的数组
		
		$map = ConfigModel::$ablemap; //基础查询条件
		if (array_key_exists($gid, ConfigModel::$group)) {
			$map['group'] = $gid;
		}else {
			reset($groups);
			$map['group'] = $gid = key($groups);
		}
		$model = new ConfigModel();
		$list = $model->where($map)->order('sort asc, id desc')->select();
		$this->assign('list',$list); //配置数组
		$this->assign('nowgid',$map['group']); //当前的分组id
		$this->display();
	}
}