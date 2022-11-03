<?php


namespace app\soft\controller\soft;

use app\soft\model\SoftModel;
use think\admin\Controller;
use think\facade\View;

/**
 * 软件管理系统
 * class Soft
 * @package app\soft\controller\soft
 */
class Soft extends Controller {


    /**
     * 项目管理
     * @auth true
     * @menu true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $this->title = '软件数据管理';

        $query = SoftModel::mQuery();
        // 加载对应数据
        $this->type = $this->request->get('type', 'index');
        if ($this->type === 'index') $query->where(['delete_at' => 0]);
        elseif ($this->type === 'recycle') $query->where(['delete_at' => 1]);
        else $this->error("无法加载 {$this->type} 数据列表！");

        // 列表排序并显示
        $query->like('code|name#name')->like('marks,cateids', ',');
        $query->equal('status,vip_entry,truck_type,rebate_type')->order('soft_id desc')->page();
    }

    /**
     * 添加软件
     * @auth true
     * @menu true
     */
    public function AddSoft() {

    }



    /**
     * 修改申请
     */

    /**
     * 撤销申请
     */

    /**
     * 查看申请详情
     */

    /**
     * 状态流转
     */
}

 