<?php
namespace MyClass;
class Order extends \MyClass\Common{
	public function Index(){
		$p = e('Page',$this->m('h_bill_log')->where('isbill = 0')->count(),50);
		$list = $this->m('h_bill_log')->where('isbill = 0')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}
	public function BillLog(){
		$p = e('Page',$this->m('h_bill_log')->where('isbill = 1')->count(),50);
		$list = $this->m('h_bill_log')->where('isbill = 1')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}
	public function InvestLog(){
		$p = e('Page',$this->m('h_invest_log')->where('isbill = 0')->count(),50);
		$list = $this->m('h_invest_log')->where('isbill = 0')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}
	public function Open(){
		$list = $this->m('h_bill_log')->where('id = '.$_POST['id'])->save(array("isbill" => 1));
	}
	public function Set(){
		$this->v();
	}
	public function SetAdd(){
		$this->v();
	}
}