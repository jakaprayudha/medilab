<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<?php require 'partial/head.php'; ?>
<?php
$modalId = "modalMasterLab";
$title   = "Tambah Master Lab Parameter";
$content = "forms/master_lab_param_frm.php"; // isi field Master Lab
$submitId = "btnSaveMasterLab";
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
                        <li class="breadcrumb-item"><a href="master_lab">Pemerikasan Laboratorium</a></li>
                        <li class="breadcrumb-item" aria-current="page">Parameter</li>
                     </ul>
                  </div>
                  <div class="col-md-12">
                     <div class="page-header-title">
                        <h2 class="mb-0">Parameter</h2>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- [ breadcrumb ] end -->

         <!-- ========== CONTENT ========== -->
         <div class="row">
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">

                  <!-- Kiri -->
                  <span class="fw-semibold">List of Parameter</span>

                  <!-- Kanan -->
                  <div class="d-flex gap-2">
                     <button class="btn btn-light btn-sm"
                        onclick="window.history.back()">
                        <i class="bi bi-arrow-left"></i> Kembali
                     </button>

                     <button class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalMasterLab">
                        <i class="bi bi-plus"></i> Tambah
                     </button>
                  </div>

               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="LabTable" class="table table-striped table-bordered w-100">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Urutan</th>
                              <th>Parameter</th>
                              <th>LIS</th>
                              <th>Satuan</th>
                              <th>Minimum</th>
                              <th>Maksimum</th>
                              <th>Catatan</th>
                              <th>Status</th>
                              <th class="text-center col-3">Actions</th>
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
<script src="../assets/js/master_lab_parameter.js"></script>

</html>