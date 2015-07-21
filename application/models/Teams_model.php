<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teams_model extends CI_Model {
	public function add()
	{
		if($this->input->post('team_1')) {
			$insert = array(
				'team'	=> $this->input->post('team_1')
			);
			$this->db->insert('team', $insert);
			$id_team_1 = $this->db->insert_id();
			$array = array(
				'id_team_1' 	=> $id_team_1,
				'created_at'	=> date('Y-m-d H:i:s')
			);
			$this->session->set_userdata($array);
		}
		if($this->input->post('team_2')) {
			$insert = array(
				'team'	=> $this->input->post('team_2')
			);
			$this->db->insert('team', $insert);
			$id_team_2 = $this->db->insert_id();
			$array = array(
				'id_team_2' 	=> $id_team_2,
				'created_at'	=> date('Y-m-d H:i:s')
			);
			$this->session->set_userdata($array);
		}
	}

	public function get_all_options() 
	{
		$this->db->select('*');
		$this->db->from('team');
		$this->db->order_by('team', 'asc');
		$query = $this->db->get();
		foreach($query->result() as $row) {
			$id_teams[$row->id] = $row->team;
		}
		return $id_teams;
	}

	public function get_team($id_team)
	{
		$this->db->select('team');
		$this->db->from('team');
		$this->db->where('id', $id_team);
		$query = $this->db->get();
		$row = $query->row();
		return $row->team;
	}
}

/* End of file Teams_model.php */
/* Location: ./application/models/Teams_model.php */