<div class="dashboard-ecommerce">
  <div class="container-fluid dashboard-content ">
    <!-- ============================================================== -->
    <!-- pageheader  -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
          <h2 class="pageheader-title">Tambah Wisata </h2>
          <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
          <div class="page-breadcrumb">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('wisata') ?>" class="breadcrumb-link">Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah/Edit Wisata</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end pageheader  -->
    <!-- ============================================================== -->
    <div class="ecommerce-widget">
      <div class="row">
        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- recent orders  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <h5 class="card-header">Masukkan seluruh data dengan benar!</h5>
            <?php
                  if(($this->session->flashdata('alert')) !== null){
                      $message = $this->session->flashdata('alert');
                      $this->load->view('bodyview/alert', ['class' => $message['class'], 'message' => $message['message']]);
                  }
              ?>
            <div class="card-body">
              <form action="<?=base_url('wisata/simpan')?>" method="post">
                <input type="hidden" name="id_wisata" value="<?= $data['form'] ? "" : $data['wisata']->id_wisata ?>">
                <input type="hidden" name="action" value="<?= $data['type'] ?>">
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Nama Wisata</label>
                  <input required name="nama_wisata" type="text" class="form-control" value="<?= $data['form'] ? "" : $data['wisata']->nama_wisata ?>">
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Kategori Wisata</label>
                  <select class="form-control" id="sel1" name="id_kategori">
                    <?php
                    foreach ($data['kategori'] as $k => $kategori) {
                    ?>
                    <option
                    <?= $data['form'] ? "" : ($kategori->id_kategori == $data['wisata']->id_kategori ? "selected" : "") ?> value="<?= $kategori->id_kategori?>"><?= $kategori->nama_kategori?></option>
                  <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Deskripsi</label>
                  <textarea required name="deskripsi" class="form-control" id="editor1" rows="3">
                    <?= $data['form'] ? "" : $data['wisata']->deskripsi ?>
                  </textarea>
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Latitude</label>
                  <input required name="latitude" type="text" class="form-control" value="<?= $data['form'] ? "" : $data['wisata']->latitude ?>">
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Longitude</label>
                  <!-- <input required name="longitude" type="text" class="uang form-control" value="<?= $data['form'] ? "" : $data['wisata']->longitude ?>"> -->
                  <input required name="longitude" type="text" class="form-control" value="<?= $data['form'] ? "" : $data['wisata']->longitude ?>">
                </div>
                <div class="custom-file mb-3">
                  <input type="submit" href="#" class="centerHorizontal btn btn-primary" value="Simpan"></a>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        if(!$data['form']){
          ?>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <h5 class="card-header">Foto - foto Wisata</h5>
              <div class="card-body">
                <div class="w-100">
                  <?php
                  foreach ($data['foto'] as $k => $img) {
                  ?>
                      <div class="col-md-2" style="float:left">
                        <div class="w-100 heightRect bayang layout-img-icon bg" style="background-image: url('<?= base_url()."datas/wisata/".$img->url_foto ?>');">
                          <div class="pointer heightParent widthParent layout-hapus" onclick="redirect('<?= base_url()?>wisata/deletefoto?id_wisata=<?= $img->id_wisata?>&id=<?php echo $img->id_foto_wisata; ?>')">
                            <div class="col-md-12 tengah-text" style="padding-top:60px"><b>HAPUS</b></div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                </div>
                <!-- <?php echo form_open_multipart('kendaraan/simpanphoto');?> -->
                <form action="<?= base_url('wisata/simpanphoto')?>" method="post" enctype="multipart/form-data">
                  <input name="id_wisata" type="hidden" class="form-control" value="<?php echo $data['wisata']->id_wisata;  ?>">
                  <div class="custom-file mb-3" style="margin-top:20px">
                    <input name="photo" type="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile" style="text-align:center">Klik untuk memilih foto wisata</label>
                  </div>
                  <div class="custom-file mb-3">
                    <input type="submit" href="#" class="col-xl-4 centerHorizontal btn btn-primary" value="Tambahkan gambar"></a>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
