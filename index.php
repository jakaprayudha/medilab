<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Login | Medilab</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description"
    content="Medi Lab">
  <meta name="keywords"
    content="Medi Lab">
  <meta name="author" content="Imzack Developer">

  <!-- [Favicon] icon -->
  <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon"> <!-- [Font] Family -->
  <link rel="stylesheet" href="assets/fonts/inter/inter.css" id="main-font-link" />
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="assets/css/style-preset.css">
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v1">
      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <div class="text-center">
              <a href="#"><img src="assets/images/logo-dark.svg" alt="img"></a>
            </div>
            <div class="saprator my-3">
            </div>
            <h4 class="text-center f-w-500 mb-3">Login with your email</h4>
            <div id="loginError" class="alert alert-danger d-none mt-3"></div>
            <form id="loginForm">
              <div class="form-group mb-3">
                <input type="email" class="form-control" id="email" placeholder="Email Address">
              </div>
              <div class="form-group mb-3">
                <input type="password" class="form-control" id="password" placeholder="Password">
              </div>
              <div class="d-flex mt-1 justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                  <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label>
                </div>
                <a href="">
                  <h6 class="text-secondary f-w-400 mb-0">Forgot Password?</h6>
                </a>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
  <script src="assets/js/plugins/popper.min.js"></script>
  <script src="assets/js/plugins/simplebar.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/fonts/custom-font.js"></script>
  <script src="assets/js/script.js"></script>
  <script src="assets/js/theme.js"></script>
  <script src="assets/js/plugins/feather.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $('#loginForm').on('submit', function(e) {
      e.preventDefault();

      $.post("api/login", {
        email: $('#email').val(),
        password: $('#password').val()
      }, function(res) {

        if (res.message !== "Login successful") {
          $('#loginError').removeClass('d-none').text(res.message);
          return;
        }

        // redirect berdasarkan role
        if (res.role === 'admin') {
          window.location.href = "admin/index";
        } else {
          window.location.href = "home";
        }

      }, 'json');
    });
  </script>
</body>

</html>