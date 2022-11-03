<?php

namespace app\soft\model;

use think\admin\Model;

class BaseModel extends Model{
    /**
     * @param array $data
     * @param array $userInfo
     */
    public function SetBaseData(&$data,$userInfo) {
        $data['create_at'] = date("Y-m-d H:i:s",time());
        $data['update_at'] = date("Y-m-d H:i:s",time());
        $data['create_uid'] = $userInfo["id"];
    }
}