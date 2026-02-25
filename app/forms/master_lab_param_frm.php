<input type="hidden" id="id" name="id">
<input type="hidden" name="kode" value="<?php echo $_GET['kode']; ?>" id="kode">
<div class="row">
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Parameter</label>
         <input type="text" class="form-control" id="assemen" name="assemen" required>
      </div>
   </div>
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Display LIS</label>
         <input type="text" class="form-control" id="ass_alat" required name="ass_alat">
      </div>
   </div>
</div>
<div class="row">
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Urutan</label>
         <input type="number" class="form-control" id="urutan" required name="urutan">
      </div>
   </div>
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Satuan</label>
         <input type="text" class="form-control" id="satuan" required name="satuan">
      </div>
   </div>
</div>
<div class="row">
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Minimum</label>
         <input type="text" class="form-control" id="minimum" required name="minimum">
      </div>
   </div>
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Maksimum</label>
         <input type="text" class="form-control" id="maksimum" required name="maksimum">
      </div>
   </div>
</div>
<div class="mb-3">
   <label class="form-label">Catatan</label>
   <textarea name="catatan" id="catatan" class="form-control" rows="5"></textarea>
</div>