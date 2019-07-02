<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Model 模型控制器
========================================|*/
namespace Extend;
class Mysql{
	private $pconnect = 1;
    private $connection_id = 0;
	private $link = 0;
	private $transTimes = 0;
	private $queryid = 0;
	private $numRows = 0;
    public function __construct(){
    	$db_cfg = \Chinax3::$conf["db"];
    	$this->pconnect = $db_cfg['pcennect'];
    	if(!$this->connection_id){
    		$this->connect($db_cfg);
    	}
    }
    function connect($db_config){
        if ($this->pconnect){
            $this->connection_id = mysql_pconnect($db_config["hostname"], $db_config["username"], $db_config["password"]);
        }else{
            $this->connection_id = @mysql_connect($db_config["hostname"], $db_config["username"], $db_config["password"]);
        }
        if ( ! $this->connection_id ){
            $this->error("mysql服务器无响应");
        }
        if ( ! @mysql_select_db($db_config["database"], $this->connection_id) ){
            $this->error("数据库不存在");
        }
        if ($db_config["charset"]) {
            @mysql_unbuffered_query("SET NAMES '".$db_config["charset"]."'");
        }
        return true;
    }
    public function query($sql){
    	if ( $this->queryid ) {    
    		$this->free();    
    	}
    	$this->queryid = mysql_query($sql);
		if($this->queryid === false)$this->error($sql,0);
    }
    public function getOne($sql){
    	$this->query($sql);
    	if(!$this->queryid)$this->error();
    	return mysql_fetch_assoc($this->queryid);
    }
    public function getNum($sql){
    	$this->query($sql);
    	return mysql_num_rows($this->queryid);
    }
    public function getAll($sql) {
    	$this->query($sql);
    	$this->numRows = mysql_num_rows($this->queryid);
    	//返回数据集
    	$result = array();
    	if($this->numRows >0) {
    		while($row = mysql_fetch_assoc($this->queryid)){
    			$result[]   =   $row;
    		}
    		mysql_data_seek($this->queryid,0);
    	}
    	return $result;
    }
    public function getSave($sql){
    	$this->query($sql);
    	if(!$this->queryid)$this->error();
    	return mysql_affected_rows($this->connection_id);
    }
    public function getAdd($sql){
    	$this->query($sql);
    	if(!$this->queryid)$this->error("添加数据失败");
    	return mysql_insert_id($this->connection_id);
    }
    public function escapeString($str) {
    	if($this->connection_id) {
    		return mysql_real_escape_string($str,$this->connection_id);
    	}else{
    		return mysql_escape_string($str);
    	}
    }
    public function free() {
    	mysql_free_result($this->queryid);
    	$this->queryID = null;
    }
	// 启动事物
    public function startTrans() {
    	if ( !$this->connection_id ) return false;
    	//数据rollback 支持
    	if ($this->transTimes == 0) {
    		mysql_query('START TRANSACTION', $this->connection_id);
    	}
    	$this->transTimes++;
    	return ;
    }
  	// 提交
    public function commit() {
    	if ($this->transTimes > 0) {
    		$result = mysql_query('COMMIT', $this->connection_id);
    		$this->transTimes = 0;
    		if(!$result){
    			$this->error();
    			return false;
    		}
    	}
    	return true;
    }
    // 回滚
    public function rollback() {
    	if ($this->transTimes > 0) {
    		$result = mysql_query('ROLLBACK', $this->connection_id);
    		$this->transTimes = 0;
    		if(!$result){
    			$this->error();
    			return false;
    		}
    	}
    	return true;
    }
    private function error($msg='',$code='1'){
		throw new \Exception("MySql Error : " .$msg);	
    }
}
?>