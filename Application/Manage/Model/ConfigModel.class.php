<?php
namespace Manage\Model;
use Think\Model;

/**
 * 配置模型
 */
class ConfigModel extends Model {
	public static $mystat = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	public static $group = array('1'=>'基本配置','2'=>'内容配置','3'=>'用户配置','4'=>'系统配置'); //配置分组
	
	
    protected $_validate = array(
        array('name', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );
	
    protected $_auto = array(
        array('name', 'strtoupper', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_BOTH),
    );
	
    /**
     * 载入数据库中的配置
     * @return NULL
     */
    public function loadConfig() {
    	$config =   S('DB_CONFIG_DATA');
    	if(!$config) {
    		$config = $this->lists();
    		S('DB_CONFIG_DATA',$config);
    	}
    	C($config); //添加配置
    	return null;
    }
    
    /**
     * 获取配置列表
     * @return array 配置数组
     */
    public function lists(){
        $map    = array('status' => 1);
        $data   = $this->where($map)->field('type,name,value')->select();
        
        $config = array();
        if($data && is_array($data)){
            foreach ($data as $value) {
                $config[$value['name']] = $this->parse($value['type'], $value['value']);
            }
        }
        return $config;
    }

    /**
     * 根据配置类型解析配置
     * @param  integer $type  配置类型
     * @param  string  $value 配置值
     */
    private function parse($type, $value){
        switch ($type) {
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if(strpos($value,':')){
                    $value  = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                }else{
                    $value =    $array;
                }
                break;
        }
        return $value;
    }
	
    /**
     * 配置更新
     * 数据更新成功后, 要清除DB_CONFIG_DATA缓存
     */
    public function myUpdate($config) {
		if($config && is_array($config)){
			foreach ($config as $name => $value) {
				$map = array('name' => $name);
				$this->where($map)->setField('value', $value);
			}
		}
		S('DB_CONFIG_DATA',null);
		return true;
	}
}
