<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/api/Base_api.php');

class Artikel extends Base_api {
  public function __construct() {
    parent::__construct();
    $this->load->helper('url');

    $this->load->model('main_model');
    $this->load->model('artikel_model');
  }

  public function index(){
    $asd = $this->artikel_model->getAll();

    if($asd){
      $data = array(
                  'status'           => "200",
                  'message'           => "Wisata Tersedia",
                  'data'          => $asd);
    } else {
      $data = array(
                  'status'           => "204",
                  'message'           => "Artikel belum tersedia",
                  'data'          => $asd);
    }
    echo json_encode($data);
  }
}
