// ==========================================================
//   DATA TABLE
// ==========================================================
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
    { data: "total_item" },
    { data: "id" },
  ],
  columnDefs: [
    {
      targets: 0,
      render: (d, t, r, m) => m.row + 1,
    },
    {
      targets: 3,
      className: "text-center",
      render: function (data, type, row) {
        return `<span class="badge bg-info">${data ?? 0} Parameter</span>`;
      },
    },
    {
      targets: 4,
      className: "text-center",
      render: function (id, type, row) {
        return `
      <button class="btn btn-primary btn-sm btn-detail action-btn"
              data-lab="${row.assemen}">
        <i class="bi bi-eye"></i> Calibration
      </button>
    `;
      },
    },
  ],
});

$("#LabTable").on("click", ".btn-detail", function () {
  const lab = $(this).data("lab");
  // redirect ke halaman parameter
  window.location.href = `analyzer_calibration_detail?lab=${lab}`;
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
