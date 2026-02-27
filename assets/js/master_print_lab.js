// ==========================================================
//   DATA TABLE
// ==========================================================

const tableLab = $("#LabTable").DataTable({
  pageLength: 10,
  processing: true,
  responsive: true,
  ajax: {
    dataType: "json",
    url: "../api/master_print_lab",
    type: "GET",
    dataSrc: "",
  },
  columns: [
    { data: null },
    { data: "kode" },
    { data: "assemen" },
    { data: "status" },
    { data: "id" },
  ],
  columnDefs: [
    {
      targets: 0,
      render: (d, t, r, m) => m.row + 1,
    },

    // ================= TEMPLATE SELECT =================
    {
      targets: 3,
      className: "text-center",
      render: function (data, type, row) {
        const tpl = row.template ?? 0;

        const t0 = tpl == 0 ? "selected" : "";
        const t1 = tpl == 1 ? "selected" : "";
        const t2 = tpl == 2 ? "selected" : "";
        const t3 = tpl == 3 ? "selected" : "";
        const t4 = tpl == 4 ? "selected" : "";
        const t5 = tpl == 5 ? "selected" : "";
        const t6 = tpl == 6 ? "selected" : "";

        return `
          <select class="form-select form-select-sm template-select"
                  data-id="${row.id}"
                  style="width:180px;margin:auto">

            <option value="0" ${t0}>🧩 Default SIMRS</option>
            <option value="1" ${t1}>Template 1</option>
            <option value="2" ${t2}>Template 2</option>
            <option value="3" ${t3}>Template 3</option>
            <option value="4" ${t4}>Template 4</option>
            <option value="5" ${t5}>Template 5</option>
            <option value="6" ${t6}>Template 6</option>

          </select>
        `;
      },
    },

    // ================= PREVIEW BUTTON =================
    {
      targets: 4,
      className: "text-center",
      render: function (id, type, row) {
        return `
          <button class="btn btn-info btn-sm btn-edit action-btn"
                  data-id="${id}">
            <i class="bi bi-folder"></i> Preview
          </button>
        `;
      },
    },
  ],
});

// ==========================================================
//   PREVIEW MODAL
// ==========================================================

$("#LabTable").on("click", ".btn-edit", function () {
  const id = $(this).data("id");

  $("#previewContent").html("Loading...");

  $.ajax({
    url: "../api/master_print_lab",
    type: "GET",
    dataType: "json",
    data: { id: id },

    success: function (res) {
      // template list termasuk default
      let templates = [0, 1, 2, 3, 4, 5, 6];

      let imagesHtml = templates
        .map((num) => {
          let title = num == 0 ? "🧩 Default SIMRS" : `Template ${num}`;

          let img =
            num == 0
              ? "../assets/template/default.png"
              : `../assets/template/template${num}.png`;

          return `
            <div class="col-md-4 mb-3 text-center">
              <div class="border rounded p-2">
                <div class="fw-bold mb-2">${title}</div>
                <img src="${img}"
                     class="img-fluid"
                     style="max-height:250px; object-fit:contain;">
              </div>
            </div>
          `;
        })
        .join("");

      let html = `
        <div class="p-3 border rounded">

          <h5 class="text-center mb-3">${res.assemen}</h5>

          <table class="table table-sm table-bordered">
            <tr>
              <td>Kode</td>
              <td>${res.kode}</td>
            </tr>
          </table>

          <div class="row mt-3">
            ${imagesHtml}
          </div>

        </div>
      `;

      $("#previewContent").html(html);

      const modal = new bootstrap.Modal(
        document.getElementById("modalPreviewPrint"),
      );

      modal.show();
    },
  });
});

// ==========================================================
//   UPDATE TEMPLATE
// ==========================================================

$(document).on("change", ".template-select", function () {
  const id = $(this).data("id");
  const template = $(this).val();

  $.ajax({
    url: "../api/choice_template_lab",
    type: "POST",
    dataType: "json",
    data: {
      id: id,
      template: template,
    },
    success: function (res) {
      if (res.success) {
        showToast("Template berhasil diupdate", "success");
      } else {
        showToast("Gagal update", "danger");
      }
    },
    error: function () {
      showToast("Server error", "danger");
    },
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
