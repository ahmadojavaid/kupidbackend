<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cms extends CI_Controller{
    function __construct(){
        parent::__construct();
       $this->load->model('Cms_model');
	   $this->load->model('admin_model');
	   $this->load->library(array('session'));
		$this->load->helper(array('url'));
        $this->load->library('form_validation');
        $this->load->helper('form');
    }
    public function index()
	{
        $data['cms']=$this->Cms_model->getAll(array('parent_id'=>0));
		$this->load->view('admin/header');        
		$this->load->view('admin/sidebar');	    
		$this->load->view('admin/cms/cms_list',$data);		
		$this->load->view('admin/footer');
    }
    public function add()
    {
		$data['cms']=$this->Cms_model->getAll();
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
	    $this->load->view('admin/cms/cms_add',$data);
		$this->load->view('admin/footer');
    }
    public function insert()
    {
		$ispost=$this->input->method(TRUE);
        if($ispost=='POST')
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('cmstitle', 'Cms Title', 'required');
			$this->form_validation->set_rules('cmsslug', 'Cms Title Slug', 'required');
			$this->form_validation->set_rules('cmscontent', 'Cms Content', 'required');
			$data=array(
				'title'=>$this->input->post('cmstitle'),
				'slug'=>$this->input->post('cmsslug'),
				'content'=>$this->input->post('cmscontent'),
				'created_date'=>date("Y-m-d H:i:s"),
				'updated_date'=>date("Y-m-d H:i:s"),
			);
			$id=$this->Cms_model->insert($data);
			if($id)
			{
				$this->session->set_flashdata('success',"CMS Successfully Add");
				redirect('/admin/cms');
			}
			else
			{
				$this->session->set_flashdata('fail',"Something Went Wrong Here!");
				redirect('/admin/cms');
			}
		}else{
			$this->session->set_flashdata('fail',"Something Went Wrong Here!");
			redirect('/admin/cms'); 
		} 
    }
	public function edit($id=null)
	{
		$data['edit_cms']=$this->Cms_model->getRecord(array('id'=>$id));
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/cms/cms_edit',$data);
		$this->load->view('admin/footer');
    }
    public function delete($id=null)
	{
		if($this->Cms_model->delete(array('id'=>$id)))
		{
			$this->Cms_model->delete(array('parent_id'=>$id));
			$this->session->set_flashdata('success','CMS data deleted successfully!');
			redirect('/admin/Cms');
		}
		else{
			$this->session->set_flashdata('error','Something Went Wrong!');
			redirect('/admin/Cms');
		}
    }
    public function update()
    {
        $ispost=$this->input->method(TRUE);
        if($ispost=='POST')
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('cmstitle', 'Cms Title', 'required');
			$this->form_validation->set_rules('cmsslug', 'Cms Title Slug', 'required');
			$this->form_validation->set_rules('cmscontent', 'Cms Content', 'required');
			$data=array(
				'title'=>$this->input->post('cmstitle'),
				'slug'=>$this->input->post('cmsslug'),
				'content'=>$this->input->post('cmscontent'),
				'created_date'=>date("Y-m-d H:i:s"),
				'updated_date'=>date("Y-m-d H:i:s"),
			);
			$parent_id=$this->input->post('id');
			$id=$this->Cms_model->update(array('id'=>$parent_id),$data);
			if($id)
			{
				$this->session->set_flashdata('success',"CMS Successfully Updated");
				redirect('/admin/cms');
			}
			else
			{
				$this->session->set_flashdata('fail',"Something Went Wrong Here!");
				redirect('/admin/cms');
			}
        }   
    }
	public function display_cms($alias_name)
	{
		$data['cms']=$this->Cms_model->getRecord(array('slug'=>$alias_name));
		if(!empty($data['cms']))
		{
			 $value['header']=$this->admin_model->get_setting();				
			$this->load->view('header',$value);
			$this->load->view('user/dashboard/cms_display',$data);
			$this->load->view('footer');
		}
	}
}
?>