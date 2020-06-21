<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/BaseAdminController.php');

class Wisata extends BaseAdminController {
	public function __construct() {
        parent::__construct();
        $this->load->helper('url');
				// $this->load->model('users_model');
				// load form helper and validation library
				$this->load->helper('form');
				$this->load->library('session');
				$this->load->library('form_validation');
		    $this->load->model('wisata_model');
		    $this->load->model('main_model');
		    $this->load->model('kategori_model');
        $this->load->helper('form');
				$this->load->library('form_validation');
	}

	public function index() {
		$wisata = $this->wisata_model->getAll();
    $asd['wisata'] = $wisata;
    parent::getView('m_wisata/listwisata', 'wisata', $asd);
	}

	public function detail($id){
		$data['type'] = "edit";
		$data['wisata'] = $this->wisata_model->getDetail($id);
		$data['kategori'] = $this->main_model->get('kategori');
		$data['foto'] = $this->wisata_model->getPhotos($id);
		$data['form'] = false;
		parent::getView('m_wisata/formwisata', 'wisata', $data);
	}
	public function tambah(){
    $data['type'] = "tambah";
		$data['kategori'] = $this->main_model->get('kategori');
		$data['form'] = true;
		parent::getView('m_wisata/formwisata', 'wisata', $data);
	}

	public function simpan(){
		$nama_wisata = $this->input->post('nama_wisata');
		$id_kategori = $this->input->post('id_kategori');
		$latitude = $this->input->post('latitude');
		$longitude = $this->input->post('longitude');
		$deskripsi = $this->input->post('deskripsi');

        $data = array(
            'nama_wisata' => $nama_wisata,
            'id_kategori' => $id_kategori,
            'latitude' => $latitude,
            'deskripsi' => $deskripsi,
            'longitude' => $longitude
        );

				if($this->input->post('action') === 'tambah') {
					$insert = $this->main_model->create($data, "wisata");
					if ($insert) {
						$this->session->set_flashdata('alert', array('message' => 'Berhasil menambah Wisata','class' => 'success'));
						redirect('wisata/detail/'.$this->wisata_model->getLastId()->id_wisata);
					}
					else {
						$this->session->set_flashdata('alert', array('message' => 'Gagal menambah Wisata','class' => 'danger'));
						redirect('wisata');
					}
				} else if ( $this->input->post('action') === 'edit' ) {
					$id_wisata = $this->input->post('id_wisata');

					$insert = $this->main_model->update($data, 'wisata', ['id_wisata' => $id_wisata]);
					if ($insert) {
						$this->session->set_flashdata('alert', array('message' => 'Berhasil mengedit Wisata','class' => 'success'));
						redirect('wisata/detail/'.$id_wisata);
					}
					else {
						$this->session->set_flashdata('alert', array('message' => 'Gagal mengedit Wisata','class' => 'danger'));
						redirect('wisata/detail/'.$id_wisata);
					}
				}
	}

	public function simpanphoto(){
		$id_wisata = $this->input->post('id_wisata');
		$photo_name = $id_wisata."_".str_replace(" ","_", $_FILES['photo']['name']);

		$data = array(
			'id_wisata' => $id_wisata,
			'url_foto' => $photo_name,
		);

		$config['upload_path']          = './datas/wisata';
		$config['allowed_types']        = 'jpg|gif|png|jpeg|JPG|PNG';
		$config['max_size']             = 150000;
		$config['file_name']            = $photo_name;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('photo') )
		{
			$error = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('alert', array('message' => $this->upload->display_errors(),'class' => 'danger'));
			redirect('wisata/detail/'.$id_wisata);
		} else {
			$insert = $this->wisata_model->create_photo($data);
			if ($insert) {
				$this->session->set_flashdata('alert', array('message' => 'Berhasil menambah foto wisata','class' => 'success'));
				redirect('wisata/detail/'.$id_wisata);
			}
			else {
				$this->session->set_flashdata('alert', array('message' => 'Gagal menambah foto wisata','class' => 'danger'));
				redirect('wisata/detail/'.$id_kendaraan);
			}
		}
	}

	function deletefoto()
    {
			$id_wisata = $this->input->get('id_wisata');
			$id = $this->input->get('id');

        $insert = $this->main_model->deletedata('foto_wisata', ['id_foto_wisata' => $id]);

        if ($insert) {
					$this->session->set_flashdata('alert', array('message' => 'Berhasil menghapus foto wisata','class' => 'success'));
					redirect('wisata/detail/'.$id_wisata);
        } else {
					$this->session->set_flashdata('alert', array('message' => 'Gagal menghapus foto','class' => 'danger'));
					redirect('wisata/detail/'.$id_wisata);
        }
    }

		function delete(){
			$id_wisata = $this->input->get('id');
			$insert = $this->main_model->deletedata('wisata', ['id_wisata' => $id_wisata]);
			if ($insert) {
				$this->session->set_flashdata('alert', array('message' => 'Berhasil menghapus wisata','class' => 'success'));
				redirect('wisata');
			} else {
				$this->session->set_flashdata('alert', array('message' => 'Gagal menghapus wisata','class' => 'danger'));
				echo "gagal";
				redirect('wisata');
			}
		}
}
