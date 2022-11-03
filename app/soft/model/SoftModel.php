<?php

namespace app\soft\model;

use think\admin\Model;



/**
 * 用户数据模型
 * Class Soft
 * @package app\soft\model
 */
class SoftModel extends Model {
    protected $name = "soft_main";
    protected $pk = 'soft_id';

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