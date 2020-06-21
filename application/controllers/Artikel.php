<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/BaseAdminController.php');

class Artikel extends BaseAdminController {
  public function __construct() {
	parent::__construct();
    $this->load->model('main_model');
    $this->load->model('artikel_model');
		// $this->load->model('user_model');
		// $this->load->model('main_m');
		// $this->is_admin = parent::is_admin();
    $this->load->library('form_validation');
	}

  public function index(){
    $asd['artikel'] = $this->main_model->getWithDeleted('artikel');
    $asd['jumlah'] = $this->artikel_model->totalArtikel();
    parent::getView('m_artikel/daftar_artikel', 'artikel', $asd);
  }

  public function tambah(){
    $asd['form'] = true;
    parent::getView('m_artikel/form_artikel', 'artikel', $asd);
  }

  public function detail($id_artikel){
    $asd['form'] = false;
    $asd['data'] = $this->main_model->getDetail("artikel", ['id_artikel' => $id_artikel, 'deleted_at' => '']);

    parent::getView('m_artikel/form_artikel', 'artikel', $asd);
  }

  public function simpan(){
    $id_artikel = $this->input->post('id');
    $judul_artikel = $this->input->post('judul_artikel');
    $url_artikel = $this->input->post('url_artikel');

    if($this->input->post('action') === 'tambah') {
      $photo_name = $this->artikel_model->totalAllArtikel()."_".str_replace(" ","_", $_FILES['foto_artikel']['name']);
      $data = array(
      	            'judul_artikel' => $judul_artikel,
      	            'url_artikel' => $url_artikel,
      							'tgl_artikel' => date('Y-m-d'),
                    'foto_artikel' => $photo_name
      	        );

      $insert = $this->main_model->create($data, "artikel");
      if($insert){
        $this->session->set_flashdata('alert', array('message' => 'Berhasil menambahkan artikel','class' => 'success'));
      }
    } else {
      $data = array(
          'judul_artikel' => $judul_artikel,
          'url_artikel' => $url_artikel
      );
      if(file_exists($_FILES['foto_artikel']['tmp_name'])){
				$path = $this->input->post('foto_artikel');
				echo $path;
				// delete_files($path);

				// unlink($path);
        $photo_name = $this->artikel_model->totalAllArtikel()."_".str_replace(" ","_", $_FILES['foto_artikel']['name']);
        $data['foto_artikel'] = $photo_name;
      }
      $insert = $this->main_model->update($data, 'artikel', ['id_artikel' => $id_artikel]);
      if($insert){
        $this->session->set_flashdata('alert', array('message' => 'Berhasil mengupdate artikel','class' => 'success'));
      }
    }

    if(file_exists($_FILES['foto_artikel']['tmp_name'])){
      $config['upload_path']          = './datas/artikel';
						$config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG';
						$config['max_size']             = 150000;
						$config['file_name']            = $photo_name;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if ( ! $this->upload->do_upload('foto_artikel') ){
              $error = array('error' => $this->upload->display_errors());
							$this->session->set_flashdata('alert', array('message' => $this->upload->display_errors(),'class' => 'danger'));
              redirect('artikel');
            } else {
	            if($this->input->post('action') === 'tambah') {
								redirect('artikel');
							} else {
								redirect('artikel/detail/'.$id_artikel);
							}
            }
    } else {
			redirect('artikel/detail/'.$id_artikel);
    }
  }

  function delete(){
    $id_artikel = $this->input->get('id');

    $data = array(
      'deleted_at' => date("Y-m-d"),
    );
    $insert = $this->main_model->update($data, 'artikel', ['id_artikel' => $id_artikel]);

    if ($insert) {
      $this->session->set_flashdata('alert', array('message' => 'Berhasil menghapus artikel','class' => 'success'));
      redirect('artikel');
    } else {
      $this->session->set_flashdata('alert', array('message' => 'Gagal menghapus artikel','class' => 'danger'));
      // echo "gagal";
      redirect('artikel');
    }
  }
}
?>
