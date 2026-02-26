<?php
header("Content-Type: application/json");
require_once "../database/db.php";

$method = $_SERVER["REQUEST_METHOD"];

/* ==========================================================
   HEADER PERMINTAAN
========================================================== */
if ($method === "GET" && ($_GET["mode"] ?? '') === "header") {

   $nopermintaan = mysqli_real_escape_string($conn, $_GET["no"] ?? '');
   $nomor_rm     = mysqli_real_escape_string($conn, $_GET["rm"] ?? '');
   $nomor_visit  = mysqli_real_escape_string($conn, $_GET["visit"] ?? '');

   $sql = "SELECT lab.*, ps.nama_pasien, ps.dokter, ps.nomor_visit,
            ps.sumber, pv.gender, ps.usia, pv.tanggal_lahir
           FROM permintaan_lab AS lab
           INNER JOIN pasien_visit AS ps ON ps.nomor_rm = lab.nomor_rm
           INNER JOIN pasien AS pv ON pv.nomor_rm = ps.nomor_rm
           WHERE lab.nopermintaan = '$nopermintaan'
           AND lab.nomor_rm = '$nomor_rm'
           AND lab.catatan = '$nomor_visit'
           LIMIT 1";

   $q = mysqli_query($conn, $sql);

   if (!$q || mysqli_num_rows($q) == 0) {
      echo json_encode([
         "success" => false,
         "message" => "Data tidak ditemukan"
      ]);
      exit;
   }

   $row = mysqli_fetch_assoc($q);

   // hitung total item
   $q2 = mysqli_query($conn, "
      SELECT COUNT(*) as total
      FROM permintaan_lab_detail
      WHERE nopermintaan = '$nopermintaan'
   ");

   $total = mysqli_fetch_assoc($q2)["total"] ?? 0;

   echo json_encode([
      "success" => true,
      "data" => $row,
      "total_item" => $total
   ]);
   exit;
}


/* ==========================================================
   LIST PARAMETER LAB
========================================================== */
if ($method === "GET" && !isset($_GET["id"])) {

   $nopermintaan = mysqli_real_escape_string($conn, $_GET["no"] ?? '');
   $lab          = mysqli_real_escape_string($conn, $_GET["lab"] ?? '');

   // ambil kode lab
   $kode = '';

   $checkkode = mysqli_query(
      $conn,
      "SELECT kode FROM laboratorium_detail
       WHERE assemen = '$lab'
       LIMIT 1"
   );

   if ($checkkode && mysqli_num_rows($checkkode) > 0) {
      $datakode = mysqli_fetch_assoc($checkkode);
      $kode = $datakode['kode'];
   }

   $where = "WHERE 1=1";

   if ($kode != '') {
      $where .= " AND li.kode = '$kode'";
   }

   $sql = "
      SELECT li.*, hl.hasil
      FROM laboratorium_item li
      LEFT JOIN hasil_lab hl
         ON hl.parameter = li.assemen
         AND hl.permintaan = '$nopermintaan'
         AND hl.lab = '$lab'
      $where
      ORDER BY li.urutan ASC
   ";

   $q = mysqli_query($conn, $sql);

   if (!$q) {
      echo json_encode([
         "success" => false,
         "message" => mysqli_error($conn)
      ]);
      exit;
   }

   $data = [];
   while ($row = mysqli_fetch_assoc($q)) {
      $data[] = $row;
   }

   echo json_encode($data);
   exit;
}


/* ==========================================================
   SAVE / UPDATE HASIL LAB
========================================================== */
if ($method === "POST" && ($_POST["mode"] ?? '') === "save_hasil") {

   $permintaan = mysqli_real_escape_string($conn, $_POST["permintaan"] ?? '');
   $lab        = mysqli_real_escape_string($conn, $_POST["lab"] ?? '');
   $parameter  = mysqli_real_escape_string($conn, $_POST["parameter"] ?? '');
   $hasil      = mysqli_real_escape_string($conn, $_POST["hasil"] ?? '');
   $satuan     = mysqli_real_escape_string($conn, $_POST["satuan"] ?? '');
   $referensi  = mysqli_real_escape_string($conn, $_POST["referensi"] ?? '');

   if (!$permintaan || !$parameter) {
      echo json_encode([
         "success" => false,
         "message" => "Data tidak lengkap"
      ]);
      exit;
   }

   // cek sudah ada
   $cek = mysqli_query($conn, "
      SELECT id FROM hasil_lab
      WHERE permintaan = '$permintaan'
      AND lab = '$lab'
      AND parameter = '$parameter'
      LIMIT 1
   ");

   if (!$cek) {
      echo json_encode([
         "success" => false,
         "message" => mysqli_error($conn)
      ]);
      exit;
   }

   /* ================= UPDATE ================= */
   if (mysqli_num_rows($cek) > 0) {

      $row = mysqli_fetch_assoc($cek);
      $id = $row['id'];

      $update = mysqli_query($conn, "
         UPDATE hasil_lab
         SET hasil = '$hasil',
             satuan = '$satuan',
             referensi = '$referensi'
         WHERE id = $id
      ");

      if (!$update) {
         echo json_encode([
            "success" => false,
            "message" => mysqli_error($conn)
         ]);
         exit;
      }

      echo json_encode([
         "success" => true,
         "message" => "Berhasil update"
      ]);
   }

   /* ================= INSERT ================= */ else {

      $insert = mysqli_query($conn, "
         INSERT INTO hasil_lab
         (permintaan, lab, parameter, hasil, satuan, referensi)
         VALUES
         ('$permintaan','$lab','$parameter','$hasil','$satuan','$referensi')
      ");

      if (!$insert) {
         echo json_encode([
            "success" => false,
            "message" => mysqli_error($conn)
         ]);
         exit;
      }

      echo json_encode([
         "success" => true,
         "message" => "Berhasil simpan"
      ]);
   }

   exit;
}


/* ==========================================================
   FALLBACK
========================================================== */
echo json_encode([
   "success" => false,
   "message" => "Invalid Request"
]);
exit;
