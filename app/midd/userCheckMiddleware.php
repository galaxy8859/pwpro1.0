<?php
/**
 * Created by PhpStorm.
 * User: Dev02
 * Date: 2018/3/27
 * Time: 19:28
 * 中间件
 * 该中间件主要检查查询的用户是否属于该登陆者账号
 */

class userCheckMiddleware{

    private $UserCheckDB;
    public function __construct()
    {
        //连接数据库
        $DBConnect = new Database();
        //指定单独的表
        $this->UserCheckDB = $DBConnect->returnConnectDB();
    }

    public function start()
    {
        return $this->checkUser();
    }

    private function checkUser(){
        include_once MODEL.'TransModel.php';
        $TransModel = new TransModel();

        $account = $TransModel->getBelongAccount();
        $accountS = '';
        foreach($account['account'] as $k=>$v){

            $accountS .= "'".$v."'".',';

        }

        $accountS =  trim($accountS,',');
        $account['account'] = $accountS;

        //判断这个用户是否属于他们旗下
        $sql = "select * from user where id='{$_POST['user_id']}' AND channelid in ({$account['account'] })";

        $sqlRes = $this->UserCheckDB->query($sql)->fetch();

        if(!$sqlRes){
            return array(
                'code'=>10008,
                'errInfo'=>'未在您代理的用户中找到该用户'
            );
        }
        else{
            return array(
                'code'=>10000,
                'user'=>$sqlRes
            );
        }
    }


}
