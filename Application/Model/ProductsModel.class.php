<?php
/**
*products表模型，用来操作Products表
规则：表名+Model
*/
namespace Model;
class ProductsModel extends \Core\Model {
	//获取products表的数据
	public function getList() {
		return $this->mypdo->fetchAll('select * from products');
	}
	//删除商品
	public function del($id) {
		return $this->mypdo->exec('delete from products where proid='.$id);
	}
}