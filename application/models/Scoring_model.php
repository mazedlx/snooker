<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scoring_model extends CI_Model {
	public function score()
	{
		$id_match = $this->session->userdata('id_match');
		$id_team = $this->session->userdata('id_team_1');
		$frame = $this->session->userdata('frame');

		$this->db->select('SUM(score) AS sum_score');
		$this->db->from('score');
		$this->db->where('id_match', $id_match);
		$this->db->where('id_team', $id_team);
		$this->db->where('frame', $frame);
		$query = $this->db->get();

		$row = $query->row();
		$score = $row->sum_score;

		if(!$score) $score = 0;
		return $score;
	}

	public function scoring_buttons()
	{
		$remaining_reds = $this->remaining_reds($this->session->userdata('id_match'), $this->session->userdata('frame'));

		$this->db->select('*');
		$this->db->from('score_type');
		$this->db->where('score_type_short !=','freeball');
		$this->db->where('score_type_short NOT LIKE "%foul%"');
		if($remaining_reds < 1) {
			$this->db->where('score_type_short != ', 'red');
		}
		$this->db->order_by('value', 'asc');
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$buttons[] = array(
				'id'	=> $row->id,
				'name'	=> $row->score_type,
				'short'	=> $row->score_type_short,
				'value'	=> $row->value
			);
		}
		return $buttons;
	}

	public function foul_buttons()
	{
		$this->db->select('*');
		$this->db->from('score_type');
		$this->db->where('score_type_short !=','freeball');
		$this->db->where('score_type_short LIKE "%foul%"');
		$this->db->order_by('value', 'asc');
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$buttons[] = array(
				'id'	=> $row->id,
				'name'	=> $row->score_type,
				'short'	=> $row->score_type_short,
				'value'	=> $row->value
			);
		}
		return $buttons;
	}

	public function remaining_reds($id_match, $frame)
	{
		$reds = 0;
		$this->db->select('break');
		$this->db->from('score');
		$this->db->where('id_match', $id_match);
		$this->db->where('frame', $frame);
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$break = $row->break;
			$arr = explode(',', $break);
			for($i = 0; $i < count($arr); $i++) {
				if($arr[$i] == 'red') {
					$reds+= 1;
				}
			}
		}
		return 15-$reds;
	}

	public function result($id_match, $frame)
	{
		$this->db->select('SUM(s.score) AS sum_score, s.id_team');
		$this->db->from('score s');
		$this->db->where('s.id_match', $id_match);
		$this->db->where('s.frame', $frame);
		$this->db->group_by('s.id_team');
		$query = $this->db->get();

		foreach($query->result() as $row) {
			if($row->sum_score) {
				$sum_score = $row->sum_score;
			} else {
				$sum_score = 0;
			}
			$results[$row->id_team] = $sum_score;
		}
		return $results;
	}

	public function frames($id_match) {
		$this->load->model('teams_model');
		$this->load->library('table');

		$template = array(
			'table_open' => '<table class="table">'
		);
		$this->table->set_template($template);

		$this->db->select('id_team_1, id_team_2');
		$this->db->from('match');
		$this->db->where('id', $id_match);
		$query = $this->db->get();
		$row = $query->row();
		$id_team_1 = $row->id_team_1;
		$id_team_2 = $row->id_team_2;

		$this->table->set_heading(
			array(
				'Frame',	
				$this->teams_model->get_team($id_team_1),
				$this->teams_model->get_team($id_team_2)
			)
		);		

		$this->db->select('SUM(score) AS sum_score, id_team, frame');
		$this->db->from('score');
		$this->db->where('id_match', $id_match);
		$this->db->group_by('id_team');
		$this->db->group_by('frame');
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$frames[$row->frame][$row->id_team] = $row->sum_score;
		}
		foreach($frames as $id => $frame) {
			$tabledata[] = array(
				$id,
				$frame[$id_team_1],
				$frame[$id_team_2]
			);
		}
		$table = $this->table->generate($tabledata);
		return $table;
	}
}

/* End of file Scoring_model.php */
/* Location: ./application/models/Scoring_model.php */