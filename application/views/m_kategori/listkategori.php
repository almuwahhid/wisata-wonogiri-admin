<div class="dashboard-ecommerce" style="min-height: 85vh;">
  <div class="container-fluid dashboard-content ">
    <!-- ============================================================== -->
    <!-- pageheader  -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
          <h2 class="pageheader-title">Daftar Kategori Wisata </h2>
          <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
          <div class="page-breadcrumb">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Kategori Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Kategori Wisata</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="ecommerce-widget">
      <div class="row">
        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- recent orders  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <h5 class="card-header">Kategori Wisata yang sudah ditambahkan</h5>
            <?php
                  if(($this->session->flashdata('alert')) !== null){
                      $message = $this->session->flashdata('alert');
                      $this->load->view('bodyview/alert', ['class' => $message['class'], 'message' => $message['message']]);
                  }
              ?>
            <div class="card-body p-0">
              <div class="table-responsive">
                <?php
                if(count($data['kategori']) == 0){
                  ?>
                  <center class="marg50-top marg50-bottom">Data belum tersedia </center>
                  <?php
                } else {
                  ?>
                <table class="table">
                  <thead class="bg-light">
                    <tr class="border-0">
                      <th class="border-0 text-center" style="width:20px">No</th>
                      <th class="border-0 text-center">Nama Kategori Wisata</th>
                      <th class="border-0 text-center">Foto Kategori</th>
                      <th class="border-0 text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 0;
                    foreach ($data['kategori'] as $k => $kategori) { ?>
                      <tr>
                        <td class="centerHorizontal text-center">
                          <?= ++$no;?>
                        </td>
                        <td class="text-center">
                          <?= $kategori->nama_kategori ?>
                        </td>
                        <td class="text-center">
                          <img src="<?= base_url().'/datas/kategori/'.$kategori->foto_kategori?>" alt="user" class="rounded" width="32"/>
                        </td>
                        <td class="text-center">
                          <a class="btn btn-success" href='<?= base_url()."/kategori/detail/".$kategori->id_kategori; ?>'>
                            <i class="fas fa-edit"></i>
                          </a> &nbsp;&nbsp;
                          <a href="#" onclick="redirect('<?= base_url()."/kategori/delete?id=".$kategori->id_kategori; ?>')">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                      <?php }?>
                  </tbody>
                </table><?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
