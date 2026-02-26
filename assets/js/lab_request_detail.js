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
