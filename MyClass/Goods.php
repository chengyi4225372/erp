<?php
namespace MyClass;
class Goods extends \MyClass\Common{
	public function Index(){
		$c = $this->m('h_goods')->count();
		$p = e('Page',$c,50);
		$list = $this->m('h_goods')->where('"show" = 0')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v('Goods/Index');
	}

	public function Edit(){
		$info = $this->m('h_goods')->find($_GET['id']);
		$this->s('info',$info)->v();
	}
	
	public function EditDo(){
		$w['id'] = $_POST['id'];
		$data['`show`']  = 1;
		$this->m('h_goods')->where($w)->save($data);
		$this->success("该项目已审核通过");
	}
	
	public function OkPay(){
		$w['id'] = $_POST['id'];
		$data['pay']  = 1;
		$this->m('h_goods')->where($w)->save($data);
	}
	
	public function Ongoing(){
		$c = $this->m('h_goods')->count();
		$p = e('Page',$c,50);
		$list = $this->m('h_goods')->where('`show` > 0')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v("Goods/Index");
	}
}