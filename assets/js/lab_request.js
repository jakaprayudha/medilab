// ==========================================================
//   DATA TABLE
// ==========================================================

const tablePermintaan = $("#PermintaanTable").DataTable({
  pageLength: 10,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/lab_request",
    type: "GET",
    dataSrc: "",
  },
  columns: [
    { data: null },
    { data: "nopermintaan" },
    { data: "nomor_rm" },
    { data: "nama_pasien" },
    { data: "dokter" },
    { data: "tanggal" },
    { data: "sumber" },
    { data: "total_item" },
    { data: "catatan" }, // nomor visit
    { data: "status" },
    { data: "id" },
  ],
  columnDefs: [
    {
      targets: 0,
      render: (d, t, r, m) => m.row + 1,
    },

    // tanggal format
    {
      targets: 5,
      className: "text-center fw-semibold",
      render: function (data, type, row) {
        return `
          <div>${data}</div>
          <small class="text-muted">${row.waktu}</small>
        `;
      },
    },

    // total item
    {
      targets: 7,
      className: "text-center",
      render: function (data) {
        return `<span class="badge bg-info">${data ?? 0} Test</span>`;
      },
    },

    // status switch
    {
      targets: 9,
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
      targets: 10,
      className: "text-center",
      render: function (id, type, row) {
        return `
          <button class="btn btn-info btn-sm btn-detail action-btn"
                  data-id="${id}" data-nopermintaan="${row.nopermintaan}" data-nomor_rm="${row.nomor_rm}" data-nomor_visit="${row.catatan}">
            <i class="bi bi-plus"></i> Pemeriksaan
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
    url: "../api/lab_request",
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
    "../api/lab_request",
    {
      mode,
      id,
      pasien_id: $("#pasien_id").val(),
      nomor_rm: $("#nomor_rm").val(),
      tanggal_lahir: $("#tanggal_lahir").val(),
      nomor_visit: $("#nomor_visit").val(),
      tanggal: $("#tanggal").val(),
      waktu: $("#waktu").val(),
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
      "../api/lab_request",
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
  // redirect ke halaman parameter
  window.location.href = `lab_request_detail?visit=${nomor_visit}&no=${nopermintaan}&rm=${nomor_rm}`;
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
  // modal dibuka
  $("#modalRequestLab").on("shown.bs.modal", function () {
    loadPasien();
    $("#pasien_id").select2({
      placeholder: "-- Pilih Pasien --",
      allowClear: true,
      width: "100%",
      dropdownParent: $("#modalRequestLab"),
    });
  });

  // change pasien
  $(document).on("change", "#pasien_id", function () {
    const selected = $(this).find(":selected");

    $("#nomor_rm").val(selected.data("nomor_rm") || "");
    $("#tanggal_lahir").val(selected.data("tanggal_lahir") || "");
    $("#nomor_visit").val(selected.data("nomor_visit") || "");
  });
});

// ================= LOAD PASIEN =================
function loadPasien() {
  $.ajax({
    url: "../api/master_pasien",
    type: "GET",
    dataType: "json",
    success: function (res) {
      let html = `<option value="">-- Pilih Pasien --</option>`;

      res.forEach((d) => {
        html += `
                    <option value="${d.id}"
                            data-nomor_rm="${d.nomor_rm}"
                            data-tanggal_lahir="${d.tanggal_lahir}"
                            data-nomor_visit="${d.nomor_visit}">
                        ${d.nama_pasien} | RM ${d.nomor_rm}
                    </option>
                `;
      });

      $("#pasien_id").html(html).trigger("change");
    },
  });
}
