<?php
//通过单例模式实现MyPDO类
namespace Core;
class MyPDO{
    private static $instance;   //保存当前类的实例
    private $type;  //数据库类型
    private $host;  //主机地址
    private $port;  //端口号
    private $dbname;    //数据库名称
    private $charset;   //字符编码
    private $user;      //用户名
    private $pwd;       //密码
    private $pdo;       //PDO对象
    /*
     * 私有的构造函数用来阻止在类的外部实例化
     */
    private function __construct($param){
        $this->initParam($param);   //初始化参数
        $this->initPDO();           //实例化PDO
        $this->initException();     //设置异常模式
    }
    /*
     * 私有的__clone()用来阻止在类的外部clone对象
     */
    private function __clone(){
    }
    /*
     * 公有的静态方法用来获取当前类的单例
     * @param array 初始化参数
     */
    public static function getInstance($param=array()){
        if(!self::$instance instanceof self)
            self::$instance=new self($param);
        return self::$instance;
    }
    /*
     * 初始化参数
     */
    private function initParam($param){
        $this->type=isset($param['type'])?$param['type']:'mysql';
        $this->host=isset($param['host'])?$param['host']:'localhost';
        $this->port=isset($param['port'])?$param['port']:'3306';
        $this->dbname=isset($param['dbname'])?$param['dbname']:'';
        $this->charset=isset($param['charset'])?$param['charset']:'utf8';
        $this->user=isset($param['user'])?$param['user']:'';
        $this->pwd=isset($param['pwd'])?$param['pwd']:'';
    }
    /*
     * 显示异常信息
     * @param $e Exception 异常对象
     * @param $sql string SQL语句
     */
    private function showException($e,$sql=''){
        if($sql!=''){
            echo 'SQL语句执行失败<br>';
            echo '错误的SQL语句是：',$sql,'<br>';
        }
        echo '错误编号：',$e->getCode(),'<br>';
        echo '错误行号：',$e->getLine(),'<br>';
        echo '错误文件：',$e->getFile(),'<br>';
        echo '错误信息：',iconv('gbk','utf-8',$e->getMessage()),'<br>';
        exit;
    }
    /*
     * 实例化PDO对象
     */
    private function initPDO(){
        try{
            $dsn="{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            $this->pdo=new \PDO($dsn,$this->user,  $this->pwd);
        }catch(PDOException $e){
            $this->showException($e);
        }
    }
    /**
    *设置异常模式,抛出异常
    */
    private function initException() {
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    /*
     * 执行数据操作语句
     * @param $sql string 数据操作语句
     * @return int 返回受影响的记录数
     */ 
    public function exec($sql){
        try{
            return $this->pdo->exec($sql);
        } catch (Exception $e) {
            $this->showException($e, $sql);
        }
    }
    /*
     * 获取自动增长的编号
     */
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
    /*
     * 获取匹配常量
     * @param $type string 匹配字符串
     * @return int 返回匹配常量
     */
    private function getFetchConst($type){
        switch($type){
            case 'assoc':
                return \PDO::FETCH_ASSOC;
            case 'num':
                return \PDO::FETCH_NUM;
            case 'both':
                return \PDO::FETCH_BOTH;
            default:
                return \PDO::FETCH_ASSOC;
        }
    }
    /*
     * 匹配所有记录
     * @param $sql string 数据查询语句
     * @param $type string 匹配类型 assoc|num|both
     * @return array 二维数组
     */
    public function fetchAll($sql,$type='assoc'){
        try{
            $stmt=$this->pdo->query($sql);
            $fetch_const=  $this->getFetchConst($type);
            return $stmt->fetchAll($fetch_const);
        } catch (Exception $e) {
            $this->showException($e);
        }
    }
    /*
     * 匹配一条记录
     */
    public function fetchRow($sql,$type='assoc'){
        try{
            $stmt=$this->pdo->query($sql);
            $fetch_const=  $this->getFetchConst($type);
            return $stmt->fetch($fetch_const);
        } catch (Exception $e) {
            $this->showException($e);
        }
    }
    /*
     * 获取一行一列的数据
     */
    public function fetchColumn($sql){
        try{
            $stmt=$this->pdo->query($sql);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            $this->showException($e);
        }
        
    }
}
