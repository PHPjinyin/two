<?php
//基础模型
namespace Core;
class Model {
	protected $mypdo;
	public function __construct() {
		$this->initMyPDO();
	}
	//获取mypdo对象
	private function initMyPDO() {
		//获取mypdo对象
		$this->mypdo=MyPDO::getInstance($GLOBALS['config']['database']);
	}
}