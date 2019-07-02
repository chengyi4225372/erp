<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Model 模型控制器
========================================|*/
namespace Drive;
class Model{
	// 查询语句
 	private $sql = array("table"=>"","field" => "", "where" => "","order" => "","limit" => "","group" => "","having" => "","join" => "");
 	private $db = null;
 	
 	public function __construct(){
 		$this->db();
 	}
 	// 连惯查询
    public function __call($md,$args){
        $md = strtolower($md);
        if(array_key_exists($md,$this->sql)){
        	if($md == 'where'){
				$this->sql[$md] =  $this->expwhere($args[0]);
			}elseif($md == 'table'){
				$this->sql[$md] =  $this->_table($args);
			}else{
				$this->sql[$md] = $args[0];
			}			
        }
        return $this;
    }
	public function clern($sql){
		foreach($sql as $k=>$v)$k!="table" &$this->sql[$k] = null;
	}
	// 事物启动
	public function startTrans(){
		$this->db->startTrans();
	}
	// 事物提交
	public function commit(){
		$this->db->commit();
	}
	// 事物回滚
	public function rollback(){
		$this->db->rollback();
	}
    // 查询一条记录
    public function find($id=false){
		$sql  = "SELECT ";
		$sql .= $this->sql['field'] ? $this->sql['field'] : "*";
		$sql .= " FROM {$this->sql['table']} ";
		$sql .= $this->sql['join'] ? " LEFT JOIN " .$this->sql['join'] : "";
		$sql .= $this->sql['where'] ? " WHERE {$this->sql['where']} " : ($id ? "WHERE id = ".$id : "WHERE 0");
		$sql .= $this->sql['group'] ? " GROUP BY ".$this->sql['group'] :'';
		$sql .= $this->sql['having'] ? " HAVING ".$this->sql['having'] :'';
		$sql .= $this->sql['order'] ? " ORDER BY ".$this->sql['order'] :'';
		$sql .= " LIMIT 1";
		$this->clern($this->sql);
		return $this->db->getOne($sql);
    }
    // 查询一多记录
    public function select(){
    	$sql  = "SELECT ";
    	$sql .= $this->sql['field'] ? $this->sql['field'] : "*";
    	$sql .= " FROM {$this->sql['table']} ";
    	$sql .= $this->sql['join'] ? " LEFT JOIN " .$this->sql['join'] : "";
    	$sql .= $this->sql['where'] ? " WHERE " . $this->sql['where']  : "";
    	$sql .= $this->sql['group'] ? " GROUP BY ".$this->sql['group'] :'';
    	$sql .= $this->sql['having'] ? " HAVING ".$this->sql['having'] :'';
    	$sql .= $this->sql['order'] ? " ORDER BY ".$this->sql['order'] :'';
    	$sql .= $this->sql['limit'] ? " limit ".$this->sql['limit'] : '';
		$this->clern($this->sql);
    	return $this->db->getAll($sql);
    } 
    // 修改记录
    public function save($set){
    	$data = "";
		if(is_array($set)){
    		foreach($set as $k=>$v){
    			$data .= "{$k}='{$this->db->escapeString($v)}',";
    		}
			$data = substr($data,0,-1);
		}else{
			$data = $set;
		}    	
    	$sql  = "UPDATE {$this->sql['table']}";
		$sql .=	$this->sql['join']  ? ( "," . $this->sql['join']) : '' ;
		$sql .= " SET {$data}";
		$sql .= @$this->sql['where'] ? " WHERE {$this->sql['where']}" : (@$set['id'] ? " WHERE id = ".$set['id'] : "");
		return $this->db->getSave($sql);
    }
	// 字段自增自减
	public function inc($zd,$num = 1,$j = false){
		$sql   = "UPDATE {$this->sql['table']}";
		if($j){
			$num = -$num;
		}
		$sql  .= " SET {$zd} = {$zd} + {$num}";
		$sql  .= $this->sql['where'] ? " WHERE {$this->sql['where']}" : '';
		return $this->db->getSave($sql);
	}
    // 删除记录
    public function del($id){
    	$sql  = "DELETE FROM {$this->sql['table']} ";
    	$sql .= @$this->sql['where'] ? "WHERE {$this->sql['where']}" : ($id ? "WHERE id = ".$id : "0");
		$this->clern($this->sql);
    	return $this->db->getSave($sql);
    }
	public function delAll($ids){
		$sql = "DELETE FROM {$this->sql['table']}";
		$sql .= @$this->sql['where'] ? "WHERE {$this->sql['where']}" : ($ids ? "WHERE id in (".join(',',$ids).")": "");
		$this->clern($this->sql);
    	return $this->db->getSave($sql);
	}
    // 添加记录
    public function add($data){
    	$field = '';
    	$value = '';
    	foreach($data as $k=>$v){
    		$field .= $this->db->escapeString($k).",";
    		$value .= "'".$this->db->escapeString($v) ."',";
    	}
    	$field = substr($field,0,-1);
    	$value = substr($value,0,-1);
    	$sql = "INSERT INTO {$this->sql['table']} ({$field}) VALUES ({$value})";
		$this->clern($this->sql);
    	return $this->db->getAdd($sql);
    }
	public function addAll($data){
		$table = $this->sql['table'];
		foreach($data as $v){			
			$ids[] = $this->add($v);
			$this->sql['table'] = $table;
		}
		return $ids;
	}
	public function sql($sql){
		return $this->db->query($sql);
	}
	// table 语句过虑
	private function _table($args){
		return $args[0];
	}
    // where 语句解析
	private function expwhere($w){
		if(!is_array($w)){
			$sql = $w;
		}else{
			foreach($w as $k=>$v){
				if($k =="OR"){
					foreach($v as $ko => $vo){
						$ors .= " AND " .  $this->k($ko,$vo);
					}
					$sql .= " OR (" . trim($ors," AND") . ")";
				}else{
					$sql .= " AND " . $this->k($k,$v);
				}
			}
		}
		return trim($sql," AND");
	}
	private function k($k,$v){
		if(!is_array($v)){
			$sql = "{$k} = '{$v}'";
		}else{
			switch($v[0]){
				case 'like' : $sql .= "{$k} like '%{$v[1]}%'";break;
				case 'in'   : $sql .= "{$k} in(". join(',',$v[1]) .")";break;
				default     : $sql .= "{$k} {$v[0]} {$v[1]}";
			}
		}
		return $sql;
	}
    // 统计
    public function count(){
    	$sql  = "SELECT ";
		$sql .= $this->sql['field'] ? $this->sql['field'] : "*";
    	$sql .= " FROM {$this->sql['table']} ";
    	$sql .= $this->sql['join'] ? " LEFT JOIN " .$this->sql['join'] : "";
    	$sql .= $this->sql['where'] ? " WHERE {$this->sql['where']} " : "";
    	$sql .= $this->sql['group'] ? " GROUP BY ".$this->sql['group'] :'';
    	$sql .= $this->sql['having'] ? " HAVING ".$this->sql['having'] :'';
    	$sql .= $this->sql['order'] ? " ORDER BY ".$this->sql['order'] :'';
    	$sql .= $this->sql['limit'] ? " limit ".$this->sql['limit'] : ' limit 200000';
		$this->clern($this->sql);
    	return $this->db->getNum($sql);
    }
    public function db(){
    	if($this->db == null){
    		$this->db = e('Mysql');
    	}
    }
}
