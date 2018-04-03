<?php
/**
*商品控制器
规则：模块名+Controller
*/
namespace Controller\Admin;
class ProductsController extends \Core\Controller {
	//显示商品
	public function listAction() {
            $model=new \Model\ProductsModel();
            $list=$model->getList();
            require  __VIEW__.'products_list.html';	//引入视图
	}
	//删除商品
	public function delAction() {
            $id=$_GET['id'];	//需要删除的id号
            $model=new \Model\ProductsModel();	//实例化模型
            if($model->del($id))
                $this->success('index.php?p=Admin&c=Products&a=list','删除成功');
            else
                $this->error ('index.php?p=Admin&c=Products&a=list', '删除失败');            
	}
}
