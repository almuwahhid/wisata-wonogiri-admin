<div class="dashboard-ecommerce" style="min-height : 88vh">
  <div class="container-fluid dashboard-content ">
    <!-- ============================================================== -->
    <!-- pageheader  -->
    <!-- ============================================================== -->
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
          <h2 class="pageheader-title">Detail User </h2>
          <p class="pageheader-text">H.O.P.E.</p>
          <div class="page-breadcrumb">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>users" class="breadcrumb-link">Daftar User</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $data->nama ?></li>
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
        <?php
              if(($this->session->flashdata('alert')) !== null){
                  $message = $this->session->flashdata('alert');
                  $this->load->view('bodyview/alert', ['class' => $message['class'], 'message' => $message['message']]);
              }
          ?>

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="card">
            <h5 class="card-header">Biodata <?= $data->nama ?></h5>
            <img style="margin:20px" src="<?= base_url() ?>profile/<?= $data->photo_profil == "" ? ($data->jenis_kelamin == "Perempuan" ? "woman.png" : "man.png"): $data->photo_profil ?>" alt="foto profil" class="rounded text-center centerHorizontal" width="180">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <tr class="border-0 bg-light">
                      <td class="border-0" style="width:50%">Email</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->email ?></td>
                    </tr>
                    <tr class="border-0">
                      <td class="border-0">Nama Lengkap</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->nama ?></td>
                    </tr>
                    <tr class="border-0 bg-light">
                      <td class="border-0">Jenis Kelamin</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->jenis_kelamin ?></td>
                    </tr>
                    <tr class="border-0">
                      <td class="border-0">Tanggal Lahir</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->tgl ?></td>
                    </tr>
                    <tr class="border-0 bg-light">
                      <td class="border-0">Program Studi</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->program_studi ?></td>
                    </tr>
                    <tr class="border-0">
                      <td class="border-0">Semester</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->semester ?></td>
                    </tr>
                    <tr class="border-0 bg-light">
                      <td class="border-0">Pekerjaan Impian</td>
                      <td class="border-0">:</td>
                      <td class="border-0"><?= $data->pekerjaan_impian ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
