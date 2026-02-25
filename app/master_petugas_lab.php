<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<?php require 'partial/head.php'; ?>
<?php
$modalId = "modalMasterPetugasLab";
$title   = "Tambah Master Petugas Lab";
$content = "forms/master_petugas_lab_frm.php"; // isi field Master Petugas Lab
$submitId = "btnSaveMasterPetugasLab";
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
                        <li class="breadcrumb-item" aria-current="page">Petugas Laboratorium</li>
                     </ul>
                  </div>
                  <div class="col-md-12">
                     <div class="page-header-title">
                        <h2 class="mb-0">Petugas Laboratorium</h2>
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
                  <span>List of Petugas</span>
                  <button class="btn btn-primary btn-sm"
                     data-bs-toggle="modal"
                     data-bs-target="#modalMasterPetugasLab">
                     <i class="bi bi-plus"></i> Tambah
                  </button>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="PATable" class="table table-striped table-bordered w-100">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Nama Lengkap</th>
                              <th>Username</th>
                              <th>Rilis</th>
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
<script src="../assets/js/master_petugas_lab.js"></script>

</html>