<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/api/Base_api.php');

class Pernyataan extends Base_api {
  public function __construct() {
    parent::__construct();
    $this->load->helper('url');

    $this->load->model('main_model');
    $this->load->model('pernyataan_model');
  }

  public function index(){
    $result = array();
    $result["data"] = array();
    $indikators = $this->pernyataan_model->getAspekIndikator();
    if($indikators){
      $result["result"] = "success";
      foreach ($indikators as $k => $ind) {
        $aspek = $ind;
        $pernyataan = array();
        // echo $this->pernyataan_model->getSamplePernyataan($ind->id_indikator);
        foreach ($this->pernyataan_model->getPernyataan($ind->id_indikator) as $k => $pyt) {
          $pyt->nilai = $this->pernyataan_model->getNilai($pyt->id_jenis_pernyataan);
          array_push($pernyataan, $pyt);
        }
        $aspek->pernyataan = $pernyataan;
        array_push($result["data"], $aspek);
      }
    }
    echo json_encode($result);
  }
}
