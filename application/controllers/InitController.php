<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InitController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');

		if (!$this->session->userdata('userId')) {
			redirect('AppController/LoginView');
			
		    exit;
		}
	}

	public function index()
	{
		$data['mgroups'] = $this->UserModel->FindMGroupBySession();
        $data['menus'] = $this->UserModel->FindMenuBySession();

		$this->load->view('layout/Header');
		$this->load->view('layout/Sidebar', $data);
		$this->load->view('init/Index');
		$this->load->view('layout/Footer');
		$this->load->view('layout/JSCore');
		$this->load->view('layout/SidebarScript');
	}
}
