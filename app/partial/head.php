<head>
   <title>Home | Medilab</title>
   <!-- [Meta] -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="description"
      content="Medi Lab">
   <meta name="keywords"
      content="Medi Lab">
   <meta name="author" content="Imzack Developer">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- [Favicon] icon -->
   <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"> <!-- [Font] Family -->
   <link rel="stylesheet" href="../assets/fonts/inter/inter.css" id="main-font-link" />
   <!-- [Tabler Icons] https://tablericons.com -->
   <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
   <!-- [Feather Icons] https://feathericons.com -->
   <link rel="stylesheet" href="../assets/fonts/feather.css">
   <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
   <link rel="stylesheet" href="../assets/fonts/fontawesome.css">
   <!-- [Material Icons] https://fonts.google.com/icons -->
   <link rel="stylesheet" href="../assets/fonts/material.css">
   <!-- [Template CSS Files] -->
   <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link">
   <link rel="stylesheet" href="../assets/css/style-preset.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   <style>
      @media (max-width: 991px) {
         .pc-sidebar {
            transform: translateX(-100%);
            transition: transform .3s ease-in-out;
         }

         .pc-sidebar.active {
            transform: translateX(0);
         }

         /* biar konten penuh saat sidebar hidden */
         .pc-container {
            padding-left: 0 !important;
         }
      }

      /* tombol tetap icon + teks di desktop */
      .action-btn i {
         font-size: 16px;
         vertical-align: middle;
      }

      /* sembunyikan teks saat layar kecil */
      @media (max-width: 768px) {
         .action-btn .btn-text {
            display: none;
         }

         .action-btn {
            padding: 4px 6px;
            /* lebih kompak */
         }
      }

      table td {
         white-space: normal;
         word-break: break-word;
      }
   </style>
</head>