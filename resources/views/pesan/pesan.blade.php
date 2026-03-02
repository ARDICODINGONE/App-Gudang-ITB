@if($isApprover && $pengajuan->status === 'pending')
<div class="modal fade" id="approvalDecisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalDecisionTitle">Pesan untuk Pengaju</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2" id="approvalDecisionHint">
                    Tambahkan pesan yang akan dikirim ke pengaju.
                </p>
                <textarea id="approvalDecisionMessage" class="form-control" rows="4" placeholder="Tulis pesan untuk pengaju (opsional)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="approvalDecisionSubmitBtn" onclick="submitDecisionWithMessage()">Kirim</button>
            </div>
        </div>
    </div>
</div>

<script>
let decisionAction = null;
let decisionPengajuanId = null;

function hasAnyApprovedItem(pengajuanId) {
    const form = document.getElementById('approval-form-' + pengajuanId);
    if (!form) return false;
    const inputs = form.querySelectorAll('input[name^="approved"]');
    let hasApproved = false;

    inputs.forEach(input => {
        const value = parseInt(input.value) || 0;
        if (value > 0) {
            hasApproved = true;
        }
    });

    return hasApproved;
}

function submitApproval(pengajuanId, message) {
    const form = document.getElementById('approval-form-' + pengajuanId);
    if (!form) return;

    let messageInput = form.querySelector('input[name="approval_message"]');
    if (!messageInput) {
        messageInput = document.createElement('input');
        messageInput.type = 'hidden';
        messageInput.name = 'approval_message';
        form.appendChild(messageInput);
    }
    messageInput.value = message || '';

    form.submit();
}

function openDecisionModal(action, pengajuanId) {
    decisionAction = action;
    decisionPengajuanId = pengajuanId;

    const title = document.getElementById('approvalDecisionTitle');
    const hint = document.getElementById('approvalDecisionHint');
    const submitBtn = document.getElementById('approvalDecisionSubmitBtn');
    const textarea = document.getElementById('approvalDecisionMessage');

    if (!title || !hint || !submitBtn || !textarea) return;

    textarea.value = '';

    if (action === 'approve') {
        if (!hasAnyApprovedItem(pengajuanId)) {
            alert('Minimal ada 1 barang yang harus disetujui atau tolak semua pengajuan.');
            return;
        }
        title.textContent = 'Setujui Pengajuan';
        hint.textContent = 'Tambahkan pesan yang akan dikirim ke pengaju (opsional).';
        submitBtn.textContent = 'Setujui & Kirim Pesan';
        submitBtn.classList.remove('btn-danger');
        submitBtn.classList.add('btn-success');
    } else {
        title.textContent = 'Tolak Pengajuan';
        hint.textContent = 'Tambahkan alasan/pesan yang akan dikirim ke pengaju.';
        submitBtn.textContent = 'Tolak & Kirim Pesan';
        submitBtn.classList.remove('btn-success');
        submitBtn.classList.add('btn-danger');
    }

    const modalEl = document.getElementById('approvalDecisionModal');
    const myModal = new bootstrap.Modal(modalEl);
    myModal.show();
}

function submitDecisionWithMessage() {
    const textarea = document.getElementById('approvalDecisionMessage');
    const message = textarea ? textarea.value.trim() : '';

    if (decisionAction === 'approve') {
        submitApproval(decisionPengajuanId, message);
        return;
    }

    if (decisionAction === 'reject') {
        askRejectAll(decisionPengajuanId, message);
    }
}

function askRejectAll(pengajuanId, message) {
    if (!confirm('Yakin ingin menolak semua item pada pengajuan ini?')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("pengajuan.reject", ":id") }}'.replace(':id', pengajuanId);
    form.innerHTML = `
        {{ csrf_field() }}
        <input type="hidden" name="note" value="">
        <input type="hidden" name="approval_message" value="">
    `;
    form.querySelector('input[name="note"]').value = message || '';
    form.querySelector('input[name="approval_message"]').value = message || '';
    document.body.appendChild(form);
    form.submit();
}
</script>
@endif
