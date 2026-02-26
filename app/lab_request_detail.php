<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<?php require 'partial/head.php'; ?>
<?php
$modalId = "modalRequestLab";
$title   = "Tambah Pemeriksaan Lab";
$content = "forms/lab_request_test.php"; // isi field Master Lab
$submitId = "btnSavePermintaanLab";
require "components/modal/modal.php";
?>
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
                        <li class="breadcrumb-item" aria-current="page">Permintaan Laboratorium</li>
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

               <span class="badge bg-warning text-dark fs-6" id="d_status">
                  Draft
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
                     <label class="text-muted small">Sumber</label>
                     <div class="fw-semibold" id="d_sumber">-</div>
                  </div>

                  <div class="col-md-6">
                     <label class="text-muted small">Dokter Pengirim</label>
                     <div class="fw-semibold" id="d_dokter">-</div>
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
                     <label class="text-muted small">Total Pemeriksaan</label>
                     <div class="fw-semibold">
                        <span class="badge bg-info fs-6" id="d_total_item">0</span>
                     </div>
                  </div>

               </div>

            </div>
         </div>
         <!-- ========== CONTENT ========== -->
         <div class="row">
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <span>List of Perminaan</span>
                  <button class="btn btn-primary btn-sm"
                     data-bs-toggle="modal"
                     data-bs-target="#modalRequestLab">
                     <i class="bi bi-plus"></i> Tambah
                  </button>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="PermintaanTable" class="table table-striped table-bordered w-100">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Pemeriksaan Lab</th>
                              <th>Catatan</th>
                              <th>File</th>
                              <th>Status</th>
                              <th class="text-center col-2">Actions</th>
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
</body>
<!-- [Body] end -->
<script src="../assets/js/lab_request_detail.js"></script>

</html>