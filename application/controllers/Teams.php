<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teams extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('teams_model');
	}

	public function index()
	{
		$data['active'] = 'teams';
		$this->load->view('head', $data, FALSE);
		$this->load->view('teams', $data, FALSE);
		$this->load->view('foot', $data, FALSE);
	}

	public function add()
	{
		$this->teams_model->add(); 
		$this->session->set_flashdata('msg', 'Teams angelegt');
		redirect('match');
	}
}

/* End of file Teams.php */
/* Location: ./application/controllers/Teams.php */