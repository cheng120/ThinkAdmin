<?php

namespace app\soft\model;

use think\admin\Model;



/**
 * 软件项目模型
 * Class Soft
 * @package app\soft\model
 */
class ProjectModel extends BaseModel {
    protected $name = "soft_project";

    /**
     * 新增项目数据
     * @param array $data
     * @param array userinfo
     * @return array ["flag"=>bool,"msg"=>string]
     */
    public function CreateData(array $data, array $userinfo) : array
    {
        // 拼装数据
        $this->SetBaseData($data,$userinfo);
        $res = ProjectModel::mk()->save($data);
        if($res){
            return ["flag"=>true,"msg"=>""];
        }else{
            return ["flag"=>false,"msg"=>"失败"];
        }
    }


    public static function getProjectList(){
        return ProjectModel::mk()->where("delete_at is  null")->where("status = 1")->select()->toArray();
    }

    /**
     * 格式化创建时间
     * @param string $value
     * @return string
     */
    public function getCreateAtAttr(string $value): string
    {
        return format_datetime($value);
    }
}