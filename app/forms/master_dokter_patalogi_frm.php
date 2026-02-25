<input type="hidden" id="id" name="id">
<div class="mb-3">
   <label class="form-label">Nama Dokter</label>
   <select class="form-select" id="dokter_id" name="dokter_id" required>
      <option value="">-- Pilih Dokter --</option>
   </select>
</div>
<div class="mb-3">
   <label class="form-label">No.SIP</label>
   <input type="text" class="form-control" id="no_sip" name="no_sip" required>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
   $(document).ready(function() {

      loadDokter();

      $("#dokter_id").on("change", function() {

         const sip = $(this).find(":selected").data("sip") || "";

         $("#no_sip").val(sip);

      });

   });


   function loadDokter() {

      $.ajax({
         url: "../api/master_dokter",
         type: "GET",
         dataType: "json",
         success: function(res) {

            let html = `<option value="">-- Pilih Dokter --</option>`;

            res.forEach(d => {
               html += `
               <option value="${d.nama}" data-sip="${d.sip}">
                  ${d.nama}
               </option>
            `;
            });

            $("#dokter_id").html(html);
         }
      });

   }
</script>