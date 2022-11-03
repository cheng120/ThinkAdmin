<?php


namespace app\soft\controller\soft;

use app\soft\controller\Base;
use app\soft\model\BaseModel;
use app\soft\model\ProjectModel;
use app\soft\model\SoftModel;
use app\soft\model\SubjectModel;
use Illuminate\Http\Request as HttpRequest;
use Prpcrypt;
use think\admin\Controller;
use think\facade\View;
use think\Request;

/**
 * 软件管理系统
 * class Soft
 * @package app\soft\controller\soft
 */
class Subject extends Base {
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
        $this->title = '软件产品管理';

        $query = SubjectModel::mQuery();
        $query->alias('s')->join('soft_project p','s.project_id=p.id')->field("s.name as s_name,s.create_at as sc_time,p.*,s.id as sid");
        // 加载对应数据
        $this->type = $this->request->get('type', 'index');
        if ($this->type === 'index') $query->where('s.delete_at is null');
        elseif ($this->type === 'recycle') $query->where("s.delete_at not null");
        else $this->error("无法加载 {$this->type} 数据列表！");
        $query->like('p.name#name');
        $query->like('charge_name#charge_name');
        $query->like('version#version');
        $query->like('s.name#s_name');
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
        $this->title = '添加产品数据';
        // 组装数据
        $model = new SubjectModel();
        $this->_form($model,"form",);
        // ProjectModel::mForm("form");
    }


    /**
     * 表单数据处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function _form_filter(array &$data)
    {
        if ($this->request->isGet()) {
            // 其他表单数据
            $this->projectList = ProjectModel::getProjectList();
        } elseif ($this->request->isPost()) {
            $model = new SubjectModel();
            if($this->mode != "edit"){
                $model->SetBaseData($data,$this->userinfo);
            }else{
                $data['update_at'] = date("Y-m-d H:i:s");
            }
        }
    }

     /**
     * 修改申请
     * @auth true
     */
    public function edit()
    {
        $this->mode = 'edit';
        $this->title = '编辑项目数据';
        $model = new SubjectModel();
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
            $this->success('产品编辑成功！', 'javascript:history.back()');
        }
    }

     /**
     * 撤销申请
     * @auth true
     */
    public function remove()
    {
        SubjectModel::mSave([], 'id');
    }
}

 