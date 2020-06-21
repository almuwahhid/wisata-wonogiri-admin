<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/BaseAdminController.php');

class Kategori extends BaseAdminController {
  public function __construct() {
	parent::__construct();
    $this->load->model('main_model');
    $this->load->library('form_validation');
	}

  public function index(){
    $asd['kategori'] = $this->main_model->get('kategori');
    parent::getView('m_kategori/listkategori', 'kategori', $asd);
  }

  public function tambah(){
    $asd['form'] = true;
    parent::getView('m_kategori/formkategori', 'kategori', $asd);
  }

  public function detail($id_kategori){
    $asd['form'] = false;
    $asd['kategori'] = $this->main_model->getDetail("kategori", ['id_kategori' => $id_kategori]);

    parent::getView('m_kategori/formkategori', 'kategori', $asd);
  }

  public function simpan(){
    $id_kategori = $this->input->post('id');
    $nama_kategori = $this->input->post('nama_kategori');

    if($this->input->post('action') === 'tambah') {
      // $photo_name = $this->artikel_model->totalAllArtikel()."_".str_replace(" ","_", $_FILES['foto_kategori']['name']);
      $photo_name = $_FILES['foto_kategori']['name'];
      $data = array(
      	            'nama_kategori' => $nama_kategori,
      	            'foto_kategori' => $photo_name
      	        );

      $insert = $this->main_model->create($data, "kategori");
      if($insert){
        $this->session->set_flashdata('alert', array('message' => 'Berhasil menambahkan kategori','class' => 'success'));
      }
    } else {
      $data = array(
          'nama_kategori' => $nama_kategori
      );
      if(file_exists($_FILES['foto_kategori']['tmp_name'])){
				$path = $this->input->post('foto_kategori');
				// echo $path;
				// delete_files($path);

				// unlink($path);
        $photo_name = $_FILES['foto_kategori']['name'];
        $data['foto_kategori'] = $photo_name;
      }
      $insert = $this->main_model->update($data, 'kategori', ['id_kategori' => $id_kategori]);
      if($insert){
        $this->session->set_flashdata('alert', array('message' => 'Berhasil mengupdate kategori','class' => 'success'));
      }
    }

    if(file_exists($_FILES['foto_kategori']['tmp_name'])){
      $config['upload_path']          = './datas/kategori';
						$config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG';
						$config['max_size']             = 150000;
						$config['file_name']            = $photo_name;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if ( ! $this->upload->do_upload('foto_kategori') ){
              $error = array('error' => $this->upload->display_errors());
							$this->session->set_flashdata('alert', array('message' => $this->upload->display_errors(),'class' => 'danger'));
              redirect('kategori');
            } else {
	            if($this->input->post('action') === 'tambah') {
								redirect('kategori');
							} else {
								redirect('kategori/detail/'.$id_kategori);
							}
            }
    } else {
			redirect('kategori/detail/'.$id_kategori);
    }
  }

  function delete(){
    $id_kategori = $this->input->get('id');

    $data = array(
      'deleted_at' => date("Y-m-d"),
    );
    $insert = $this->main_model->deletedata('kategori', ['id_kategori' => $id_kategori]);

    if ($insert) {
      $this->session->set_flashdata('alert', array('message' => 'Berhasil menghapus kategori','class' => 'success'));
      redirect('kategori');
    } else {
      $this->session->set_flashdata('alert', array('message' => 'Gagal menghapus artkategoriikel','class' => 'danger'));
      // echo "gagal";
      redirect('kategori');
    }
  }
}
?>
