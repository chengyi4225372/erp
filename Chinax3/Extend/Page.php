<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------
namespace Extend;
class Page {
    
    // 分页栏每页显示的页数
    public $rollPage = 10;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =    array(
		'header'=>'条记录',
		'prev'=>'<',
		'next'=>'>',
	//	'topage'=>'到<input name="p" type="text">页<a>确定</a>',
		'theme'=>'%upPage% %linkPage% %downPage%  %topage% %header%',
		'mini' => '%upPage% %linkPage%/%nowCoolPage% %downPage%',
	);
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->varPage      =   'p' ;
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if($this->nowPage<1){
            $this->nowPage  =   1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
	public function mini(){
		if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
		
		$var =  !empty($_POST)?$_POST:$_GET;
		if(empty($var)) {
			$parameter  =   array();
		}else{
			$parameter  =   $var;
		}
		
		$parameter[$p]  =   '__PAGE__';
		$parameter = array_filter($parameter);
		if(c('urlType') == false){
			$url = '?';
			foreach($parameter as $k=>$v){
				$url .= "{$k}={$v}&";
			}
			$url = trim($url,'&');
		}else{
			unset($parameter[m],$parameter[a]);
			$url = "./";
			foreach($parameter as $k=>$v){
				$url .= "{$k}-{$v}-";
			}
			$url = trim($url,'-');
		}

        //上下翻页字符串
        $upRow          =   $this->nowPage > 1 ? $this->nowPage-1 : $this->nowPage;
        $downRow        =   $this->nowPage < $this->totalPages ? $this->nowPage+1 : $this->nowPage;
        $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        $downPage   = 	" <a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
		$now = "<span class='active'>".$this->nowPage ."</span>";
  
		// 跳转到页
		$topage = "";
        $pageStr     =   str_replace(
            array('%upPage%', '%linkPage%','%nowCoolPage%','%downPage%'),
            array($upPage,$now,$this->totalPages,$downPage),
			$this->config['mini']
		);
        return $pageStr;
	}
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
		
		$var =  !empty($_POST)?$_POST:$_GET;
		if(empty($var)) {
			$parameter  =   array();
		}else{
			$parameter  =   $var;
		}
		
		$parameter[$p]  =   '__PAGE__';
		$parameter = array_filter($parameter);
		if(c('urlType') == false){
			$url = '?';
			foreach($parameter as $k=>$v){
				$url .= "{$k}={$v}&";
			}
			$url = trim($url,'&');
		}else{
			unset($parameter[m],$parameter[a]);
			$url = "./";
			foreach($parameter as $k=>$v){
				$url .= "{$k}-{$v}-";
			}
			$url = trim($url,'-');
		}

        //上下翻页字符串
        $upRow          =   $this->nowPage > 1 ? $this->nowPage-1 : $this->nowPage;
        $downRow        =   $this->nowPage < $this->totalPages ? $this->nowPage+1 : $this->nowPage;
        $upPage     =   $this->nowPage > 1 ? "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>" :null;
        $downPage   =   $this->nowPage < $this->totalPages ? " <a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>" : "";
   

        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page       =   ($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<span class='current'>".$page."</span>";
                }
            }
        }
		// 跳转到页
		$topage = "";
        $pageStr     =   str_replace(
            array('%upPage%', '%linkPage%','%topage%','%downPage%','%header%'),
            array($upPage,$linkPage,$this->config['topage'],$downPage,'&nbsp;共找到' . $this->totalRows . '条记录'),
			$this->config['theme']
		);
        return $pageStr;
    }

}