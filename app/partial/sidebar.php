<?php session_start() ?>
<nav class="pc-sidebar">
   <div class="navbar-wrapper">
      <div class="m-header">
         <a href="../dashboard/index.html" class="b-brand text-primary">
            <!-- ========   Change your logo from here   ============ -->
            <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo">
            <span class="badge bg-light-success rounded-pill ms-2 theme-version">v2.6.0</span>
         </a>
      </div>
      <div class="navbar-content">
         <div class="card pc-user-card">
            <div class="card-body">
               <div class="d-flex align-items-center">
                  <div class="flex-shrink-0">
                     <img src="../assets/images/user/avatar-1.jpg" alt="user-image" class="user-avtar wid-45 rounded-circle" />
                  </div>
                  <div class="flex-grow-1 ms-3 me-2">
                     <h6 class="mb-0">
                        <?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Guest') ?>
                     </h6>
                     <small>
                        <?= ucfirst($_SESSION['user']['role'] ?? 'User') ?>
                     </small>
                  </div>
                  <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-sort-outline"></use>
                     </svg>
                  </a>
               </div>
               <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                  <div class="pt-3">
                     <a href="#!">
                        <i class="ti ti-user"></i>
                        <span>My Account</span>
                     </a>
                     <a href="#!">
                        <i class="ti ti-settings"></i>
                        <span>Settings</span>
                     </a>
                     <a href="#!">
                        <i class="ti ti-lock"></i>
                        <span>Lock Screen</span>
                     </a>
                     <a href="#!">
                        <i class="ti ti-power"></i>
                        <span>Logout</span>
                     </a>
                  </div>
               </div>
            </div>
         </div>

         <ul class="pc-navbar">
            <li class="pc-item pc-caption">
               <label>Navigation</label>
            </li>

            <li class="pc-item">
               <a href="index" class="pc-link">
                  <span class="pc-micon">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-status-up"></use>
                     </svg>
                  </span>
                  <span class="pc-mtext">Dashboard</span>
               </a>
            </li>

            <li class="pc-item pc-caption">
               <label>Aktivitas Lab</label>
            </li>
            <li class="pc-item pc-hasmenu">
               <a href="javascript:;" class="pc-link"><span class="pc-micon">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-level"></use>
                     </svg> </span><span class="pc-mtext">Master Data</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
               <ul class="pc-submenu">
                  <li class="pc-item"><a class="pc-link" href="master_lab">Laboratorium</a></li>
                  <li class="pc-item"><a class="pc-link" href="#">Dokter Patologi</a></li>
                  <li class="pc-item"><a class="pc-link" href="#">Petugas</a></li>
                  <li class="pc-item"><a class="pc-link" href="#">Print Out</a></li>
               </ul>
            </li>
            <li class="pc-item">
               <a href="exposure" class="pc-link"><span class="pc-micon">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-user"></use>
                     </svg> </span><span class="pc-mtext">Permintaan Laboratorium</span></a>
            </li>
            <li class="pc-item pc-caption">
               <label>Aktivitas Pemeriksaan</label>
               <svg class="pc-icon">
                  <use xlink:href="#custom-box-1"></use>
               </svg>
            </li>
            <li class="pc-item">
               <a href="invoice" class="pc-link"><span class="pc-micon">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-document"></use>
                     </svg> </span><span class="pc-mtext">Pemeriksaan</span></a>
            </li>
            <li class="pc-item">
               <a href="revenue" class="pc-link"><span class="pc-micon">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-text-block"></use>
                     </svg> </span><span class="pc-mtext">Riwayat</span></a>
            </li>

            <li class="pc-item"><a href="loans" class="pc-link">
                  <span class="pc-micon">
                     <svg class="pc-icon">
                        <use xlink:href="#custom-notification-status"></use>
                     </svg>
                  </span>
                  <span class="pc-mtext">Analyzer Calibration</span></a>
            </li>
         </ul>
         <div class="card pc-user-card mt-3">
            <div class="card-body text-center">
               <!-- <img src="../assets/images/application/img-coupon.png" alt="img" class="img-fluid w-50" /> -->
               <h5 class="mb-0 mt-1">Nusa Tirta Teknologi</h5>
               <p>Company</p>
               <a
                  href="#"
                  target="_blank"
                  class="btn btn-warning">
                  <svg class="pc-icon me-2">
                     <use xlink:href="#custom-logout-1-outline"></use>
                  </svg>
                  Upgrade to Pro
               </a>
            </div>
         </div>
      </div>
   </div>
</nav>
<?php require 'header.php'; ?>
<script>
   document.addEventListener("DOMContentLoaded", () => {
      const path = window.location.pathname;
      const file = path.substring(path.lastIndexOf('/') + 1);
      const page = file.split("?")[0].replace(".php", "");

      /* ============================================
         Halaman turunan yang tetap dianggap CONTRACT
      ============================================ */
      const contractPages = ["contract", "contract_detail", "contract_invoice"];

      /* ============================================
         MASTER KATEGORI yang punya anak page
         (jika perlu tambah mapping lain)
      ============================================ */
      const menuMapping = [{
            base: "product",
            pages: ["product", "product_detail", "product_price"]
         },
         {
            base: "project",
            pages: ["project", "project_detail", "project_revenue"]
         },
         {
            base: "contract",
            pages: contractPages
         }
      ];

      let activeMenu = page; // default: sama dengan nama file

      // cari apakah page termasuk kelompok yang mapped
      menuMapping.forEach(map => {
         if (map.pages.includes(page)) {
            activeMenu = map.base;
         }
      });

      // selector menu berdasarkan hasil mapping
      const selector = `.pc-navbar a.pc-link[href="${activeMenu}"]`;
      const activeLink = document.querySelector(selector);

      if (activeLink) {
         const item = activeLink.closest(".pc-item");
         if (item) item.classList.add("active");

         const submenu = activeLink.closest(".pc-submenu");
         if (submenu) {
            const parent = submenu.closest(".pc-hasmenu");
            if (parent) {
               parent.classList.add("active", "open", "pc-trigger");
               submenu.classList.add("show");
            }
         }
      }
   });
</script>