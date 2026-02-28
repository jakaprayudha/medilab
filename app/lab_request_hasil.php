<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<?php require 'partial/head.php'; ?>

<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="light" class="pc-sidebar-mobile-transform">
   <!-- [ Pre-loadetetapr ] start -->
   <div class="loader-bg">
      <div class="loader-track">
         <div class="loader-fill"></div>
      </div>
   </div>
   <!-- [ Pre-loader ] End -->
   <!-- [ Sidebar Menu - Header ] start -->
   <?php require 'partial/sidebar.php'; ?>
   <!-- [ Sidebar Menu - Header] end --> <!-- [ Header Topbar ] start -->
   <!-- [ Header ] end -->
   <!-- [ Main Content ] start -->
   <div class="pc-container">
      <div class="pc-content">
         <!-- [ breadcrumb ] start -->
         <div class="page-header">
            <div class="page-block">
               <div class="row align-items-center">
                  <div class="col-md-12">
                     <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index">Home</a></li>
                        <li class="breadcrumb-item"><a href="lab_request">Permintaan Laboratorium</a></li>
                        <li class="breadcrumb-item">
                           <a href="javascript:history.back()">Pemeriksaan</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Pengisian Hasil</li>
                     </ul>
                  </div>
                  <div class="col-md-12">
                     <div class="page-header-title">
                        <h2 class="mb-0">Permintaan</h2>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- [ breadcrumb ] end -->
         <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">Detail Permintaan Laboratorium</h5>
                  <small>No Permintaan: <strong id="d_nopermintaan">-</strong></small>
               </div>

               <span class="badge bg-success fs-6" id="d_dokter">
               </span>
            </div>

            <div class="card-body">

               <div class="row g-3">

                  <div class="col-md-6">
                     <label class="text-muted small">Nama Pasien</label>
                     <div class="fw-semibold fs-5" id="d_nama_pasien">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Nomor RM</label>
                     <div class="fw-semibold" id="d_nomor_rm">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Jenis Kelamin</label>
                     <div class="fw-semibold" id="d_gender">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Tanggal Lahir</label>
                     <div class="fw-semibold" id="d_tgl_lahir">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Usia</label>
                     <div class="fw-semibold" id="d_usia">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Sumber</label>
                     <div class="fw-semibold" id="d_sumber">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Tanggal</label>
                     <div class="fw-semibold" id="d_tanggal">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Waktu</label>
                     <div class="fw-semibold" id="d_waktu">-</div>
                  </div>

                  <div class="col-md-6">
                     <label class="text-muted small">Nomor Visit</label>
                     <div class="fw-semibold" id="d_nomor_visit">-</div>
                  </div>

                  <div class="col-md-3">
                     <label class="text-muted small">Pemeriksaan</label>
                     <div class="fw-semibold">
                        <span class="badge bg-danger fs-6" id="">
                           <?php echo $_GET['lab'] ?>
                        </span>
                     </div>
                  </div>

               </div>

            </div>
         </div>
         <!-- ========== CONTENT ========== -->
         <div class="row">
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">

                  <!-- Kiri -->
                  <span class="fw-semibold">List of Permintaan</span>

                  <!-- Kanan -->
                  <div class="d-flex gap-2">
                     <button class="btn btn-light btn-sm"
                        onclick="window.history.back()">
                        <i class="bi bi-arrow-left"></i> Kembali
                     </button>

                     <button id="btnHistogram" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-bar-chart"></i> Histogram
                     </button>

                     <button id="btnPrint" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-printer"></i> Print Out
                     </button>

                     <button id="btnGetAlat" class="btn btn-primary btn-sm">
                        <i class="bi bi-download"></i> Get Data Alat
                     </button>
                  </div>

               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="PermintaanTable" class="table table-striped table-bordered w-100">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Parameter</th>
                              <th>LIS</th>
                              <th class="col-2">Satuan</th>
                              <th>Minimum</th>
                              <th class="col-1">Maksimum</th>
                              <th class="col-2">Hasil</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- [ Main Content ] end -->
   <?php require 'partial/footer.php' ?>
   <?php require 'partial/library.php' ?>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <div class="modal fade" id="modalHistogram" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered">
         <div class="modal-content">

            <div class="modal-header bg-success text-white">
               <h5 class="modal-title">
                  <i class="bi bi-bar-chart"></i> Histogram Pemeriksaan
               </h5>
               <button type="button" class="btn-close btn-close-white"
                  data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

               <div class="row g-3">

                  <!-- WBC -->
                  <div class="col-md-4">
                     <div class="border rounded p-2 h-100">
                        <h6 class="text-center mb-2">
                           <span class="badge bg-success">WBC</span>
                        </h6>
                        <canvas id="wbcChart" height="160"></canvas>
                     </div>
                  </div>

                  <!-- RBC -->
                  <div class="col-md-4">
                     <div class="border rounded p-2 h-100">
                        <h6 class="text-center mb-2">
                           <span class="badge bg-danger">RBC</span>
                        </h6>
                        <canvas id="rbcChart" height="160"></canvas>
                     </div>
                  </div>

                  <!-- PLT -->
                  <div class="col-md-4">
                     <div class="border rounded p-2 h-100">
                        <h6 class="text-center mb-2">
                           <span class="badge bg-primary">PLT</span>
                        </h6>
                        <canvas id="pltChart" height="160"></canvas>
                     </div>
                  </div>

               </div>

            </div>

            <div class="modal-footer">
               <button class="btn btn-secondary"
                  data-bs-dismiss="modal">
                  Tutup
               </button>
            </div>

         </div>
      </div>
   </div>
   <div id="loadingAlat" class="loading-overlay d-none">
      <div class="text-center text-white">
         <div class="spinner-border mb-3" role="status"></div>
         <div>Mengambil data dari alat...</div>
      </div>
   </div>
</body>
<!-- [Body] end -->
<script src="../assets/js/lab_request_hasil_3diff.js"></script>

</html>