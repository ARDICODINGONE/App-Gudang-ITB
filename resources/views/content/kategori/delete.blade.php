<div class="modal fade" id="modalHapusKategori" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="formHapusKategori" action="" method="POST">
        @csrf
        @method('DELETE') <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus data kategori ini?</p>
          <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
