<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('scoring_model');
	}

	public function index()
	{
		$data['table'] = $this->scoring_model->get_all_matches();
		$data['active'] = 'stats';
		$this->load->view('head', $data, FALSE);
		$this->load->view('stats', $data, FALSE);
		$this->load->view('foot', $data, FALSE);
	}

}

/* End of file Stats.php */
/* Location: ./application/controllers/Stats.php */