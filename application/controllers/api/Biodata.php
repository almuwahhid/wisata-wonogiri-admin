<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/api/Base_api.php');
define('UPLOAD_DIR', 'profile/');

class Biodata extends Base_api {
  public function __construct() {
    parent::__construct();
    $this->load->helper('url');

    $this->load->model('users_model');
    $this->load->model('main_model');
  }

  public function index(){
    $result = array();
    $result["data"] = array();
    $data = json_decode($this->input->post('data'));
    $img = $this->input->post('foto');

    if($img != ""){
      $img = str_replace('data:image/png;base64,', '', $img);
      $img = str_replace(' ', '+', $img);
      $data_img = base64_decode($img);
      $file = UPLOAD_DIR . $data->id_user . '.png';
      $success = file_put_contents($file, $data_img);
      if($success){
        $params = array('nama' => $data->nama,
                        'jenis_kelamin' => $data->jenis_kelamin,
                        'tgl_lahir' => $data->tgl_lahir,
                        'program_studi' => $data->program_studi,
                        'telp' => $data->telp,
                        'semester' => $data->semester,
                        'universitas' => $data->universitas,
                        'pekerjaan_impian' => $data->pekerjaan_impian,
                        'photo_profil' => $data->id_user . '.png');
        $update = $this->main_model->update($params, 'user', ['id_user' => $data->id_user]);
      } else {
        $update = $this->main_model->update($data, 'user', ['id_user' => $data->id_user]);
      }
    } else {
      $update = $this->main_model->update($data, 'user', ['id_user' => $data->id_user]);
    }

    if($update){
      $result["result"] = "success";
      $result["data"] = $this->users_model->get_user($data->email);

    } else {
      $result["result"] = "failed";
    }
    echo json_encode($result);
  }

  public function editpassword(){
    $dataq = json_decode($this->input->post('data'));
    $password = $this->input->post('password');

    if ($this->users_model->check_login_user($dataq->email, $dataq->password)) {
      $p_baru = $this->users_model->hash_password($password);

      $datas = array(
        'password' => $p_baru
      );
      $update = $this->main_model->update($datas, 'user', ['id_user' => $dataq->id_user]);
      if($update){
        $data = array(
                    'status'           => "200",
                    'message'           => "Berhasil mengubah password",
                    'data'          => new stdClass());
      } else {
        $data = array(
                    'status'           => "404",
                    'message'           => "Ada yang bermasalah dengan server",
                    'data'          => new stdClass());
      }
    } else {
      $data = array(
                  'status'           => "204",
                  'message'           => "Password Anda salah",
                  'data'          => new stdClass());
    }

    echo json_encode($data);
  }
}
