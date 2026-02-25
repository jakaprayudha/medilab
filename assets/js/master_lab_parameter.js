function shortText(text, max = 25) {
  if (!text) return "-";
  if (text.length <= max) return text;

  return `
    <span class="text-truncate d-inline-block" style="max-width:150px">
      ${text.substring(0, max)}...
    </span>
    <a href="#" class="text-primary btn-detail-text ms-1"
       data-text="${encodeURIComponent(text)}">
       selengkapnya
    </a>
  `;
}
// ==========================================================
//   DATA TABLE
// ==========================================================
const urlParams = new URLSearchParams(window.location.search);
const kode = urlParams.get("kode");
const tableLab = $("#LabTable").DataTable({
  pageLength: 10,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/master_lab_parameter",
    type: "GET",

    data: function (d) {
      d.kode = kode;
    },
    dataSrc: "",
  },
  columns: [
    { data: null }, // 0 No
    { data: "urutan" }, // 1
    { data: "assemen" }, // 2
    { data: "ass_alat" }, // 3
    { data: "satuan" }, // 4
    { data: "minimum" }, // 5
    { data: "maksimum" }, // 6
    { data: "catatan" }, // 7
    { data: "status" }, // 8 Toggle
    { data: "id" }, // 9 Action
  ],

  columnDefs: [
    {
      targets: 0,
      className: "text-center",
      render: (d, t, r, m) => m.row + 1,
    },

    {
      targets: [3, 4],
      className: "text-center",
      render: (data) => data || "-",
    },

    {
      targets: 5,
      render: (data) => shortText(data, 20),
    },
    {
      targets: 6,
      render: (data) => shortText(data, 20),
    },
    {
      targets: 7,
      render: (data) => shortText(data, 30),
    },

    /* ================= STATUS TOGGLE ================= */
    {
      targets: 8,
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

    /* ================= ACTION ================= */
    {
      targets: 9,
      className: "text-center",
      render: function (id) {
        return `
          <button class="btn btn-warning btn-sm btn-edit"
                  data-id="${id}">
            <i class="bi bi-pencil-square"></i>
          </button>

          <button class="btn btn-danger btn-sm btn-delete"
                  data-id="${id}">
            <i class="bi bi-trash"></i>
          </button>
        `;
      },
    },
  ],
});

$("#LabTable").on("click", ".btn-detail-text", function (e) {
  e.preventDefault();

  const text = decodeURIComponent($(this).data("text"));

  Swal.fire({
    title: "Detail",
    html: `<div style="text-align:left">${text}</div>`,
    width: 500,
  });
});
$("#LabTable").on("change", ".toggle-status", function () {
  const id = $(this).data("id");
  const status = $(this).is(":checked") ? 1 : 0;
  const el = $(this);

  $.ajax({
    url: "../api/master_lab_parameter",
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
$("#btnSaveMasterLab").on("click", function () {
  const id = $("#id").val();
  const mode = id ? "update" : "create";

  $.post(
    "../api/master_lab_parameter",
    {
      mode,
      id,
      kode: $("#kode").val(),
      assemen: $("#assemen").val(),
      ass_alat: $("#ass_alat").val(),
      minimum: $("#minimum").val(),
      maksimum: $("#maksimum").val(),
      satuan: $("#satuan").val(),
      catatan: $("#catatan").val(),
      urutan: $("#urutan").val(),
    },
    (res) => {
      showToast(res.message, "success");
      $("#modalMasterLab").modal("hide");
      tableLab.ajax.reload(null, false);
    },
    "json",
  );
});

// ==========================================================
//  EDIT
// ==========================================================
$(document).on("click", ".btn-edit", function () {
  const id = $(this).data("id");

  $.get(
    "../api/master_lab_parameter",
    { id: id },
    function (res) {
      $("#id").val(res.id);
      $("#kode").val(res.kode);
      $("#assemen").val(res.assemen);
      $("#ass_alat").val(res.ass_alat);
      $("#minimum").val(res.minimum);
      $("#maksimum").val(res.maksimum);
      $("#satuan").val(res.satuan);
      $("#catatan").val(res.catatan);
      $("#urutan").val(res.urutan);

      $("#modalMasterLab").modal("show");
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
      "../api/master_lab_parameter",
      { mode: "delete", id: id },
      function (res) {
        showToast(res.message, "warning");
        tableLab.ajax.reload(null, false);
      },
      "json",
    );
  });
});

$("#LabTable").on("click", ".btn-detail", function () {
  const kode = $(this).data("kode");
  // redirect ke halaman parameter
  window.location.href = `master_lab_parameter?kode=${kode}`;
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
