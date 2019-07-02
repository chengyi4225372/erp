<?php 
namespace MyClass;
class Common extends \C\Action{
	protected static $U = null;
	protected function _bgin(){
		//if(!$this->is_login() && $_GET['a'] != 'LoginDo')$this->Login();
	}
	protected function is_login(){
		$uid = session("uid");
		if($uid){
			return self::$U['uid'] = $uid;
		}else{
			return false;
		}
	}
	protected function Login(){
		$this->v("Public/login");exit;
	}
	public function LoginDo(){
		$w = array("name"=>$_POST['username'],'password' => $_POST['password']);
		$info = $this->m('sys_admin')->where($w)->find();
		if($info){
			session('uid',$info['id']);
			$this->success("登录成功",T('Index/Index'));
		}
	}
	public function Logout(){
		session('uid',null);
		$this->success("退出成功",T('Index/index'));
	}
}