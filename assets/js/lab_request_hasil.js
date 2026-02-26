function getParam(name) {
  const url = new URL(window.location.href);
  return url.searchParams.get(name);
}

const nopermintaan = getParam("no");
const nomor_rm = getParam("rm");
const nomor_visit = getParam("visit");
const lab = getParam("lab");
// ==========================================================
//   DATA TABLE
// ==========================================================

const tablePermintaan = $("#PermintaanTable").DataTable({
  pageLength: 20,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/lab_request_hasil",
    type: "GET",
    data: function (d) {
      d.no = nopermintaan;
      d.rm = nomor_rm;
      d.visit = nomor_visit;
      d.lab = lab;
    },
    dataSrc: "",
  },
  columns: [
    { data: null },
    { data: "assemen" },
    { data: "satuan" },
    { data: "minimum" },
    { data: "maksimum" },
    { data: "hasil" },
  ],
  columnDefs: [
    {
      targets: 0,
      render: (d, t, r, m) => m.row + 1,
    },
    {
      targets: 5,
      render: function (data, type, row) {
        return `
        <input type="text"
               class="form-control form-control-sm input-hasil"
               value="${data ?? ""}"
               data-parameter="${row.assemen}"
               data-satuan="${row.satuan}"
               data-min="${row.minimum}"
               data-max="${row.maksimum}">
      `;
      },
    },
  ],
});

let saveTimer;

$("#PermintaanTable").on("keyup change", ".input-hasil", function () {
  const el = $(this);

  clearTimeout(saveTimer);

  saveTimer = setTimeout(() => {
    const hasil = el.val();
    const parameter = el.data("parameter");
    const satuan = el.data("satuan");
    const referensi = (el.data("min") ?? "") + " - " + (el.data("max") ?? "");

    $.ajax({
      url: "../api/lab_request_hasil",
      type: "POST",
      dataType: "json",
      data: {
        mode: "save_hasil",
        permintaan: nopermintaan,
        lab: lab,
        parameter: parameter,
        hasil: hasil,
        satuan: satuan,
        referensi: referensi,
      },
      success: function (res) {
        if (res.success) {
          showToast("Berhasil simpan", "success");
        } else {
          showToast(res.message || "Gagal simpan", "danger");
        }
      },
      error: function () {
        showToast("Error server", "danger");
      },
    });
  }, 500); // delay 0.5 detik supaya tidak spam save
});

// ==========================================================
//  TOAST
// ==========================================================
function showToast(msg, type = "primary") {
  const toastEl = document.getElementById("liveToast");
  const toastBody = document.getElementById("toastMessage");
  toastEl.className = `toast text-bg-${type} border-0`;
  toastBody.innerText = msg;
  new bootstrap.Toast(toastEl).show();
}

// ================= LOAD TEST =================
function loadTest() {
  $.ajax({
    url: "../api/master_test",
    type: "GET",
    dataType: "json",
    success: function (res) {
      let html = `<option value="">-- Pilih Pemeriksaan --</option>`;

      res.forEach((d) => {
        html += `
                    <option value="${d.assemen}"
                            data-kode="${d.kode}"
                            data-tarif="${d.tarif}">
                        ${d.assemen} 
                    </option>
                `;
      });

      $("#pemeriksaan_id").html(html).trigger("change");
    },
  });
}

function loadHeader() {
  $.ajax({
    url: "../api/lab_request_hasil",
    type: "GET",
    dataType: "json",
    data: {
      mode: "header",
      no: nopermintaan,
      rm: nomor_rm,
      visit: nomor_visit,
    },
    success: function (res) {
      if (!res.success) return;

      const d = res.data;

      $("#d_nopermintaan").text(d.nopermintaan || "-");
      $("#d_nama_pasien").text(d.nama_pasien || "-");
      $("#d_nomor_rm").text(d.nomor_rm || "-");
      $("#d_sumber").text(d.sumber || "-");
      $("#d_dokter").text(d.dokter || "-");
      $("#d_tanggal").text(d.tanggal || "-");
      $("#d_waktu").text(d.waktu || "-");
      $("#d_nomor_visit").text(d.nomor_visit || "-");
      $("#d_gender").text(d.gender || "-");
      $("#d_tgl_lahir").text(d.tanggal_lahir || "-");
      $("#d_usia").text(d.usia || "-");
    },
  });
}

$(document).ready(function () {
  loadHeader();
});

$("#btnGetAlat").on("click", function () {
  $("#loadingAlat").removeClass("d-none");

  // simulasi ambil data alat 2 detik
  setTimeout(() => {
    $("#loadingAlat").addClass("d-none");

    showToast("Data berhasil diambil dari alat", "success");

    // kalau mau reload tabel hasil
    tablePermintaan.ajax.reload(null, false);
  }, 2000);
});
