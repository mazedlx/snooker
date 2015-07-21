<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scoring extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('teams_model');
		$this->load->model('scoring_model');
	}
	public function index()
	{
		$id_team_1 = $this->session->userdata('id_team_1');
		$id_team_2 = $this->session->userdata('id_team_2');

		if($this->session->userdata('frame')) {
			$data['frame'] = $this->session->userdata('frame');
		} else {
			$data['frame'] = 1;	
			$array = array(
				'frame' => 1
			);				
			$this->session->set_userdata($array);		
		}

		$data['active'] = 'scoring';
		$data['team1'] = $this->teams_model->get_team($id_team_1);
		$data['team2'] = $this->teams_model->get_team($id_team_2);
		$data['score'] = 0;
		$data['id_team_1'] = $id_team_1;
		$data['id_team_2'] = $id_team_2;
		$data['id_match'] = $this->session->userdata('id_match');
		$data['scoring_buttons'] = $this->scoring_model->scoring_buttons();
		$data['foul_buttons'] = $this->scoring_model->foul_buttons();
		$data['count_reds'] = $this->scoring_model->remaining_reds($data['id_match'], $data['frame']);

		$results = $this->scoring_model->result($data['id_match'], $data['frame']);
		$data['result_1'] = $results[$id_team_1] | '0';
		$data['result_2'] = $results[$id_team_2] | '0';

		$data['frames_table'] = $this->scoring_model->frames($data['id_match']); 

		$this->load->view('head', $data, FALSE);
		$this->load->view('scoring', $data, FALSE);
		$this->load->view('foot', $data, FALSE);
	}

}

/* End of file Scoring.php */
/* Location: ./application/controllers/Scoring.php */