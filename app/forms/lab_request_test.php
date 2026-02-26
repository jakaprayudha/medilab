<input type="hidden" id="id" name="id">
<input type="hidden" name="nomor_visit" id="nomor_visit" value="<?= $_GET['visit'] ?>">
<input type="hidden" name="nomor_lab" id="nomor_lab" value="<?= $_GET['no'] ?>">
<input type="hidden" name="nomor_rm" id="nomor_rm" value="<?= $_GET['rm'] ?>">
<div class="mb-3">
   <label class="form-label">Nama Pemeriksaan</label>
   <select class="form-select" id="pemeriksaan_id" name="pemeriksaan_id" required style="width:100%">
      <option value="">-- Pilih Pemeriksaan Lab --</option>
   </select>
</div>

<div class="mb-3">
   <label class="form-label">Kode Lab</label>
   <input type="text" readonly class="form-control" id="kode" name="kode" required>
</div>

<div class="mb-3">
   <label class="form-label">Basis Tarif</label>
   <input type="number" readonly class="form-control" id="tarif" name="tarif" required>
</div>

<div class="mb-3">
   <label class="form-label">Catatan</label>
   <textarea name="catatan" id="catatan" class="form-control" rows="5"></textarea>
</div>