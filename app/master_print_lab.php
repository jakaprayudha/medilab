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
                        <li class="breadcrumb-item" aria-current="page">Setting Print Out</li>
                     </ul>
                  </div>
                  <div class="col-md-12">
                     <div class="page-header-title">
                        <h2 class="mb-0">Setting Print Out</h2>
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
                  <span>List of Pemeriksaan Laboratorium</span>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="LabTable" class="table table-striped table-bordered w-100">
                        <thead>
                           <tr>
                              <th>No</th>
                              <th class="col-2">Kode</th>
                              <th>Pemeriksaan</th>
                              <th>Print Out Template</th>
                              <th class="text-center col-3">Preview</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="modalPreviewPrint" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
         <div class="modal-content">

            <div class="modal-header">
               <h5 class="modal-title">Preview Template</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="previewContent">
               <div class="text-center text-muted">
                  Loading preview...
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
<script src="../assets/js/master_print_lab.js"></script>

</html>