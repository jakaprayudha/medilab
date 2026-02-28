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
    { data: "ass_alat" },
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
      targets: 6,
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

$("#btnPrint").on("click", function () {
  const visit = getParam("visit");
  const no = getParam("no");
  const rm = getParam("rm");
  const lab = getParam("lab");

  const url = `../api/lab_request_print?visit=${encodeURIComponent(visit)}&no=${encodeURIComponent(no)}&rm=${encodeURIComponent(rm)}&lab=${encodeURIComponent(lab)}`;
  window.open(url, "_blank");
});

//HISTOGRAM
let wbcChart, rbcChart, pltChart;

$("#btnHistogram").on("click", function () {
  const modal = new bootstrap.Modal(document.getElementById("modalHistogram"));

  modal.show();

  loadHistogramData();
});
function gaussian(x, mean, sd, height) {
  return height * Math.exp(-0.5 * Math.pow((x - mean) / sd, 2));
}
function loadHistogramData() {
  $.get(
    "../api/lab_histogram",
    {
      no: nopermintaan,
      lab: lab,
    },
    function (res) {
      if (!res.success) {
        showToast("Data histogram tidak ditemukan", "danger");
        return;
      }

      const d = res.data;

      /* ================= WBC ================= */

      const lymph = Number(d["limfosit"] ?? d["%ymphocytes"] ?? 30);
      // Alat 5 DIVV
      // const mid = Number(
      //   d["%mid"] ??
      //     Number(d["monosit"] ?? d["%monocytes"] ?? 0) +
      //       Number(d["eosinofil"] ?? 0) +
      //       Number(d["basofil"] ?? 0),
      // );

      // Alat 3 DIVV
      const mid = Number(d["monosit"] ?? d["%monocytes"] ?? 0);
      // Alat 5 DIVV
      // const gran = Number(
      //   d["gran%"] ??
      //     d["%Granulocyte"] ??
      //     Number(d["neutrofil segmen"] ?? 0) +
      //       Number(d["neutrofil batang"] ?? 0),
      // );
      // Alat 3 DIVV
      const gran = Number(d["gran%"] ?? d["%granulocyte"] ?? 0);

      function normalizeCount(val) {
        val = Number(val || 0);
        if (val < 50) return val * 1000;
        return val;
      }

      const wbcTotal = normalizeCount(d["leukosit"]);

      const wbcLabels = Array.from({ length: 40 }, (_, i) => i * 10 + 50);
      const wbcData = wbcLabels.map(
        (x) =>
          gaussian(x, 90, 15, (lymph / 100) * wbcTotal) +
          gaussian(x, 150, 20, (mid / 100) * wbcTotal) +
          gaussian(x, 300, 40, (gran / 100) * wbcTotal),
      );

      /* ================= RBC ================= */

      const mcv = Number(d["mcv"] ?? 90);
      const rdw = Number(d["rdw"] ?? 13);

      const rbcLabels = Array.from({ length: 40 }, (_, i) => i * 5 + 60);

      const rbcData = rbcLabels.map((x) => gaussian(x, mcv, rdw, 100));

      /* ================= PLT ================= */

      const mpv = Number(d["mpv"] ?? 10);
      const pdw = Number(d["pdw"] ?? 12);

      const pltLabels = Array.from({ length: 40 }, (_, i) => i * 2 + 2);

      const pltData = pltLabels.map((x) => gaussian(x, mpv, pdw / 2, 120));

      renderCharts(wbcLabels, wbcData, rbcLabels, rbcData, pltLabels, pltData);
    },
    "json",
  );
}
function renderCharts(
  wbcLabels,
  wbcData,
  rbcLabels,
  rbcData,
  pltLabels,
  pltData,
) {
  if (wbcChart) wbcChart.destroy();
  if (rbcChart) rbcChart.destroy();
  if (pltChart) pltChart.destroy();

  wbcChart = new Chart(document.getElementById("wbcChart"), {
    type: "line",
    data: {
      labels: wbcLabels,
      datasets: [
        {
          label: "WBC",
          data: wbcData,
          borderColor: "green",
          tension: 0.3,
        },
      ],
    },
  });

  rbcChart = new Chart(document.getElementById("rbcChart"), {
    type: "line",
    data: {
      labels: rbcLabels,
      datasets: [
        {
          label: "RBC",
          data: rbcData,
          borderColor: "red",
          tension: 0.3,
        },
      ],
    },
  });

  pltChart = new Chart(document.getElementById("pltChart"), {
    type: "line",
    data: {
      labels: pltLabels,
      datasets: [
        {
          label: "PLT",
          data: pltData,
          borderColor: "blue",
          tension: 0.3,
        },
      ],
    },
  });
}
