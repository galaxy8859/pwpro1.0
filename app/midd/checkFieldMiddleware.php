<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/3/28
 * Time: 9:48
 * 检查用户携带的字段是否符合类型标准
 */

class checkFieldMiddleware{

    public function __construct()
    {

    }

    //手机检测 参数就是要验证的号码
    public function mobileCheck($value){

        $mobile = $_POST[$value['filed']];

        $mobileRegExt = "/^1[3456789]{1}[0-9]{9}$/i";

        $pos = preg_match($mobileRegExt,$mobile);

        if($pos!=1)
            EchoError(10006);
    }

    //包含检测  只在固定值中挑选
    public function insideCheck($value)
    {
        echo '实现包含检测';die;
    }

    //范围检测
    public function betweenCheck($value)
    {
        $betVal = $_POST[$value['filed']];
        $pos = is_numeric($betVal);
        if(!$pos)
            EchoError(10007);

        if($betVal<$value['value']['betweenCheck'][0] || $betVal>$value['value']['betweenCheck'][1] )
            EchoError(10008,$value['filed'].'的值超出了设定的范围');
    }

    //登陆密码检测 密码检测默认是字母加数字
    public function password($value)
    {
        $pass = $_POST[$value['filed']];
        if(strlen($pass)<6){
            //如果是登陆接口错误返回的是登陆密码错误
            $routeSite = substr($value['type'],strpos($value['type'],'/' )+1);
            $code = $routeSite=='login'?10009:10010;
            EchoError($code);
        }

    }

}