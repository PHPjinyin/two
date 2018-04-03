<?php
namespace Core;
class Framework{
    public static function run(){
        self::initConst();
        self::initConfig();
        self::initRoutes();
        self::initRegisterLoadClass();
        self::initDispatch();
    }
    //定义路径常量,每个路径都以目录分隔符结尾
    private static function initConst(){
        define('DS', DIRECTORY_SEPARATOR);  //定义目录分隔符
        define('ROOT_PATH', getcwd().DS);   //根目录路径
        define('APP_PATH', ROOT_PATH.'Application'.DS); //Application地址
        define('FRAMEWORK_PATH', ROOT_PATH.'Framework'.DS);
        define('CONFIG_PATH', APP_PATH.'Config'.DS);
        define('CONTROLLER_PATH', APP_PATH.'Controller'.DS);
        define('MODEL_PATH', APP_PATH.'Model'.DS);
        define('VIEW_PATH', APP_PATH.'View'.DS);
        define('CORE_PATH', FRAMEWORK_PATH.'Core'.DS);
        define('LIB_PATH', FRAMEWORK_PATH.'Lib'.DS);
    }
    //引入配置文件
    private static function initConfig(){
        $GLOBALS['config']=require CONFIG_PATH.'config.php';
    }
    //确定路由
    private static function initRoutes(){
        $p=isset($_GET['p'])?$_GET['p']:$GLOBALS['config']['app']['default_platform'];    //访问的平台
        $c=isset($_GET['c'])?$_GET['c']:$GLOBALS['config']['app']['default_controller']; //访问的控制器
        $a=isset($_GET['a'])?$_GET['a']:$GLOBALS['config']['app']['default_action'];     //访问的方法
        $p= ucfirst(strtolower($p));   
        $c= ucfirst(strtolower($c));
        $a= ucfirst(strtolower($a));
        define('PLATFORM_NAME', $p);        //定义平台名常量
        define('CONTROLLER_NAME', $c);      //定义控制器名常量
        define('ACTION_NAME', $a);          //定义方法名常量
        define('__URL__', CONTROLLER_PATH.$p.DS);  //当前控制器的目录
        define('__VIEW__', VIEW_PATH.$p.DS);        //当前视图的目录
    }
    //加载类
    private static function LoadClass($class_name) {
        $namespace= dirname($class_name);   //命名空间
        $class_name=basename($class_name);  //类名
        if(in_array($namespace, array('Core','Lib'))){  //加载框架代码
            $path=FRAMEWORK_PATH.$namespace.DS.$class_name.'.class.php';
        }
        elseif($namespace=='Model'){    //表模型路径
            $path=MODEL_PATH.$class_name.'.class.php';
        }
        else{   //控制器路径
            $path=__URL__.$class_name.'.class.php';
        }
        //$path中有路径并且对应的文件存在就加载类文件
        if(isset($path) && file_exists($path)){
            require $path;
        }
    }
    //将方法注册到__autoload函数栈中
    private static function initRegisterLoadClass(){
        spl_autoload_register('self::LoadClass');
    }
    //请求分发
    private static function initDispatch(){
        //获取控制器名和方法名
        $controller_name= '\Controller\\'.PLATFORM_NAME.'\\'.CONTROLLER_NAME.'Controller'; //拼接控制器名
        $action_name=ACTION_NAME.'Action';			//拼接方法名
        //请求分发
        $obj=new $controller_name();
        $obj->$action_name();
    }
}
