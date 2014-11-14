<?php
namespace Common\Controller;
use Think\Controller;
use Common\Model\ConfigModel;

/**
 * Store 控制器基类
 */
abstract class StoreBaseController extends Controller {
	
	/**
	 * 不做权限处理, 所有用户权限相同
	 * 游客即可访问, 不需要判断登录;
	 * 需要登录的控制器extends别的基类
	 */
	protected function _initialize() {
        defined('SID') or define('SID',is_login());
        if( !SID ){// 还没登录 跳转到登录页面
        	$this->redirect(C('LOGIN_URL'));
        }
        
		$model = new ConfigModel();
		$model->loadConfig();
	}
	
	public function _empty() {
		//TODO 判断是否存在当前controller的模板文件, 若存在,则display 不存在,再跳转
		$this->redirect('Index/index');
	}
	
	protected function _state($id,$act,$modelname) {
		$id = (int)$id;
		if ($id <= 0) {
			$this->error('主要参数非法');
		}
		$class = '\\Manage\\Model\\'.$modelname.'Model';
		$acts = $class::$mystat;
		if (!key_exists($act, $acts)) {
			$this->error('参数非法');
		}
		$model = M($modelname);
		if (false === $model->where('`id`='.$id)->setField('status',$acts[$act])) {
			$this->error('更新失败,未知错误!');
		}
		$this->success('更新成功');
	}
}