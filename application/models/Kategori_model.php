<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function create($params) {
		return $this->db->insert('kategori', $params);
	}

  public function get(){
		$query = $this->db->get('kategori');
		return $query->result();
  }

}
