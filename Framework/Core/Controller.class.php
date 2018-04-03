<?php
/*
 * 基础控制器
 */
namespace Core;
abstract class Controller{
    //正确的跳转
    public function success($url,$info='',$time=1){
        $this->jump($url, $info, $time, true);
    }
    //错误的跳转
    public function error($url,$info='',$time=3){
        $this->jump($url, $info, $time, false);
    }
    /*
     * 跳转的方法
     * $url string 跳转的地址
     * $info string 提示信息
     * $time int 停留时间
     * $success bool 成功|失败后跳转
     */
    private function jump($url,$info='',$time=1,$success=true){
         //如果没有信息显示就直接跳转
        if($info==''){ 
            header("location:$url");
            exit;
        }
        //有信息显示
        if($success){
            $flag='true';
        }else
            $flag='error';
        echo <<<str
       <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="{$time};url={$url}"/>
<title>无标题文档</title>
<style type="text/css">
img{
	display:block;
	margin:100px auto 20px;
}
body{
	font-size:24px;
	font-family:'黑体';
	text-align:center;
}
#true{
	color:#060;
}
#error{
	color:#F00;
}
</style>
</head>
<body>
<img src="Public/images/{$flag}.fw.png"/>
<div id="{$flag}">{$info},3秒以后跳转</div>
<script type="text/javascript">
/*
setTimeout(function(){
	location.href='index.php?p=Admin&c=Products&a=list';	
},3000);
*/
</script>
</body>
</html>
str;
exit;
    }
}
