<div class="dashboard-ecommerce">
  <div class="container-fluid dashboard-content ">
    <!-- ============================================================== -->
    <!-- pageheader  -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
          <h2 class="pageheader-title">Edit Wisata </h2>
          <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
          <div class="page-breadcrumb">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('wisata') ?>" class="breadcrumb-link">Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Wisata</li>
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
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_wisata" value="<?= $data['wisata']->id_wisata ?>">
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Nama Wisata</label>
                  <input required name="nama_wisata" type="text" class="form-control" value="<?= $data['wisata']->nama_wisata ?>">
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Kategori Wisata</label>
                  <select class="form-control" id="sel1" name="model">
                    <?php
                    foreach ($data['kategori'] as $k => $kategori) {
                    ?>
                    <option value="<?= $kategori->id_kategori?>"><?= $kategori->nama_kategori?></option>
                  <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Deskripsi</label>
                  <textarea required name="keterangan" class="form-control" id="editor1" rows="3"><?= $data['wisata']->deskripsi ?></textarea>
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Latitude</label>
                  <input required name="telp" type="text" class="form-control" value="<?= $data['wisata']->latitude ?>">
                </div>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Longitude</label>
                  <input required name="biaya" type="text" class="uang form-control" value="<?= $data['wisata']->longitude ?>">
                </div>
                <div class="custom-file mb-3">
                  <input type="submit" href="#" class="centerHorizontal btn btn-primary" value="Simpan"></a>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
