<div class="dashboard-ecommerce" style="min-height: 85vh;">
  <div class="container-fluid dashboard-content ">
    <!-- ============================================================== -->
    <!-- pageheader  -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
          <h2 class="pageheader-title">Kategori Wisata </h2>
          <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
          <div class="page-breadcrumb">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="breadcrumb-link">Kategori Kendaraan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah/Edit Kategori Kendaraan</li>
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
              <form action="<?=base_url('kategori/simpan')?>" method="post" enctype="multipart/form-data">
                <?php
                  if($data['form']){
                    ?>
                      <input type="hidden" name="action" value="tambah">
                    <?php
                  } else {
                    ?>
                      <input type="hidden" name="action" value="edit">
                      <input type="hidden" name="id" value="<?= $data['kategori']->id_kategori ?>">
                    <?php
                  }
                ?>
                <div class="form-group">
                  <label for="inputText3" class="col-form-label">Nama Kategori</label>
                  <input required name="nama_kategori" id="nama_kategori" type="text" class="form-control" value="<?= $data['form'] ? "" : $data['kategori']->nama_kategori ?>">
                </div>
                <div class="custom-file mb-3">
                  <input <?= $data['form'] ? "required" : ""?> name="foto_kategori" type="file" class="custom-file-input" id="customFile">
                  <label class="custom-file-label" for="customFile">Masukkan gambar Kategori</label>
                  <?= $data['form'] ? "" : '<img src="'.base_url().'/datas/kategori/'.$data['kategori']->foto_kategori.'" alt="user" class="rounded" width="32"/>' ?>
                </div>
                <div class="custom-file mb-3">
                  <input type="submit" href="#" class="centerHorizontal btn btn-primary" value="<?= $data['form'] ? "Tambahkan" : "Edit"?>"></a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
