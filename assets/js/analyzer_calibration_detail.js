function getParam(name) {
  const url = new URL(window.location.href);
  return url.searchParams.get(name);
}
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
    url: "../api/analyzer_calibration_detail",
    type: "GET",
    data: function (d) {
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
      url: "../api/analyzer_calibration_detail",
      type: "POST",
      dataType: "json",
      data: {
        mode: "save_hasil",
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
