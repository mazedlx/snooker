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
			'table_open' => '<table class="table table-condensed table-bordered">'
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
				'Highest Break',
				$this->teams_model->get_team($id_team_2),
				'Highest Break'
			)
		);		

		$this->db->select('SUM(score) AS sum_score, id_team, frame');
		$this->db->from('score');
		$this->db->where('id_match', $id_match);
		$this->db->group_by('id_team');
		$this->db->group_by('frame');
		$this->db->order_by('frame', 'asc');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$frames[$row->frame][$row->id_team] = $row->sum_score;
			}
			foreach($frames as $id => $frame) {
				if(!$frame[$id_team_1]) $frame[$id_team_1] = 0;
				if(!$frame[$id_team_2]) $frame[$id_team_2] = 0;
				$tabledata[] = array(
					$id,
					$frame[$id_team_1],
					$this->highest_break($id_match, $id, $id_team_1),
					$frame[$id_team_2],
					$this->highest_break($id_match, $id, $id_team_2)
				);
			}
		} else {
			$tabledata[] = array(
				array(
					'data' 		=> 'Noch keine Frames gespielt',
					'colspan'	=> 5
				)
			);
		}
		$table = $this->table->generate($tabledata);
		return $table;
	}

	public function highest_break($id_match, $frame, $id_team)
	{
		$this->db->select('id, break');
		$this->db->from('score');
		$this->db->where('id_match', $id_match);
		$this->db->where('frame', $frame);
		$this->db->where('id_team', $id_team);
		$this->db->where('break !=', 'foul');
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$break = explode(',', $row->break);
			foreach($break as $key => $color) {
				$this->db->select('value');
				$this->db->from('score_type');
				$this->db->where('score_type_short', $color);
				$query_b = $this->db->get();
				$row_b = $query_b->row();

				$break_scores[$row->id]+= $row_b->value;
			}
		}
		rsort($break_scores);
		$highest_break = $break_scores[0];
		if(!$highest_break) $highest_break = 0;
		return $highest_break;
	}

	public function get_all_matches()
	{
		$this->load->model('teams_model');
		$this->load->library('table');
		$template = array(
			'table_open'	=> '<table class="table table-bordered">'
		);
		$this->table->set_template($template);
		$this->table->set_heading(array(
			'Datum',
			'Team 1',
			'Punkte',
			'Team 2',
			'Punkte'
		));
		$this->db->select('*');
		$this->db->from('match');
		$this->db->order_by('created_at', 'desc');
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$this->db->select('SUM(score) AS sum_score, id_team, frame');
			$this->db->from('score');
			$this->db->where('id_match', $row->id);
			$this->db->group_by('id_match');
			$this->db->group_by('id_team');
			$this->db->group_by('frame');
			$query_s = $this->db->get();
			foreach($query_s->result() as $row_s) {
				$scores[$row_s->id_team][$row_s->frame] = $row_s->sum_score;
			}

			$this->db->select('MAX(frame) AS max_frame');
			$this->db->from('score');
			$this->db->where('id_match', $row->id);
			$query_f = $this->db->get();
			$row_f = $query_f->row();
			$max_frame = $row_f->max_frame;
			for($i = 1; $i <= $max_frame; $i++) {
				$tabledata[] = array(
					date('d.m.Y', strtotime($row->created_at)),
					$this->teams_model->get_team($row->id_team_1),
					$scores[$row->id_team_1][$i],
					$this->teams_model->get_team($row->id_team_2),
					$scores[$row->id_team_2][$i]
				);
			}
		}

		$table = $this->table->generate($tabledata);
		return $table;
	}
}

/* End of file Scoring_model.php */
/* Location: ./application/models/Scoring_model.php */