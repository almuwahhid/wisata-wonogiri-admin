<?php
class Helper{
  public function klasifikasiKomentar($pernyataan, $total_nilai){
    if($total_nilai > $pernyataan->plus_value){
      return "plus";
    } else {
      return "minus";
    }
  }

  public function isNeedTask($myvalue, $median){
    if($myvalue <= $median){
      return true;
    } else {
      return false;
    }
  }
}
?>
