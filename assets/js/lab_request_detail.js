function getParam(name) {
  const url = new URL(window.location.href);
  return url.searchParams.get(name);
}

const nopermintaan = getParam("no");
const nomor_rm = getParam("rm");
const nomor_visit = getParam("visit");
// ==========================================================
//   DATA TABLE
// ==========================================================

const tablePermintaan = $("#PermintaanTable").DataTable({
  pageLength: 10,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/lab_request_test",
    type: "GET",
    data: function (d) {
      d.no = nopermintaan;
      d.rm = nomor_rm;
      d.visit = nomor_visit;
    },
    dataSrc: "",
  },
  columns: [
    { data: null },
    { data: "lab" },
    { data: "catatan" },
    { data: "hasil" },
    { data: "status" },
    { data: "id" },
  ],
  columnDefs: [
    {
      targets: 0,
      render: (d, t, r, m) => m.row + 1,
    },

    // status switch
    {
      targets: 4,
      className: "text-center",
      render: function (data, type, row) {
        const checked = data == 1 ? "checked" : "";

        return `
          <div class="form-check form-switch d-flex justify-content-center">
            <input class="form-check-input toggle-status"
                   type="checkbox"
                   data-id="${row.id}"
                   ${checked}>
          </div>
        `;
      },
    },

    // action buttons
    {
      targets: 5,
      className: "text-center",
      render: function (id, type, row) {
        return `
          <button class="btn btn-info btn-sm btn-detail action-btn"
                  data-id="${id}" data-nopermintaan="${row.nopermintaan}" data-nomor_rm="${row.nomor_rm}" data-nomor_visit="${nomor_visit}" data-lab="${row.lab}">
            <i class="bi bi-plus"></i> Hasil
          </button>
          <button class="btn btn-danger btn-sm btn-delete action-btn"
                  data-id="${id}">
            <i class="bi bi-trash"></i> Hapus
          </button>
        `;
      },
    },
  ],
});
$("#PermintaanTable").on("change", ".toggle-status", function () {
  const id = $(this).data("id");
  const status = $(this).is(":checked") ? 1 : 0;
  const el = $(this);

  $.ajax({
    url: "../api/lab_request_test",
    type: "POST",
    dataType: "json",
    data: {
      mode: "toggle_status",
      id: id,
      status: status,
    },
    success: function (res) {
      showToast(res.message, "success");
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      el.prop("checked", !status);
      showToast("Gagal update status", "danger");
    },
  });
});

// ==========================================================
//  SAVE  (CREATE / UPDATE)
// ==========================================================
$("#btnSavePermintaanLab").on("click", function () {
  const id = $("#id").val();
  const mode = id ? "update" : "create";

  $.post(
    "../api/lab_request_test",
    {
      mode,
      id,
      pemeriksaan_id: $("#pemeriksaan_id").val(),
      nomor_visit: $("#nomor_visit").val(),
      nomor_lab: $("#nomor_lab").val(),
      nomor_rm: $("#nomor_rm").val(),
      catatan: $("#catatan").val(),
    },
    (res) => {
      showToast(res.message, "success");
      $("#modalRequestLab").modal("hide");
      tablePermintaan.ajax.reload(null, false);
    },
    "json",
  );
});

// ==========================================================
//  DELETE
// ==========================================================
$(document).on("click", ".btn-delete", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "Yakin hapus Lab?",
    text: "Data tidak bisa dikembalikan setelah dihapus.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Ya, hapus",
    cancelButtonText: "Batal",
  }).then((r) => {
    if (!r.isConfirmed) return;
    $.post(
      "../api/lab_request_test",
      { mode: "delete", id: id },
      function (res) {
        showToast(res.message, "warning");
        tablePermintaan.ajax.reload(null, false);
      },
      "json",
    );
  });
});

$("#PermintaanTable").on("click", ".btn-detail", function () {
  const nopermintaan = $(this).data("nopermintaan");
  const nomor_rm = $(this).data("nomor_rm");
  const nomor_visit = $(this).data("nomor_visit");
  const lab = $(this).data("lab");
  // redirect ke halaman parameter
  window.location.href = `lab_request_hasil?visit=${nomor_visit}&no=${nopermintaan}&rm=${nomor_rm}&lab=${lab}`;
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
$(document).ready(function () {
  loadHeader();
  // modal dibuka
  $("#modalRequestLab").on("shown.bs.modal", function () {
    loadTest();
    $("#pemeriksaan_id").select2({
      placeholder: "-- Pilih Pemeriksaan --",
      allowClear: true,
      width: "100%",
      dropdownParent: $("#modalRequestLab"),
    });
  });

  // change TEST
  $(document).on("change", "#pemeriksaan_id", function () {
    const selected = $(this).find(":selected");

    $("#kode").val(selected.data("kode") || "");
    $("#tarif").val(selected.data("tarif") || "");
  });
});

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
    url: "../api/lab_request_test",
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
      $("#d_total_item").text(res.total_item || 0);
      $("#d_gender").text(d.gender || "-");
      $("#d_tgl_lahir").text(d.tanggal_lahir || "-");
      $("#d_usia").text(d.usia || "-");
    },
  });
}

$(document).ready(function () {
  loadHeader();
});
