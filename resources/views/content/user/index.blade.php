@extends('layouts/app')

@section('title', 'User - Index')

@section('content')
  @php
    $totalUsers = $users->count();
    $totalAdmin = $users->where('role', 'admin')->count();
    $totalPetugas = $users->where('role', 'petugas')->count();
    $totalApproval = $users->where('role', 'approval')->count();
  @endphp

  <style>
    .user-page {
      max-width: 1320px;
      margin: 1.4rem auto;
      padding: 1rem;
      border-radius: 20px;
      background: linear-gradient(180deg, #f6faff 0%, #ffffff 100%);
      box-shadow: 0 22px 44px -34px rgba(19, 54, 112, 0.38);
    }

    .user-shell {
      border: 1px solid #e2eafb;
      border-radius: 16px;
      overflow: hidden;
      background: #fff;
    }

    .user-header {
      padding: 1rem 1.25rem;
      background: linear-gradient(98deg, #0d6efd 0%, #2f84ff 72%, #5ca0ff 100%);
      color: #fff;
    }

    .user-subtitle {
      margin: 0.2rem 0 0;
      font-size: 0.9rem;
      opacity: 0.92;
    }

    .user-stat-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 0.75rem;
      margin-bottom: 1.1rem;
    }

    .user-stat-card {
      border: 1px solid #e6eefc;
      border-radius: 12px;
      padding: 0.75rem 0.85rem;
      background: #fbfdff;
    }

    .user-stat-label {
      font-size: 0.74rem;
      color: #6b7c99;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      margin-bottom: 0.25rem;
    }

    .user-stat-value {
      font-size: 1.25rem;
      line-height: 1;
      font-weight: 800;
      color: #0f172a;
    }

    .user-table thead th {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      color: #60738f;
      border-bottom-width: 1px;
      background: #f8fbff;
      white-space: nowrap;
    }

    .user-table tbody td {
      vertical-align: middle;
      color: #334155;
    }

    .user-table tbody tr:hover {
      background: #f8fbff;
    }

    .user-no {
      display: inline-block;
      min-width: 30px;
      text-align: center;
      padding: 0.24rem 0.5rem;
      border-radius: 999px;
      background: #e8f1ff;
      color: #0d6efd;
      font-weight: 700;
      font-size: 0.76rem;
    }

    .role-pill {
      display: inline-flex;
      align-items: center;
      padding: 0.3rem 0.62rem;
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.72rem;
      letter-spacing: 0.02em;
      text-transform: uppercase;
    }

    .role-admin {
      background: #fee2e2;
      color: #b91c1c;
    }

    .role-approval {
      background: #e0f2fe;
      color: #0369a1;
    }

    .role-petugas {
      background: #ecfdf5;
      color: #047857;
    }

    .role-user {
      background: #eef2ff;
      color: #4338ca;
    }

    .password-hash {
      max-width: 250px;
      display: inline-block;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      font-size: 0.76rem;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      color: #64748b;
    }

    .user-actions {
      display: flex;
      gap: 0.45rem;
      flex-wrap: wrap;
    }

    .user-empty {
      padding: 2rem 1rem;
      text-align: center;
      color: #7f90aa;
    }

    .user-empty i {
      font-size: 2rem;
      color: #9eb2d3;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    @media (max-width: 991.98px) {
      .user-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 767.98px) {
      .user-page {
        margin: 1rem 0.75rem;
        padding: 0.75rem;
      }

      .user-header {
        padding: 1rem;
      }
    }
  </style>

  <div class="user-page">
    <div class="user-shell">
      <div class="user-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <h5 class="mb-1 text-white">Daftar User</h5>
          <p class="user-subtitle">Kelola akun pengguna sistem agar akses tetap terstruktur dan aman.</p>
        </div>
        <div>
          <button type="button" class="btn btn-sm btn-light text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="ri ri-add-line me-1"></i>Tambah User
          </button>
        </div>
      </div>

      <div class="card-body p-3 p-md-4">
        @if (session('success'))
          <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        <div class="user-stat-grid">
          <div class="user-stat-card">
            <div class="user-stat-label">Total User</div>
            <div class="user-stat-value">{{ $totalUsers }}</div>
          </div>
          <div class="user-stat-card">
            <div class="user-stat-label">Admin</div>
            <div class="user-stat-value">{{ $totalAdmin }}</div>
          </div>
          <div class="user-stat-card">
            <div class="user-stat-label">Petugas</div>
            <div class="user-stat-value">{{ $totalPetugas }}</div>
          </div>
          <div class="user-stat-card">
            <div class="user-stat-label">Approval</div>
            <div class="user-stat-value">{{ $totalApproval }}</div>
          </div>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table table-hover user-table align-middle mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Password (Hash)</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($users as $user)
                @php
                  $roleClass = 'role-user';
                  if ($user->role === 'admin') {
                    $roleClass = 'role-admin';
                  } elseif ($user->role === 'approval') {
                    $roleClass = 'role-approval';
                  } elseif ($user->role === 'petugas') {
                    $roleClass = 'role-petugas';
                  }
                @endphp
                <tr>
                  <td><span class="user-no">{{ $loop->iteration }}</span></td>
                  <td class="fw-semibold">{{ $user->id }}</td>
                  <td class="fw-semibold">{{ $user->nama }}</td>
                  <td>{{ $user->username }}</td>
                  <td>
                    <span class="password-hash" title="{{ $user->password }}">{{ $user->password }}</span>
                  </td>
                  <td>
                    <span class="role-pill {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                  </td>
                  <td>
                    <div class="user-actions">
                      <a class="btn btn-sm btn-outline-primary" href="javascript:void(0);"
                        onclick='editUser(
                          @json(route("user.update", $user->id)),
                          @json($user->nama),
                          @json($user->username),
                          "",
                          @json($user->role)
                        )'>
                        <i class="ri-pencil-line me-1"></i>Edit
                      </a>

                      <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                        onclick="konfirmasiHapus('{{ route('user.destroy', $user->id) }}')">
                        <i class="ri-delete-bin-6-line me-1"></i>Delete
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7">
                    <div class="user-empty">
                      <i class="ri-user-search-line"></i>
                      <div class="fw-semibold">Belum ada data user</div>
                      <small>Tambahkan user baru agar akses sistem dapat digunakan sesuai role.</small>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @include('content.user.delete')
  @include('content.user.create')
  @include('content.user.update')

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
          var myModal = new bootstrap.Modal(document.getElementById('createUserModal'));
          myModal.show();
        @endif
      });
    </script>
  @endpush

  <script>
    function konfirmasiHapus(actionUrl) {
      var form = document.getElementById('formHapusUser');
      form.action = actionUrl;
      var myModal = new bootstrap.Modal(document.getElementById('modalHapusUser'));
      myModal.show();
    }

    function editUser(actionUrl, nama, username, password, role) {
      document.getElementById('formEditUser').action = actionUrl;
      document.getElementById('edit_nama').value = nama;
      document.getElementById('edit_username').value = username;
      document.getElementById('edit_password').value = password;
      document.getElementById('edit_role').value = role;

      var myModal = new bootstrap.Modal(document.getElementById('modalEditUser'));
      myModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      if (togglePassword) {
        togglePassword.addEventListener('click', function() {
          const passwordInput = document.getElementById('password');
          const icon = this.querySelector('i');

          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        });
      }

      const toggleEditPassword = document.getElementById('toggleEditPassword');
      if (toggleEditPassword) {
        toggleEditPassword.addEventListener('click', function() {
          const passwordInput = document.getElementById('edit_password');
          const icon = this.querySelector('i');

          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        });
      }
    });
  </script>
@endsection
