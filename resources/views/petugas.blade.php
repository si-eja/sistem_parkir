@extends('layout.dashboard-master')

@section('title', 'Data Petugas Parkir - Admin E-Parkir UNSIL')

@section('custom-css')
<style>
    :root {
        --unsil-green: #005a32;
        --unsil-green-dark: #003d22;
        --unsil-green-light: #e6f4ea;
        --unsil-yellow: #facc15;
        --unsil-yellow-hover: #eab308;
        --unsil-red: #dc2626;
    }

    .admin-hero-banner {
        background: linear-gradient(135deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
        color: #ffffff;
        border-radius: 1.25rem;
        padding: 1.75rem;
        border-bottom: 4px solid var(--unsil-yellow);
        box-shadow: 0 10px 25px -8px rgba(0, 61, 34, 0.25);
    }

    .admin-panel {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 14px rgba(0, 90, 50, 0.04);
    }

    .text-unsil { color: var(--unsil-green) !important; }
    .bg-unsil-soft { background-color: var(--unsil-green-light) !important; }

    .badge-unsil-yellow {
        background-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
    }

    .badge-unsil-green {
        background-color: var(--unsil-green-light);
        color: var(--unsil-green);
        border: 1px solid rgba(0, 90, 50, 0.25);
        font-weight: 700;
    }

    .btn-unsil-primary {
        background-color: var(--unsil-green);
        border-color: var(--unsil-green);
        color: #ffffff;
        transition: all 0.2s ease;
    }
    .btn-unsil-primary:hover {
        background-color: var(--unsil-green-dark);
        color: var(--unsil-yellow);
    }

    .btn-unsil-yellow {
        background-color: var(--unsil-yellow);
        border-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .btn-unsil-yellow:hover {
        background-color: var(--unsil-yellow-hover);
        color: #000000;
    }

    .btn-edit-soft {
        background-color: #fefce8;
        border: 1px solid #fde047;
        color: #854d0e;
        transition: all 0.2s ease;
    }
    .btn-edit-soft:hover {
        background-color: var(--unsil-yellow);
        color: #062818;
    }

    /* Sentuhan Merah Minimalis untuk Tombol Hapus */
    .btn-delete-minimal {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: var(--unsil-red);
        transition: all 0.2s ease;
    }
    .btn-delete-minimal:hover {
        background-color: var(--unsil-red);
        border-color: var(--unsil-red);
        color: #ffffff;
    }

    .avatar-circle-unsil {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--unsil-green);
        color: var(--unsil-yellow);
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .form-control:focus {
        border-color: var(--unsil-green);
        box-shadow: 0 0 0 0.2rem rgba(0, 90, 50, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0" data-aos="fade-up">

    <!-- 1. HEADER BANNER MANAJEMEN PETUGAS -->
    <div class="admin-hero-banner mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge badge-unsil-yellow rounded-pill px-3 py-1 mb-2 small">
                    <i class="bi bi-people-fill me-1"></i> Manajemen SDM Pos Lapangan
                </span>
                <h3 class="fw-bold text-white mb-1">Data Petugas Juru Parkir (Jukir)</h3>
                <p class="mb-0 small" style="color: #bbf7d0;">
                    Kelola akun petugas lapangan, perbarui kredensial akses POS, atau tambahkan petugas baru Universitas Siliwangi.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button type="button" class="btn btn-unsil-yellow btn-lg rounded-3 px-4 py-2.5 shadow-sm d-inline-flex align-items-center gap-2" 
                        data-bs-toggle="modal" data-bs-target="#modalTambahPetugas">
                    <i class="bi bi-person-plus-fill fs-5"></i>
                    <span>Tambah Petugas Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- VALIDASI ERROR FORM -->
    @if($errors->any())
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 small" 
             style="border-left: 4px solid #dc2626 !important;">
            <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan input:</strong>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2. TABEL DAFTAR PETUGAS PARKIR -->
    <div class="admin-panel" style="border-top: 4px solid var(--unsil-green);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-dark mb-0">Daftar Akun Petugas Aktif</h5>
                <span class="badge badge-unsil-green rounded-pill px-3 py-1.5 small">
                    {{ $petugas->count() }} Petugas
                </span>
            </div>

            <div style="min-width: 240px;">
                <input type="text" id="searchPetugas" class="form-control form-control-sm rounded-3" placeholder="🔍 Cari nama atau email petugas...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablePetugas">
                <thead style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
                    <tr class="small">
                        <th style="width: 50px;">NO</th>
                        <th>NAMA PETUGAS</th>
                        <th>EMAIL LOGIN</th>
                        <th>ROLE AKSES</th>
                        <th>TERDAFTAR SEJAK</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugas as $index => $p)
                        <tr>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle-unsil">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->name }}</div>
                                        <small class="text-muted">ID Petugas: <strong class="text-unsil">{{ $p->kode_petugas }}</strong></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-dark fw-medium">{{ $p->email }}</td>
                            <td>
                                <span class="badge badge-unsil-yellow px-3 py-1 rounded-pill small">
                                    <i class="bi bi-shield-check me-1"></i> {{ $p->role }}
                                </span>
                            </td>
                            <td class="small text-muted">
                                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <!-- Tombol Edit Petugas -->
                                    <button type="button" class="btn btn-edit-soft btn-sm rounded-3 fw-semibold px-3" 
                                            data-bs-toggle="modal" data-bs-target="#modalEditPetugas{{ $p->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>

                                    <!-- Tombol Hapus Petugas (Merah Minimalis) -->
                                    <form action="{{ route('admin.petugas.delete', $p->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus akun petugas {{ $p->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete-minimal btn-sm rounded-3 fw-semibold px-2.5" title="Hapus Petugas">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-person-x fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada data petugas juru parkir yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ==================== MODAL TAMBAH PETUGAS BARU ==================== -->
<div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white border-0" style="background-color: var(--unsil-green); border-bottom: 3px solid var(--unsil-yellow) !important;">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-person-plus-fill text-warning me-1"></i> Tambah Petugas Parkir Baru
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.petugas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Nama Lengkap Petugas</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Alamat Email Login</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="petugas@unsil.ac.id" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted">Password Akun</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-unsil-primary rounded-3 fw-semibold px-4">Simpan Petugas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL EDIT PETUGAS (LOOP DI LUAR AOS) ==================== -->
@foreach($petugas as $p)
    <div class="modal fade" id="modalEditPetugas{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header text-white border-0" style="background-color: var(--unsil-green); border-bottom: 3px solid var(--unsil-yellow) !important;">
                    <h6 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square text-warning me-1"></i> Edit Data Petugas — {{ $p->name }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.petugas.update', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Nama Lengkap Petugas</label>
                            <input type="text" name="name" class="form-control rounded-3 fw-semibold" value="{{ $p->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Alamat Email Login</label>
                            <input type="email" name="email" class="form-control rounded-3" value="{{ $p->email }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-muted">Password Baru <span class="fw-normal">(Kosongkan jika tidak diubah)</span></label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="Isi hanya jika ingin reset password">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-unsil-primary rounded-3 fw-semibold px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchPetugas');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('#tablePetugas tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection