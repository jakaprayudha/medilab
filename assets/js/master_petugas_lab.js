// ==========================================================
//   DATA TABLE
// ==========================================================
const tableDokterPA = $("#PATable").DataTable({
  pageLength: 10,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/master_petugas_lab",
    type: "GET",
    dataSrc: "",
  },

  columns: [
    { data: null }, // 0 No
    { data: "fullname" },
    { data: "username" },
    { data: "create_at" },
    { data: "status_user" },
    { data: "id" }, // 4 Action
  ],

  columnDefs: [
    /* ================= NO ================= */
    {
      targets: 0,
      className: "text-center",
      render: (d, t, r, m) => m.row + 1,
    },

    /* ================= STATUS TOGGLE ================= */
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

    /* ================= ACTION ================= */
    {
      targets: 5,
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
$("#PATable").on("change", ".toggle-status", function () {
  const id = $(this).data("id");
  const status = $(this).is(":checked") ? 1 : 0;
  const el = $(this);

  $.ajax({
    url: "../api/master_petugas_lab",
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
$("#btnSaveMasterPetugasLab").on("click", function () {
  const id = $("#id").val();
  const mode = id ? "update" : "create";

  $.post(
    "../api/master_petugas_lab",
    {
      mode,
      id,
      fullname: $("#fullname").val(),
      username: $("#username").val(),
      password: $("#password").val(),
    },
    (res) => {
      showToast(res.message, "success");
      $("#modalMasterPetugasLab").modal("hide");
      tableDokterPA.ajax.reload(null, false);
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
    "../api/master_petugas_lab",
    { id: id },
    function (res) {
      $("#id").val(res.id);
      $("#fullname").val(res.fullname);
      $("#username").val(res.username);
      $("#password").val(res.password);

      $("#modalMasterPetugasLab").modal("show");
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
      "../api/master_petugas_lab",
      { mode: "delete", id: id },
      function (res) {
        showToast(res.message, "warning");
        tableDokterPA.ajax.reload(null, false);
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
