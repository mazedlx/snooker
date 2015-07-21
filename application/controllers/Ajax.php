<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public function score()
	{
		$id_match = $this->input->post('id_match');
		$id_team = $this->input->post('id_team');
		$frame = $this->input->post('frame');

		$this->db->select('SUM(score) AS sum_score');
		$this->db->from('score');
		$this->db->where('id_match', $id_match);
		$this->db->where('id_team', $id_team);
		$this->db->where('frame', $frame);
		$query = $this->db->get();
		$row = $query->row();
		$score = $row->sum_score;
		if(!$score) $score = 0;
		echo $score;	
	}

	public function save_score()
	{
		$id_match = $this->input->post('id_match');
		$id_team = $this->input->post('id_team');
		$frame = $this->input->post('frame');
		$score = $this->input->post('score');
		$break_text = $this->input->post('break_text');
		$break_text = substr($break_text, 1);
		$free_ball = $this->input->post('free_ball');

		$insert = array(
			'id_team'		=> $id_team,
			'id_match'		=> $id_match,
			'frame'			=> $frame,
			'score'			=> $score,
			'break'			=> $break_text,
			'created_at'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('score', $insert);
		$id_score = $this->db->insert_id();

		if($free_ball) {
			$insert = array(
				'id_match'	=> $id_match,
				'frame'		=> $frame,
				'id_score'	=> $id_score,
				'free_ball'	=> $free_ball
			);
			$this->db->insert('free_ball', $insert);
		}
	}

	public function save_score_and_foul()
	{
		$id_match = $this->input->post('id_match');
		$id_team = $this->input->post('id_team');
		$frame = $this->input->post('frame');
		$score = $this->input->post('score');
		$foul = $this->input->post('foul');
		$id_team_1 = $this->input->post('id_team_1');
		$id_team_2 = $this->input->post('id_team_2');


		$break_text = $this->input->post('break_text');
		$break_text = substr($break_text, 1);

		$insert = array(
			'id_team'		=> $id_team,
			'id_match'		=> $id_match,
			'frame'			=> $frame,
			'score'			=> $score,
			'break'			=> $break_text,
			'created_at'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('score', $insert);

		if($id_team == $id_team_1) {
			$foul_team = $id_team_2;
		} else {
			$foul_team = $id_team_1;
		}

		$insert = array(
			'id_team'		=> $foul_team,
			'id_match'		=> $id_match,
			'frame'			=> $frame,
			'score'			=> $foul,
			'break'			=> 'foul',
			'created_at'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('score', $insert);
	}

	public function end_match()
	{
		$frame = $this->session->userdata('frame')+1;
		$array = array(
			'frame' => $frame
		);
		$this->session->set_userdata($array);
	}

	public function logoff()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function result() 
	{
		$this->load->model('scoring_model');

		$id_match = $this->input->post('id_match');
		$id_team_1 = $this->input->post('id_team_1');
		$id_team_2 = $this->input->post('id_team_2');
		$frame = $this->input->post('frame');
		$results = $this->scoring_model->result($id_match, $frame);
		if($results[$id_team_1] == null) {
			$results[$id_team_1] = 0;
		}
		if($results[$id_team_2] == null) {
			$results[$id_team_2] = 0;
		}
		echo $results[$id_team_1].' - '.$results[$id_team_2];
	}
}

/* End of file Ajax.php */
/* Location: ./application/controllers/Ajax.php */