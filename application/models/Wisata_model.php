<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wisata_model extends CI_Model {
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/**
	 * create_user function.
	 *
	 * @access public
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function create_kendaraan($params) {
		return $this->db->insert("wisata", $params);
	}
	public function create_photo($params) {
		return $this->db->insert('foto_wisata', $params);
	}

	public function totalKendaraan() {
		$this->db->from("wisata");
		return $this->db->count_all_results();
	}

	public function getLastId(){
		return $this->db->order_by('id_wisata',"desc")->limit(1)->get('wisata')->row();
	}

	public function getPhotos($id_wisata){
		$this->db->where('id_wisata', $id_wisata);
		$query = $this->db->get('foto_wisata');
		return $query->result();
	}

	public function getAll($keyword = null, $id_kategori = null){
    $this->db->order_by('nama_wisata', 'asc');
		$this->db->join('kategori', 'wisata.id_kategori = kategori.id_kategori');
		if($keyword != null){
			$this->db->like('wisata.nama_wisata', $keyword);
		}
		if($id_kategori != null){
			$this->db->where('wisata.id_kategori', $id_kategori);
		}
		$query = $this->db->get('wisata');
		return $query->result();
	}

  // public function get($page = null){
  //   if($page!=null){
  //     $this->db->limit('5', $page);
  //   } else {
  //     if($this->totalKendaraan()>1){
  //         $this->db->limit('5');
  //     }
  //   };
	// 	$this->db->where('deleted_at', '');
  //   $this->db->order_by('merk', 'asc');
	// 	$query = $this->db->get('kendaraan');
	// 	return $query->result();
  // }

	public function getDetail($id){
		$this->db->where('id_wisata', $id);
		$this->db->select('*');
    $this->db->from('wisata');
		return $this->db->get()->row();
  }
}
