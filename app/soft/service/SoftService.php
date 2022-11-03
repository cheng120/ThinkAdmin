<?php
namespace app\data\service;

use app\data\model\Soft;
use app\data\model\SoftModel;
use app\soft\model\SoftInfoModel;
use Exception;
use think\admin\Service;
use think\Db;

/**
 * 用户数据管理服务
 * Class SoftService
 * @package app\soft\service
 */
class SoftService extends Service {
    public $stage_name  = [
        1 => "开发",
        2 => "受控",
        3 => "产品",
    ];
    public $status = [
        1 => "正常",
        2 => "撤销",
        3 => "删除",
        4 => "待定",
    ];
    public $info_type = [
        1 => "软件",
        2 => "工具"
    ];
    
    /**
     * 新增软件
     * SetSoft
     * @param  array main_data 软件主体
     * @param array info_data
     * @param  array uinfo  创建人
     * @param bool is_update 是否更新 默认不
     * @return bool 
     * @package app\data\service
     * @throws \think\admin\Exception 
     */
    function SetSoft(array $mainData,array $infoData, array $uinfo,bool $is_update = false):bool{
        $map = ["number"=>$mainData["number"]];
        $soft = SoftModel::mk()->where($map)->where(['delete_at' => null])->findOrEmpty();
        if ($is_update) {
            // 更新
            $mainData['update_at'] = time();
        }else{
            // new
            $mainData['create_at'] = date("Y-m-d H:i:s",time());
            $mainData['update_at'] = date("Y-m-d H:i:s",time());
            $mainData['add_user'] = $uinfo["username"];
            $mainData['add_uid'] = $uinfo["id"];
            $mainData['status'] = 1;
        }
        Db::startTrans();
        try{
            if(!$soft->save($mainData)){
                throw new Exception("更新失败");
            }
            $soft_id = $soft->id;
            // 处理详细信息
            //清空 原详情 保留新的详情
            $infoModel = SoftInfoModel::mk();
            $infoModel->where(["main_id"=>$soft_id])->delete();
            //构造详情数据
            foreach ($infoData as $key => $item) {
                $infoData[$key]["create_at"] = date("Y-m-d H:i:s",time());
                $infoData[$key]["update_at"] = date("Y-m-d H:i:s",time());
                $infoData[$key]["create_uid"] = $uinfo["id"];
                $infoData[$key]["main_id"] = $soft_id;
            }
            $res = $infoModel->where()->savelAll($infoData);
            if(!$res){
                throw new Exception("更新失败");
            }
            Db::commit();
            return true;
        }catch (Exception $e){
            Db::rollback();
            return false;
        }
    }


}