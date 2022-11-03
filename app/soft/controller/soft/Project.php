<?php


namespace app\soft\controller\soft;

use app\soft\controller\Base;
use app\soft\model\ProjectModel;
use app\soft\model\SoftModel;
use Illuminate\Http\Request as HttpRequest;
use think\admin\Controller;
use think\facade\View;
use think\Request;

/**
 * 软件管理系统
 * class Soft
 * @package app\soft\controller\soft
 */
class Project extends Base {
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
        $this->title = '软件项目管理';

        $query = ProjectModel::mQuery();
        // 加载对应数据
        $this->type = $this->request->get('type', 'index');
        if ($this->type === 'index') $query->where('delete_at is null');
        elseif ($this->type === 'recycle') $query->where("delete_at not null");
        else $this->error("无法加载 {$this->type} 数据列表！");
        $query->like('name#name');
        $query->like('charge_name#charge_name');
        $query->like('version#version');
        // 列表排序并显示
        $query->order('id desc')->page();
    }

    /**
     * 添加软件
     * @auth true
     * @menu true
     */
    public function add(Request $request) {
        $this->mode = 'add';
        $this->title = '添加项目数据';
        // 组装数据
        $model = new ProjectModel();
        $this->_form($model,"form",);
        // ProjectModel::mForm("form");
    }


    protected function _form_filter(&$data) {
        $model = new ProjectModel();
        $model->SetBaseData($data,$this->userinfo);
    }


     /**
     * 修改申请
     * @auth true
     */
    public function edit()
    {
        $this->mode = 'edit';
        $this->title = '编辑项目数据';
        $model = new ProjectModel();
        $this->_form($model,"form",);
        // ProjectModel::mForm('form', 'id');
    }

    /**
     * 表单结果处理
     * @param boolean $result
     */
    protected function _form_result(bool $result,array $data)
    {   
        // 这里可以获取到数据记录ID    
        //  echo $data['id']
        if ($result && $this->request->isPost()) {
            $this->success('项目编辑成功！', 'javascript:history.back()');
        }
    }

     /**
     * 撤销申请
     * @auth true
     */
    public function remove()
    {
        ProjectModel::mSave([], 'id');
    }
}

 