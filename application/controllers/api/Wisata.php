<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/api/Base_api.php');

class Wisata extends Base_api {
  public function __construct() {
    parent::__construct();
    $this->load->helper('url');

    $this->load->model('main_model');
    $this->load->model('wisata_model');
    $this->load->model('kategori_model');
  }

  public function index(){
    $lat = $this->input->post('lat');
    $lng = $this->input->post('lng');
    $keyword = $this->input->post('keyword');
    $id_kategori = $this->input->post('id_kategori');

    $wisata = $this->wisata_model->getAll(($keyword == "" ? null : $keyword), ($id_kategori == "" ? null : $id_kategori));
    $asd['kategori'] = $this->main_model->get('kategori');
    // $asd['wisata'] = $wisata;

    $wisatawithjarak = array();
    foreach ($wisata as $k => $data) {
      $data->jarak = number_format($this->getDistance($data->latitude, $data->longitude, $lat, $lng), 2);
      $data->photos = $this->main_model->get('foto_wisata', array('id_wisata' => $data->id_wisata));
      array_push($wisatawithjarak, $data);
    }

    $asd['wisata'] = $this->bubble_sort($wisatawithjarak);

    if(count($wisata) > 0){
      $data = array(
                  'status'           => "200",
                  'message'           => "Wisata Tersedia",
                  'data'          => $asd);
    } else {
      $data = array(
                  'status'           => "204",
                  'message'           => "Wisata belum tersedia",
                  'data'          => $asd);
    }
    echo json_encode($data);
  }
}
