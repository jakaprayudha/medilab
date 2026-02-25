// ==========================================================
//   DATA TABLE
// ==========================================================
function formatRupiah(angka) {
  if (!angka) return "Rp 0";
  return "Rp " + Number(angka).toLocaleString("id-ID");
}
const tableLab = $("#LabTable").DataTable({
  pageLength: 10,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/master_lab",
    type: "GET",
    dataSrc: "",
  },
  columns: [
    { data: null },
    { data: "kode" },
    { data: "assemen" },
    { data: "tarif" },
    { data: "status" },
    { data: "id" },
  ],
  columnDefs: [
    {
      targets: 0,
      render: (d, t, r, m) => m.row + 1,
    },
    {
      targets: 3,
      className: "text-end fw-semibold",
      render: function (data) {
        return formatRupiah(data);
      },
    },
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
    {
      targets: 5,
      className: "text-center",
      render: (id) => `
                <button class="btn btn-warning btn-sm btn-edit action-btn" data-id="${id}">
                    <i class="bi bi-pencil-square"></i> <span class="btn-text">Edit</span>
                </button>
                <button class="btn btn-danger btn-sm btn-delete action-btn" data-id="${id}">
                    <i class="bi bi-trash"></i> <span class="btn-text">Hapus</span>
                </button>
            `,
    },
  ],
});
$("#LabTable").on("change", ".toggle-status", function () {
  const id = $(this).data("id");
  const status = $(this).is(":checked") ? 1 : 0;
  const el = $(this);

  $.ajax({
    url: "../api/master_lab",
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
    "../api/master_lab",
    {
      mode,
      id,
      kode: $("#kode").val(),
      pemeriksaan: $("#pemeriksaan").val(),
      tarif: $("#tarif").val(),
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
    "../api/master_lab",
    { id: id },
    function (res) {
      $("#id").val(res.id);
      $("#kode").val(res.kode);
      $("#pemeriksaan").val(res.assemen);
      $("#tarif").val(res.tarif);

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
      "../api/master_lab",
      { mode: "delete", id: id },
      function (res) {
        showToast(res.message, "warning");
        tableLab.ajax.reload(null, false);
      },
      "json",
    );
  });
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
