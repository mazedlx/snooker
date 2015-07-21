<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('teams_model');
		$this->load->model('match_model');
	}

	public function index()
	{
		$data['active'] = 'match';

		$data['id_teams'] = $this->teams_model->get_all_options();

		$data['id_team_1'] = $this->session->userdata('id_team_1');
		$data['id_team_2'] = $this->session->userdata('id_team_2');

		$this->load->view('head', $data, FALSE);
		$this->load->view('match', $data, FALSE);
		$this->load->view('foot', $data, FALSE);		
	}

	public function add()
	{
		$this->match_model->add();
		$this->session->set_flashdata('msg', 'Match angelegt');
		redirect('scoring');
	}
}

/* End of file Match.php */
/* Location: ./application/controllers/Match.php */