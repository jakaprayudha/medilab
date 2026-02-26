<input type="hidden" id="id" name="id">
<div class="row">
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Tanggal</label>
         <input type="date" value="<?= date('Y-m-d') ?>" class="form-control" id="tanggal" name="tanggal" required>
      </div>
   </div>
   <div class="col">
      <div class="mb-3">
         <label class="form-label">Waktu</label>
         <input type="time" value="<?= date('H:i') ?>" class="form-control" id="waktu" name="waktu" required>
      </div>
   </div>
</div>
<div class="mb-3">
   <label class="form-label">Nama Pasien</label>
   <select class="form-select" id="pasien_id" name="pasien_id" required style="width:100%">
      <option value="">-- Pilih Pasien --</option>
   </select>
</div>

<div class="mb-3">
   <label class="form-label">Nomor RM</label>
   <input type="text" readonly class="form-control" id="nomor_rm" name="nomor_rm" required>
</div>

<div class="mb-3">
   <label class="form-label">Tanggal Lahir</label>
   <input type="text" readonly class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
</div>

<div class="mb-3">
   <label class="form-label">Nomor Visit</label>
   <input type="text" readonly class="form-control" id="nomor_visit" name="nomor_visit" required>
</div>