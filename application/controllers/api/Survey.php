<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'controllers/api/Base_api.php');
require_once(APPPATH.'controllers/util/helper.php');

class Survey extends Base_api {
  public function __construct() {
    parent::__construct();
    $this->load->helper('url');

    $this->load->model('main_model');
    $this->load->model('survey_model');
    $this->load->model('pernyataan_model');
  }

  public function index(){
    $data = json_decode($this->input->post('data'));
    echo json_encode($result);
  }

  public function makeSurvey(){
    $data = json_decode($this->input->post('data'));
    $result = array();
    $datak = array(
                'id_user'           => $data->id_user,
                'tanggal_survey'           => date('Y-m-d H:i:s')
                );
     if($this->survey_model->addSurvey($datak)){
       $result["result"] = "success";
       $result["message"] = "Berhasil membuat survey";
       $result["data"] = $this->survey_model->getLastSurvey($data->id_user);
     } else {
       $result["result"] = "failed";
       $result["message"] = "Ada yang bermasalah dengan server";
     }
    echo json_encode($result);
  }

  public function check(){
    $data = json_decode($this->input->post('data'));
    $jumlahSurvey = $this->survey_model->checkSurvey($data->id_user);

    $result = array();
    if($jumlahSurvey == 0){
      $result["result"] = "new";
      $result["task"] = array();
    } else {
      $result["task"] = array();
      if($jumlahSurvey == 1){
        if($this->isSurveyDone($data->id_user)){
          $result["result"] = "first";
        } else {
          $result["result"] = "survey";
          $result["data"] = $this->survey_model->getLastSurvey($data->id_user);
          $result["intervensi"] = $this->survey_model->getTaskPertanyaanSurvey($result["data"]->id_survey);
        }
      } else if($jumlahSurvey > 1){
        if($this->isSurveyDone($data->id_user)){
          $result["result"] = "second";
        } else {
          $result["result"] = "survey";
          $result["data"] = $this->survey_model->getLastSurvey($data->id_user);
          $result["intervensi"] = $this->survey_model->getTaskPertanyaanSurvey($result["data"]->id_survey);
        }
      }
    }

    // $result["nilai"] = $this->survey_model->totalNilaiIntervensi($data->id_user);
    echo json_encode($result);
  }

  public function submitPertanyaan(){
    // include_once '../util/helper.php';
    $helper = new Helper();
    $data = json_decode($this->input->post('data'));
    $pernyataan = json_decode($this->input->post('pernyataan'));
    $result = array();
    $pertanyaan = array(
                'id_survey'           => $data->id_survey,
                'id_pernyataan'           => $pernyataan->id_pernyataan,
                'nilai_pertanyaan'           => $data->nilai_pertanyaan,
                'nama_nilai_pertanyaan'           => $data->nama_nilai_pertanyaan
                );
    $process = $this->survey_model->addPertanyaanSurvey($pertanyaan);

    if($process){
      $result["indikator"] = false;
      $result["aspek"] = false;
      $result["survey"] = false;

      // jika Jumlah pertanyaan == jumlah indikator
      if($this->survey_model->isPertanyaanAsPernyataanIndikator($data->id_survey, $pernyataan->id_indikator)){
        $result["indikator"] = true;

        // $countPernyataanByIndikator = ($this->survey_model->getCountPernyataanByIndikator($pernyataan->id_indikator)*4)/2;
        $nilaiPertanyaanByIndikator = $this->survey_model->getNilaiPertanyaanByIndikator($data->id_survey, $pernyataan->id_indikator);

        // TODO : UNDONE 06/11/2019 11:54
        if($helper->isNeedTask($nilaiPertanyaanByIndikator, $pernyataan->indikator_median)){
          $last_pertanyaan = $this->survey_model->getLastPertanyaan($data->id_survey);
          $task_pertanyaan = array(
                      'id_pertanyaan_survey'           => $last_pertanyaan->id_pertanyaan_survey,
                      'tanggal_task'           => $this->survey_model->getLastDateIntervensi($data->id_survey),
                      'status_task'           => 'N'
                      );
          $this->main_model->create($task_pertanyaan, "task_pertanyaan");
          $result["intervensi"] = $this->survey_model->getLastIntervensi($data->id_survey);
        }
      }

      // jika Jumlah pertanyaan == jumlah aspek
      if($this->survey_model->isPertanyaanAsPernyataanAspek($data->id_survey, $pernyataan->id_aspek)){
        $result["aspek"] = true;

        $countPernyataanBySubAspek = $this->survey_model->getCountPernyataanByAspek($pernyataan->id_aspek);
        $countPertanyaanBySubAspek = $this->survey_model->getCountPertanyaanByAspek($data->id_survey, $pernyataan->id_aspek);
        $nilaiPertanyaanBySubAspek = $this->survey_model->getNilaiPertanyaanByAspek($data->id_survey, $pernyataan->id_aspek);

        if($helper->klasifikasiKomentar($pernyataan, $nilaiPertanyaanBySubAspek) == "plus"){
          $result["data_aspek"] = $this->pernyataan_model->getAspekByAspek($pernyataan->id_aspek)->plus_comment;
          $result["data_aspek_indicator"] = "plus";
        } else if($helper->klasifikasiKomentar($pernyataan, $nilaiPertanyaanBySubAspek) == "minus"){
          $result["data_aspek"] = $this->pernyataan_model->getAspekByAspek($pernyataan->id_aspek)->negative_comment;
          $result["data_aspek_indicator"] = "minus";
        }
      }

      // TODO : DONE 06/11/2019 11:54
      // jika Jumlah pertanyaan == keseluruhan aspek
      if($this->survey_model->isPertanyaanAsPernyataanAll($data->id_survey)){
        $result["survey"] = true;
        $aspek = $this->pernyataan_model->getAspek();
        foreach ($aspek as $k => $asp) {
          $nilaiPertanyaanByAspek = $this->survey_model->getNilaiPertanyaanByAspek($data->id_survey, $asp->id_aspek);
          // echo $this->survey_model->getSampleKlasifikasiScoreByScore($asp->id_aspek, $nilaiPertanyaanByAspek);
          $score_identitas_survey = array(
                      'id_survey'           => $data->id_survey,
                      'id_klasifikasi_score_identitas'           => $this->survey_model->getKlasifikasiScoreByScore($asp->id_aspek, $nilaiPertanyaanByAspek)->id_klasifikasi_score_identitas,
                      'score'           => $nilaiPertanyaanByAspek);

          $this->main_model->create($score_identitas_survey, "score_identitas_survey");
        }

        $data_score_identitas_survey = $this->survey_model->getScoreIdentitasSurvey($data->id_survey);

        // echo $this->survey_model->hitungStatusIdentitasReligiusSample($data_score_identitas_survey);
        $hitungStatusIdentitasReligius = $this->survey_model->hitungStatusIdentitasReligius($data_score_identitas_survey);
        if($hitungStatusIdentitasReligius){
          if($this->survey_model->updateScoreSurvey($hitungStatusIdentitasReligius->id_status_identitas_religius, $data->id_survey)){
            $result["data_survey"] = $hitungStatusIdentitasReligius->deskripsi_status;
          }
        } else {
          if($this->survey_model->updateScoreSurvey("6", $data->id_survey)){
            $result["data_survey"] = "Tidak ada status";
          }
        }
      }

      $result["result"] = "success";
    } else {
      $result["result"] = "failed";
    }
    echo json_encode($result);
  }

  public function test(){
    // echo strtotime('+1 day', date('Y-m-d H:i:s'));
    // $date = strtotime("+7 day", '2019-06-02 23:42:29');
    // // echo date('2019-06-02 23:42:29', strtotime('+1 day'));
		// echo date('Y-m-d H:i:s', $date);
		// echo date('Y-m-d H:i:s', strtotime('+1 day'));
    // $timestamp = time()-86400;
    $timestamp = strtotime('2019-06-02 23:42:29');

    echo $timestamp;
    $date = strtotime("+1 day", $timestamp);
    echo date('Y-m-d H:i:s', $date);
	}

  public function getAllTaskPertanyaan(){
    echo json_encode($this->main_model->get("task_pertanyaan"));
  }

  public function updateTaskPertanyaan(){
    $result = array();

    $data = json_decode($this->input->post('data'));
    $user = json_decode($this->input->post('user'));
    $data->tanggal_submit = date('Y-m-d H:i:s');

    $jumlahSurvey = $this->survey_model->checkSurvey($user->id_user);

    if($jumlahSurvey == 1){
      $datak = array(
                  'id_task_pertanyaan'          => $data->id_task_pertanyaan,
                  'id_pertanyaan_survey'        => $data->id_pertanyaan_survey,
                  'tanggal_task'                => $data->tanggal_task,
                  'status_task'                 => $data->status_task,
                  'tanggal_submit'              => $data->tanggal_submit,
                  'komentar_pertanyaan'         => $data->komentar_pertanyaan,
                  'nilai'                       => '10');
    } else {
      $datak = array(
                  'id_task_pertanyaan'          => $data->id_task_pertanyaan,
                  'id_pertanyaan_survey'        => $data->id_pertanyaan_survey,
                  'tanggal_task'                => $data->tanggal_task,
                  'status_task'                 => $data->status_task,
                  'tanggal_submit'            => $data->tanggal_submit,
                  'komentar_pertanyaan'           => $data->komentar_pertanyaan);
    }

    $insert = $this->main_model->update($datak, 'task_pertanyaan', ['id_task_pertanyaan' => $data->id_task_pertanyaan]);
    $result["done"] = false;
    if($insert){
      $result["result"] = "success";
      $result["data"] = $this->survey_model->getTaskPertanyaan($data->id_task_pertanyaan);
      if($this->isSurveyComplete($user->id_user)){
        $result["done"] = true;
      }
    } else {
      $result["result"] = "failed";
      $result["data"]  = new stdClass();
    }

    echo json_encode($result);
  }

  public function updateNewTaskPertanyaan(){
    $result = array();

    $data = json_decode($this->input->post('data'));
    $user = json_decode($this->input->post('user'));
    $data->tanggal_submit = date('Y-m-d H:i:s');

    $jumlahSurvey = $this->survey_model->checkSurvey($user->id_user);

    if($jumlahSurvey == 1){
      $datak = array(
                  'id_task_pertanyaan'          => $data->id_task_pertanyaan,
                  'id_pertanyaan_survey'        => $data->id_pertanyaan_survey,
                  'tanggal_task'                => $data->tanggal_task,
                  'status_task'                 => $data->status_task,
                  'tanggal_submit'              => $data->tanggal_submit,
                  'komentar_pertanyaan'         => $data->komentar_pertanyaan,
                  'keterangan'                  => $data->keterangan,
                  'nilai'                       => '10');
    } else {
      $datak = array(
                  'id_task_pertanyaan'          => $data->id_task_pertanyaan,
                  'id_pertanyaan_survey'        => $data->id_pertanyaan_survey,
                  'tanggal_task'                => $data->tanggal_task,
                  'status_task'                 => $data->status_task,
                  'tanggal_submit'            => $data->tanggal_submit,
                  'komentar_pertanyaan'           => $data->komentar_pertanyaan,
                  'keterangan'                  => $data->keterangan);
    }

    $insert = $this->main_model->update($datak, 'task_pertanyaan', ['id_task_pertanyaan' => $data->id_task_pertanyaan]);
    $result["done"] = false;
    if($insert){
      $result["result"] = "success";
      $result["data"] = $this->survey_model->getTaskPertanyaan($data->id_task_pertanyaan);
      if($this->isSurveyComplete($user->id_user)){
        $result["done"] = true;
      }
    } else {
      $result["result"] = "failed";
      $result["data"]  = new stdClass();
    }

    echo json_encode($result);
  }

  public function getSurveySaya(){
    $helper = new Helper();
    $result = array();
    $data = json_decode($this->input->post('data'));
    $surveySaya = $this->survey_model->surveySaya($data->id_user);
    foreach ($surveySaya as $k => $surveyq) {
      $total_nilai = 0;
      $aspeks = $this->pernyataan_model->getAspek();
      $surveySaya[$k]->komentar_aspek = array();
      foreach ($aspeks as $z => $aspek) {
        $nilaiPertanyaanByAspek = $this->survey_model->getNilaiPertanyaanByAspek($surveyq->id_survey, $aspek->id_aspek);
        $total_nilai += $nilaiPertanyaanByAspek;
        if($helper->klasifikasiKomentar($aspek, $nilaiPertanyaanByAspek) == "plus"){
          array_push($surveySaya[$k]->komentar_aspek, $this->pernyataan_model->getAspekByAspek($aspek->id_aspek)->plus_comment);
        } else {
          array_push($surveySaya[$k]->komentar_aspek, $this->pernyataan_model->getAspekByAspek($aspek->id_aspek)->negative_comment);
        }
      }
      $surveySaya[$k]->nilai = $total_nilai;
    }
    // echo $this->survey_model->isTaskCompletedBySurveySample($survey->id_survey);
    if($surveySaya){
      $result["result"] = "success";
      $result["data"] = $surveySaya;
    } else {
      $result["result"] = "failed";
    }
    echo json_encode($result);
  }

  public function getDetailSurveySaya(){
    $result = array();
    $data = json_decode($this->input->post('data'));
    $detailSurveySaya = $this->survey_model->detailSurveySaya($data->id_survey);
    // echo $this->survey_model->isTaskCompletedBySurveySample($survey->id_survey);
    if($detailSurveySaya){
      $result["result"] = "success";
      $result["data"] = $detailSurveySaya;
    } else {
      $result["result"] = "failed";
    }
    echo json_encode($result);
  }

  public function getPertanyaan(){
    $result = array();
    $data = json_decode($this->input->post('data'));
    $task_pertanyaan = $this->survey_model->getTaskPertanyaanSurvey($data->id_survey);
    // echo $this->survey_model->isTaskCompletedBySurveySample($survey->id_survey);
    if($task_pertanyaan){
      $result["result"] = "success";
      $result["data"] = $task_pertanyaan;
    } else {
      $result["result"] = "failed";
    }
    echo json_encode($result);
  }

  private function isSurveyComplete($id_user){
    $survey = $this->survey_model->getLastSurvey($id_user);
    // echo $this->survey_model->isTaskCompletedBySurveySample($survey->id_survey);
    if($this->survey_model->isTaskPassedBySurvey($survey->id_survey)){
      return false;
    } else {
      return true;
    }
  }

  private function isSurveyDone($id_user){
    $survey = $this->survey_model->getLastSurvey($id_user);
    // echo $this->survey_model->isTaskCompletedBySurveySample($survey->id_survey);
    if($this->survey_model->isTaskCompletedBySurvey($survey->id_survey)){
      return false;
    } else {
      return true;
    }
  }

  // 22 Juni 2019

  public function checkIntervensiToday(){
    $result = array();
    $data = json_decode($this->input->post('data'));
    // $isTask = $this->survey_model->checkIntervensiToday($data->id_user);
    // echo $this->survey_model->isTaskCompletedBySurveySample($survey->id_survey);
    // if($isTask){
    //   $result["result"] = "success";
    //   $result["data"] = $isTask;
    // } else {
    //   $result["result"] = "failed";
    //   $result["data"] = $this->survey_model->checkIntervensiTodaySample($data->id_user);
    // }

$jumlahSurvey = $this->survey_model->checkSurvey($data->id_user);

    if($jumlahSurvey == 0){
        $result["result"] = "success";
    } else {
        $result["result"] = "failed";
    }
    echo json_encode($result);
  }
}
