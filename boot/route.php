<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/3/26
 * Time: 9:39
 * 路由 检测  定位
 */

class Route{

    public function  checkRoute(){

        $type = @$_POST['type'];
        if(is_null($type) || empty($type))
            EchoError(10002);
        $routeFile = trim( substr($type,0,strpos($type,'/')) );

        //判断type格式
        if(empty($routeFile))
            EchoError(10002);

        $routeSite = ROUTE.$routeFile.'.php';
        //判断是否有文件
        if(!is_file($routeSite))
            EchoError(10002);

        //引入对应的路由文件
        include $routeSite;

        $routeFile2 = trim( substr($type,strpos($type,'/')+1) );
        $_POST['type']  = $type = $routeFile.'/'.$routeFile2;

        $routeArr = @$GLOBALS['ROUTE'][$type];

        //type 为空或者是没有定义过的路由直接返回
        if(is_null($type) || is_null($routeArr)){
            EchoError(10002);
        }

        //检验必填参数是否足够
        $mustAttr = explode(',',$GLOBALS['ROUTE'][$type]['parameter']);

        foreach($mustAttr as $k1 => $v1){
            if(is_null(@$_POST[$mustAttr[$k1]])){
                EchoError(10003);
            }
        }

        //如果请求的是PRI接口必须通过token检验
        if($GLOBALS['ROUTE'][$type]['AccessPermissions']=='PRI'){
            include_once MODEL.'TokenModel.php';
            $TokenModel = new TokenModel();
            //token 检查在这里
            $tokenRes = $TokenModel->checkToken();

            if($tokenRes['code']!=10000){
                echo json_encode($tokenRes);
                die;
            }
        }


        //中间件调用
        if(!is_null(@$GLOBALS['ROUTE'][$type]['middleware'])){
            //查看引用了几个中间件
            $MID = explode(',',$GLOBALS['ROUTE'][$type]['middleware']);

            $objctArr = array();
            //先将中间件加载
            foreach($MID as $key=>$script){
                $RouteSTR = MIDD.$script;
                include $RouteSTR;

                //动态建立对象
                $className = str_replace('.php','',$script);
                $objctArr[$className] = new $className();
            }

            foreach($GLOBALS['ROUTE'][$type]['checkFiled'] as $key=>$value){

                //判断有没有参数
                if(is_null(@$_POST[$key]) ){
                    continue;
                }
                else{
                    //再次进行字段验证
                    $objFun = explode('@',$value['function']);

                    //如果有固定值先判断固定值
                    if($value['value'] && $_POST[$key]!=$value['value']){
                        EchoError(10005);
                    }

                    $checkArr = array(
                        'filed'=>$key,
                        'value'=>$value,
                        'type'=>$type
                    );

                    //第三个参数是该字段的值  将值传递到方法内部
                    call_user_func( array($objctArr[$objFun[1]],$objFun[0]),$checkArr );
                }

            }

        }

        //参数标准合格 初步接受请求
        return array(
            'code'=>'10000',
            'routeArr'=>$GLOBALS['ROUTE'][$type]
        );
    }



}