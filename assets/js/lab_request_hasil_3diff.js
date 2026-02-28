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

/* ================= GLOBAL ================= */

let wbcChart = null;
let rbcChart = null;
let pltChart = null;

/* ================= BUTTON ================= */

$("#btnHistogram").on("click", function () {
  const modal = new bootstrap.Modal(document.getElementById("modalHistogram"));

  modal.show();

  loadHistogramData();
});

/* ================= GAUSSIAN ================= */

function gaussian(x, mean, sd, height) {
  return height * Math.exp(-0.5 * Math.pow((x - mean) / sd, 2));
}

/* ================= NORMALIZE COUNT ================= */

function normalizeCount(v) {
  if (!v) return 8000;

  v = Number(v);

  if (v < 100) return v * 1000; // 10.6 → 10600

  return v;
}

/* ================= LOAD DATA ================= */

function loadHistogramData() {
  $.get(
    "../api/lab_histogram",
    {
      no: nopermintaan,
      lab: lab,
    },
    function (res) {
      if (!res || !res.success) {
        showToast("Data histogram tidak ditemukan", "danger");
        return;
      }

      const d = res.data || {};

      console.log("DATA HIST:", d);

      /* ================= WBC ================= */

      const lymph = Number(
        d["lymph%"] ?? d["limfosit"] ?? d["%lymphocytes"] ?? 30,
      );

      const mid = Number(d["mid%"] ?? d["mxd%"] ?? d["%monocytes"] ?? 10);

      const gran = Number(d["gran%"] ?? d["%granulocyte"] ?? 60);

      const wbcTotal = normalizeCount(d["leukosit"]);

      console.log("WBC:", lymph, mid, gran, wbcTotal);

      // const wbcLabels = Array.from({ length: 40 }, (_, i) => i * 10 + 50);

      // const wbcData = wbcLabels.map(
      //   (x) =>
      //     gaussian(x, 70, 12, (lymph / 100) * wbcTotal) +
      //     gaussian(x, 140, 18, (mid / 100) * wbcTotal) +
      //     gaussian(x, 230, 35, (gran / 100) * wbcTotal),
      // );

      const wbcLabels = Array.from({ length: 40 }, (_, i) => i * 10);

      const wbcData = wbcLabels.map((x) => {
        // ===== LYMPH =====
        let lymphPeak = gaussian(x, 60, 7, (lymph / 100) * wbcTotal * 1.5);

        // 🔥 FORCE NAIK DI AWAL
        if (x < 80) {
          lymphPeak *= 2.5; // angka ini bisa kamu naikkan lagi
        }

        // ===== MID =====
        const midPeak = gaussian(x, 120, 30, (mid / 100) * wbcTotal * 0.9);

        // ===== GRAN =====
        const granPeak = gaussian(x, 220, 50, (gran / 100) * wbcTotal * 1.8);

        // ===== TAIL =====
        const tail = gaussian(x, 270, 90, (gran / 100) * wbcTotal * 0.45);

        // ===== VALLEY =====
        const valley = gaussian(x, 95, 18, wbcTotal * 0.25);

        return Math.max(lymphPeak + midPeak + granPeak + tail - valley, 0);
      });

      // const wbcData = wbcLabels.map((x) => {
      //   // ===== BOOST KIRI AGAR SPIKE TINGGI =====
      //   const leftBoost = 1 + Math.exp(-(x - 35) / 10);

      //   // ===== LYMPH =====
      //   const lymphPeak =
      //     gaussian(x, 60, 6, (lymph / 100) * wbcTotal * 1.4) * leftBoost;

      //   // ===== MID =====
      //   const midPeak = gaussian(x, 120, 30, (mid / 100) * wbcTotal * 0.9);

      //   // ===== GRAN =====
      //   const granPeak = gaussian(x, 220, 50, (gran / 100) * wbcTotal * 1.8);

      //   // ===== TAIL =====
      //   const tail = gaussian(x, 270, 90, (gran / 100) * wbcTotal * 0.45);

      //   // ===== VALLEY =====
      //   const valley = gaussian(x, 95, 18, wbcTotal * 0.25);

      //   return Math.max(lymphPeak + midPeak + granPeak + tail - valley, 0);
      // });

      /* ================= RBC ================= */

      const mcv = Number(d["mcv"] ?? 90);
      const rdw = Number(d["rdw"] ?? 13);

      const rbcLabels = Array.from({ length: 40 }, (_, i) => i * 5 + 60);

      const rbcData = rbcLabels.map((x) => gaussian(x, mcv, rdw / 2.5, 120));
      /* ================= PLT ================= */

      const mpv = Number(d["mpv"] ?? 10);
      const pdw = Number(d["pdw"] ?? 12);

      const pltLabels = Array.from({ length: 40 }, (_, i) => i * 2 + 2);

      const pltData = pltLabels.map((x) => gaussian(x, mpv, pdw / 3, 120));

      renderCharts(wbcLabels, wbcData, rbcLabels, rbcData, pltLabels, pltData);
    },
    "json",
  );
}

/* ================= RENDER ================= */

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

  /* ================= WBC ================= */

  wbcChart = new Chart(document.getElementById("wbcChart"), {
    type: "line",
    data: {
      labels: wbcLabels,
      datasets: [
        {
          label: "WBC",
          data: wbcData,
          borderColor: "green",
          borderWidth: 2,
          pointRadius: 0,
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { display: true },
        y: { display: false },
      },
    },
  });

  /* ================= RBC ================= */

  rbcChart = new Chart(document.getElementById("rbcChart"), {
    type: "line",
    data: {
      labels: rbcLabels,
      datasets: [
        {
          label: "RBC",
          data: rbcData,
          borderColor: "red",
          borderWidth: 2,
          pointRadius: 0,
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { display: true },
        y: { display: false },
      },
    },
  });

  /* ================= PLT ================= */

  pltChart = new Chart(document.getElementById("pltChart"), {
    type: "line",
    data: {
      labels: pltLabels,
      datasets: [
        {
          label: "PLT",
          data: pltData,
          borderColor: "blue",
          borderWidth: 2,
          pointRadius: 0,
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        x: { display: true },
        y: { display: false },
      },
    },
  });
}

function noise(v) {
  return v * (0.92 + Math.random() * 0.15);
}
