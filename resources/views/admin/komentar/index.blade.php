@extends('admin.layouts.app')

@section('title', 'Manajemen Komentar')

@section('styles')
<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
    }
    .status-badge {
        width: 80px;
    }
    .status-pending {
        background-color: #f6c23e;
    }
    .status-approved {
        background-color: #1cc88a;
    }
    .status-rejected {
        background-color: #e74a3b;
    }
    .article-title {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .comment-content {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .tab-filter {
        cursor: pointer;
    }
    .tab-filter.active {
        font-weight: bold;
        border-bottom: 2px solid #4e73df;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Komentar</h1>
        <div>
            <button type="button" class="btn btn-danger btn-sm" id="btnBatchAction" disabled data-bs-toggle="modal" data-bs-target="#batchActionModal">
                <i class="bi bi-gear"></i> Aksi Massal
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-start">
                <div class="tab-filter {{ $status == 'pending' ? 'active' : '' }} px-3 py-2 me-3">
                    <a href="{{ route('admin.komentar.index', ['status' => 'pending']) }}" class="text-decoration-none text-dark">
                        Menunggu <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                    </a>
                </div>
                <div class="tab-filter {{ $status == 'approved' ? 'active' : '' }} px-3 py-2 me-3">
                    <a href="{{ route('admin.komentar.index', ['status' => 'approved']) }}" class="text-decoration-none text-dark">
                        Disetujui <span class="badge bg-success">{{ $approvedCount }}</span>
                    </a>
                </div>
                <div class="tab-filter {{ $status == 'rejected' ? 'active' : '' }} px-3 py-2">
                    <a href="{{ route('admin.komentar.index', ['status' => 'rejected']) }}" class="text-decoration-none text-dark">
                        Ditolak <span class="badge bg-danger">{{ $rejectedCount }}</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($komentar->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">Tidak ada komentar dengan status {{ $status }}.</p>
            </div>
            @else
            <form id="batchForm" method="POST" action="{{ route('admin.komentar.batch-destroy') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                    </div>
                                </th>
                                <th>Artikel</th>
                                <th>Pengomentar</th>
                                <th>Komentar</th>
                                <th>Tanggal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($komentar as $comment)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input comment-checkbox" type="checkbox" name="selected_comments[]" value="{{ $comment->id_komentar }}">
                                    </div>
                                </td>
                                <td class="article-title" title="{{ $comment->nama_artikel }}">
                                    {{ $comment->nama_artikel }}
                                </td>
                                <td>{{ $comment->nama_pengomentar }}</td>
                                <td class="comment-content" title="{{ $comment->isi_komentar }}">
                                    {{ $comment->isi_komentar }}
                                </td>
                                <td>{{ $comment->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($status == 'pending')
                                    <button type="button" class="btn btn-success btn-circle"
                                            onclick="updateStatus({{ $comment->id_komentar }}, 'approved')" 
                                            title="Setujui">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-circle"
                                            onclick="updateStatus({{ $comment->id_komentar }}, 'rejected')" 
                                            title="Tolak">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @elseif($status == 'approved')
                                    <button type="button" class="btn btn-warning btn-circle"
                                            onclick="updateStatus({{ $comment->id_komentar }}, 'pending')" 
                                            title="Kembalikan ke Menunggu">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                    @elseif($status == 'rejected')
                                    <button type="button" class="btn btn-warning btn-circle"
                                            onclick="updateStatus({{ $comment->id_komentar }}, 'pending')" 
                                            title="Kembalikan ke Menunggu">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-secondary btn-circle"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal"
                                            data-article="{{ $comment->nama_artikel }}"
                                            data-author="{{ $comment->nama_pengomentar }}"
                                            data-content="{{ $comment->isi_komentar }}"
                                            data-date="{{ $comment->created_at->format('d M Y H:i') }}"
                                            title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-circle" 
                                            onclick="confirmDelete({{ $comment->id_komentar }})"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $komentar->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Komentar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold">Artikel:</label>
                    <div id="detailArticle"></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Pengirim:</label>
                    <div id="detailAuthor"></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Tanggal:</label>
                    <div id="detailDate"></div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Isi Komentar:</label>
                    <div id="detailContent" class="p-2 border rounded bg-light"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Batch Action Modal -->
<div class="modal fade" id="batchActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Pilih aksi yang ingin dilakukan pada <span id="selectedCount">0</span> komentar yang dipilih:</p>
                <div class="list-group">
                    @if($status == 'pending')
                    <button type="button" class="list-group-item list-group-item-action" onclick="batchAction('approve')">
                        <i class="bi bi-check-lg text-success me-2"></i> Setujui Semua
                    </button>
                    <button type="button" class="list-group-item list-group-item-action" onclick="batchAction('reject')">
                        <i class="bi bi-x-lg text-danger me-2"></i> Tolak Semua
                    </button>
                    @endif
                    <button type="button" class="list-group-item list-group-item-action" onclick="batchAction('delete')">
                        <i class="bi bi-trash text-danger me-2"></i> Hapus Semua
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Form -->
<form id="statusForm" method="POST" action="" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusValue">
</form>

<!-- Batch Status Update Form -->
<form id="batchStatusForm" method="POST" action="{{ route('admin.komentar.batch-status') }}" style="display: none;">
    @csrf
    <input type="hidden" name="batch_action" id="batchAction">
    <!-- Selected comments will be copied here -->
</form>
@endsection

@section('scripts')
<script>
    // Detail modal
    const detailModal = document.getElementById('detailModal');
    if (detailModal) {
        detailModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const article = button.getAttribute('data-article');
            const author = button.getAttribute('data-author');
            const content = button.getAttribute('data-content');
            const date = button.getAttribute('data-date');
            
            document.getElementById('detailArticle').textContent = article;
            document.getElementById('detailAuthor').textContent = author;
            document.getElementById('detailContent').textContent = content;
            document.getElementById('detailDate').textContent = date;
        });
    }
    
    // Update status function
    function updateStatus(id, status) {
        const form = document.getElementById('statusForm');
        form.action = `{{ url('admin/komentar') }}/${id}/status`;
        
        document.getElementById('statusValue').value = status;
        form.submit();
    }
    
    // Delete confirmation
    function confirmDelete(id) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/komentar') }}/${id}`;
        
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Batch actions
    function batchAction(action) {
        const batchForm = document.getElementById('batchForm');
        const batchStatusForm = document.getElementById('batchStatusForm');
        
        // Clone selected checkboxes to the correct form
        const selectedCheckboxes = document.querySelectorAll('.comment-checkbox:checked');
        
        if (action === 'delete') {
            batchForm.action = "{{ route('admin.komentar.batch-destroy') }}";
            batchForm.submit();
        } else {
            // Clear any previous hidden inputs
            const existingInputs = batchStatusForm.querySelectorAll('input[name="selected_comments[]"]');
            existingInputs.forEach(input => input.remove());
            
            // Add selected comments to the form
            selectedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_comments[]';
                input.value = checkbox.value;
                batchStatusForm.appendChild(input);
            });
            
            document.getElementById('batchAction').value = action;
            batchStatusForm.submit();
        }
    }
    
    // Check all functionality
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.comment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBatchButton();
    });
    
    // Individual checkbox change
    document.querySelectorAll('.comment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBatchButton);
    });
    
    // Update batch button state
    function updateBatchButton() {
        const selectedCheckboxes = document.querySelectorAll('.comment-checkbox:checked');
        const batchButton = document.getElementById('btnBatchAction');
        const selectedCount = document.getElementById('selectedCount');
        
        batchButton.disabled = selectedCheckboxes.length === 0;
        selectedCount.textContent = selectedCheckboxes.length;
    }
</script>
@endsection 