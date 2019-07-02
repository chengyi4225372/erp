<?php
namespace MyClass;
class User extends \MyClass\Common{
    //意向客户
	public function Index(){
		$p = e('Page',$this->m('e_user')->count(),50);
		$list = $this->m('e_user')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}

    public function Edit(){
        $info = $this->m('e_user')->find($_GET['id']);
        $this->s('info',$info)->v();
    }

    public function  add(){
	     $this->v();
    }

    public function Edit_do(){
	    $id = $_POST['id'];
        $data['user_id']=$_POST['user_id'];
        $data['names']=$_POST['names'];
        $data['cates_id']=$_POST['cates_id'];
      if(!empty($id)){
         $res =  $this->m('e_user')->where('id = '.$id)->save($data);
          if($res){
              $this->ajax('200','编辑成功！');
          }else{
              $this->ajax('400','编辑失败！');
          }
      }else{
          $data['create_time']=time();
          $res =   $this->m('e_user')->add($data);
          if($res){
              $this->ajax('200','添加成功！');
          }else{
              $this->ajax('400','添加失败！');
          }
      }

    }


    //todo 没有使用
	public function Login(){
		$p = e('Page',$this->m('user_login')->count());
		$list = $this->m('user_login l')->join('user u on u.id = l.uid')->group('l.id')->order('time DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}
	public function Vip(){
		$p = e('Page',10000,50);
		$list = $this->m('h_user_person u')->field("u.*,a.amount")->join('h_account a on a.uid = u.user_id')->where('state = 1')->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}

	public function EditReg(){
		$info = $this->m('h_user_person')->find($_GET['id']);
		$this->s('info',$info)->v();
	}
	public function EditDo(){
		$data['state'] = 1;
		$this->m('h_user_person')->where('id = ' . $_POST[id])->save($data);
		$this->success('已成功办理注册业务',t('User/Index'));
	}
	public function EditDo1(){
		$data['state'] = 1;
		$this->m('h_user_company')->where('id = ' . $_POST[id])->save($data);
		$this->success('已成功办理注册业务',t('User/Index'));
	}
	public function Cert1(){
		$w['state'] = 1;
		$p = e('Page',$this->m('h_user_company')->where($w)->count(),50);
		$list = $this->m('h_user_company u')->field("u.*,a.amount")->join('h_account a on a.uid = u.user_id')->where($w)->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}
	public function Cert(){
		$w['state'] = 0;
		$p = e('Page',$this->m('h_user_company')->where($w)->count(),50);
		$list = $this->m('h_user_company')->where($w)->limit($p->firstRow.','.$p->listRows)->select();
		$this->s('list',$list)->s('page',$p->show())->v();
	}
	public function Pay(){
		$w['uid'] = $_POST['uid'];
		$mount = $this->m("h_account")->where($w)->find();
		if($mount){
			$this->m("h_account")->where($w)->inc("amount",$_POST['amount']);
		}else{
			$this->m("h_account")->add($_POST);
		}
		$this->AddInvest();
	}
	public function AddInvest(){
		$data = array(
			'price' => $_POST['amount'],
			'uid'	=> $_POST['uid']
		);
		$this->m("h_invest_log")->add($data);
	}
}