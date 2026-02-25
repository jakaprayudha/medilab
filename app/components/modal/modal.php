<!-- Generic Modal Wrapper -->
<div class="modal fade" id="<?= $modalId ?? 'globalModal' ?>" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog <?= $size ?? '' ?>">
      <div class="modal-content">

         <div class="modal-header">
            <h5 class="modal-title"><?= $title ?? 'Form' ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <div class="modal-body">
            <?php
            // tampilkan konten form yang di-passing
            if (isset($content)) {
               require $content;
            }
            ?>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-primary" id="<?= $submitId ?? 'btnSubmit' ?>">Simpan</button>
         </div>

      </div>
   </div>
</div>