<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/BaseAdminController.php');

class Admin extends BaseAdminController {
  public function __construct() {
	parent::__construct();
		$this->load->model('users_model');
		$this->load->model('main_model');
		// $this->is_admin = parent::is_admin();
	}

  public function index(){
    parent::getView('admin/dashboard', 'dashboard');
  }

  public function ubahpassword(){
    parent::getView('m_profile/profile', 'dashboard');
  }


  public function ubah(){
    $username = $this->input->post('username');
    $password_lama = $this->input->post('password_lama');
    $password_baru = $this->input->post('password_baru');
    $password_baru_ulangi = $this->input->post('password_baru_ulangi');

    if($password_baru_ulangi == $password_baru){
      if ($this->users_model->check_login_admin($username, $password_lama)) {
        $p_baru = $this->users_model->hash_password($password_baru);
        $datas = array(
          'password' => $p_baru
        );
        $update = $this->main_model->update($datas, 'admin', ['username' => $username]);
        if($update){
          $this->session->set_flashdata('alert', array('message' => 'Berhasil mengubah password','class' => 'success'));
        } else {
          $this->session->set_flashdata('alert', array('message' => 'Ada yang bermasalah dengan server','class' => 'info'));
        }
      } else {
        $this->session->set_flashdata('alert', array('message' => 'Password Anda salah','class' => 'danger'));
      }
    } else {
      $this->session->set_flashdata('alert', array('message' => 'Password baru Anda belum sesuai, coba ulangi lagi','class' => 'warning'));
    }

    redirect('admin/ubahpassword');
  }



  public function logout(){
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
      // remove session datas
      foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]);
      }
      redirect('/');

    } else {
      redirect('/');
    }
  }

}
?>
