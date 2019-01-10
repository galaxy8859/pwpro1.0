<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/6/7
 * Time: 9:45
 */
/*查找sys里面的配置信息
 * @param1 文件 system下的文件直接输入文件名
 * @param2 索引
 * @param3 模式  默认是直接输出
 * 可优化进redis 在热备的时候就进行初始化
 *
 *  */
function GetSystem($file,$index){

    $getFile = @file_get_contents(SYSTEM.$file.'.conf',"r");
    $regExp  = '/'.$index.'.*/i';
    $pos = preg_match($regExp,$getFile,$arr);

    //没有找到提示错误
    if(!$pos)
        echoError(20000,$file.'.conf配置文件中没有'.$index.'此配置,请联系管理员');

    $site = strpos($arr[0],':');
    $info = trim( substr($arr[0],$site+1) );

    //返回参数
        return $info;

}

//错误输出
function EchoError($code,$info=false){

    //系统错误 20000不查询 否者会导致死循环
    if($code==20000){
        $returnInfo = $info==false?'系统信息错误,请联系管理员':$info;
    }

    //文件找错误对应的信息
    else{
        $returnInfo =$info==false?GetSystem('error',$code):$info;
    }


    echo json_encode(array(
        'code'=>$code,
        'errorInfo'=>$returnInfo
    ));

    die;
}

//数据输出 正确情况下的输出
function EchoData($data){

    echo json_encode(
        array(
            'code'=>10000,
            'content'=>$data
        )
    );

    die;
}











