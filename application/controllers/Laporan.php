<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/BaseController.php');
require 'vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

class Laporan extends BaseController {
	public function __construct() {
        parent::__construct();
        $this->load->helper('url');
				$this->load->model('laporan_model');

				$this->load->library('session');
		    $this->load->model('main_model');
		    $this->load->model('survey_model');
		    $this->load->model('users_model');


	}

	public function index() {
		// $model = $this->laporan_model->yearList();
		// foreach ($model as $k => $booking) {
		// 	$model2 = $this->laporan_model->monthinYearlist($booking->month);
		// 	foreach ($model2 as $y => $booking2) {
		// 		echo $booking2->month."\n";
		// 	}
		// }

		$model = $this->laporan_model->yearList();
		parent::getView('m_laporan/laporan_tahunan', 'laporan', $model);
	}

	public function laporanbiodata(){
		$id_user = $this->input->get('data');
		$user = $this->users_model->get_user_by_id($id_user);
		// parent::getView('m_laporan/laporan_biodata', 'laporan', ['data' => $user]);
		$html = $this->load->view('m_laporan/laporan_biodata', ['data' => $user], true);

		$html2pdf = new Html2Pdf();
		$html2pdf->writeHTML($html);
		$html2pdf->output();

		// $this->html2pdf->html('<h1>Some Title</h1><p>Some content in here</p>');
		// $this->html2pdf->create('save');
	}

	public function laporansurvey($id_user){
		$data = array();
		// $user = json_decode($this->input->get('data'));
		// $id_user = $this->input->get('id_user');
		// $id_user = base64_decode($iduser);
		$data['user'] = $this->users_model->get_user_by_id($id_user);
		$data['user']->age = parent::getAge($data['user']->tgl_lahir);


    $survey = $this->survey_model->laporanSurveySaya($id_user);
		foreach ($survey as $k => $model) {
			$realdate = parent::parseTanggal(explode(" ", $model->tanggal_survey)[0]);
			$survey[$k]->realdate = $realdate;
			$task_pertanyaan = array();
			$task_pertanyaan = $this->survey_model->getTaskPertanyaanSurvey($survey[$k]->id_survey);
			if($task_pertanyaan){
				$survey[$k]->istaskpertanyaan = true;
				$survey[$k]->taskpertanyaan = $task_pertanyaan;
			} else {
				$survey[$k]->istaskpertanyaan = false;
			}

		}
		$data['survey'] = $survey;
		//
    // if($surveySaya){
    //   foreach ($surveySaya as $k => $survey) {
    //     $survey->nilai = $this->survey_model->getNilaiPertanyaanBySurvey($survey->id_survey);
    //     array_push($data["grafik"], $survey);
    //   }
    // }
		// parent::getView('m_laporan/laporan_survey', 'laporan', ['data' => $data]);

		$html = $this->load->view('m_laporan/laporan_survey', ['data' => $data], true);

		$html2pdf = new Html2Pdf();
		$html2pdf->writeHTML($html);
		$html2pdf->output();

		// $html = $this->load->view('m_laporan/laporan_survey', ['data' => $data]);
	}

	public function detaillaporansurvey($id_survey){
		$data = array();
		// $user = json_decode($this->input->get('data'));
		// $id_user = $this->input->get('id_user');
		// $id_user = base64_decode($iduser);

		$survey = $this->survey_model->getDetailSurveyById($id_survey);
		 $realdate = parent::parseTanggal(explode(" ", $survey->tanggal_survey)[0]);
		$data['survey'] = $survey;

		$detailSurveySaya = $this->survey_model->detailSurveySaya($id_survey);
		$data['detailsurvey'] = $detailSurveySaya;

		$task_pertanyaan = $this->survey_model->getTaskPertanyaanSurvey($id_survey);
		if($task_pertanyaan){
      $data["istaskpertanyaan"] = true;
      $data["taskpertanyaan"] = $task_pertanyaan;
    } else {
      $data["istaskpertanyaan"] = false;
    }

		$data['user'] = $this->users_model->get_user_by_id($survey->id_user);
		$data['user']->age = parent::getAge($data['user']->tgl_lahir);

		$html = $this->load->view('m_laporan/laporan_detail_survey', ['data' => $data], true);

		$html2pdf = new Html2Pdf();
		$html2pdf->writeHTML($html);
		$html2pdf->output();
	}

	public function detail_laporan_tahunan(){
		$year = $this->input->get('tahun');
		$model = $this->laporan_model->monthinYearlist($year);
		$result = array();
		$result['tahun'] = $year;
		$result['tahunan'] = array();
		foreach ($model as $k => $bulan) :
			$datas = $this->laporan_model->listLaporan($year, $bulan->bulan);
			$datak = array();
			$datak['bulan'] = $bulan;
			$datak['datas'] = $datas;
			array_push($result['tahunan'], $datak);

		endforeach;

		$html = $this->load->view('m_laporan/detiltahunan', ['data' => $result], true);
    $filename = 'report_'.time();
    $this->pdfgenerator->generate($html, $filename, true, 'A4', 'portrait');
		// parent::getView('m_laporan/detiltahunan', 'laporan', $result);
	}

	public function detail_laporan_bulanan(){
		$year = $this->input->get('tahun');
		$bulan = $this->input->get('bulan');
		$datas = $this->laporan_model->listLaporan($year, $bulan);

		$result = array();
		$result['tahun'] = $year;
		$result['bulan'] = $this->laporan_model->getmonth($bulan);
		$result['datas'] = $datas;

		$html = $this->load->view('m_laporan/detailbulanan', ['data' => $result], true);
    $filename = 'report_'.time();
    $this->pdfgenerator->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function bulanan(){
		$result = array();
		$model = $this->laporan_model->yearList();
		foreach ($model as $k => $tahun) :
			$datas = $this->laporan_model->monthlist($tahun->tahun);
			$datak = array();
			$datak['tahun'] = $tahun->tahun;
			$datak['datas'] = $datas;
			array_push($result, $datak);
		endforeach;
		parent::getView('m_laporan/laporan_bulanan', 'laporan', $result);
	}

	public function sample() {
		$model = $this->laporan_model->listLaporan('2019');
		foreach ($model as $k => $booking) {
			echo $booking->nama_lengkap."\n";
		}
	}

	public function detail($id){
		$data['users']=array(
			array('firstname'=>'I am','lastname'=>'Programmer','email'=>'iam@programmer.com'),
			array('firstname'=>'I am','lastname'=>'Designer','email'=>'iam@designer.com'),
			array('firstname'=>'I am','lastname'=>'User','email'=>'iam@user.com'),
			array('firstname'=>'I am','lastname'=>'Quality Assurance','email'=>'iam@qualityassurance.com')
		);
    $html = $this->load->view('table_report', $data, true);
    $filename = 'report_'.time();
    $this->pdfgenerator->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function konfirmasi(){
		$id_booking = $this->input->get('id');
		$status = $this->input->get('status');

		if($status == '1'){
			$params = array('confirmed' => 'Y');
		} else {
			$params = array('deleted_at' => date('Y-m-d H:i:s'));
		}

		$insert = $this->main_model->update($params, 'booking', ['id_booking' => $id_booking]);
		if ($insert) {
			$this->session->set_flashdata('alert', array('message' => 'Berhasil menghapus foto','class' => 'success'));
			redirect('booking');
		} else {
			$this->session->set_flashdata('alert', array('message' => 'Gagal menghapus foto','class' => 'danger'));
			redirect('booking');
		}
	}

// SELECT DISTINCT EXTRACT(MONTH FROM begin_date) FROM booking

// SELECT kode_booking FROM booking WHERE EXTRACT(YEAR FROM begin_date) = 2018

}
