<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match_model extends CI_Model {
	public function add()
	{
		$insert = array(
			'id_team_1'		=> $this->input->post('id_team_1'),
			'id_team_2' 	=> $this->input->post('id_team_2'),
			'created_at'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('match', $insert);
		$id_match = $this->db->insert_id();
		$array = array(
			'id_match' => $id_match
		);
		$this->session->set_userdata($array);

		$array = array(
			'id_team_1' => $this->input->post('id_team_1'),
			'id_team_2' => $this->input->post('id_team_2')
		);
		
		$this->session->set_userdata( $array );
	}
}

/* End of file Match_model.php */
/* Location: ./application/models/Match_model.php */