@extends('layouts/app')

@section('title', 'User - Index')


@section('content')
  <div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
      <span>Daftar User</span>
      <div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal"><i
            class="icon-base ri ri-add-line me-1"></i>Tambah User</button>
      </div>
    </h5>

    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>ID</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Password</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($users as $user)
              <tr>
                <td>{{ $loop->iteration }}</td>
              <td>{{ $user->id }}</td>
              <td>{{ $user->nama }}</td>
              <td>{{ $user->username }}</td>
              {{-- PASSWORD (HASH) --}}
              <td style="max-width:250px; word-break: break-all;">{{ $user->password }}</td>
              <td>
                <span class="badge bg-info text-dark">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
                <td>
                  <div class="d-flex">
                    <a class="btn btn-sm btn-outline-primary me-1" href="javascript:void(0);"
                        onclick="editUser(
                          '{{ route('user.update', $user->id) }}',
                          '{{ $user->nama }}',
                          '{{ $user->username }}',
                          '{{ $user->password }}',
                          '{{ $user->role }}'
                          )">
                        <i class="ri-pencil-line me-1"></i>
                      Edit
                    </a>

                    <a class="btn btn-sm btn-outline-danger" href="javascript:void(0);"
                        onclick="konfirmasiHapus('{{ route('user.destroy', $user->id) }}')">
                        <i class="ri-delete-bin-6-line me-1"></i>
                      Delete
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
            @if ($users->isEmpty())
              <tr>
                <td colspan="5" class="text-center">Belum ada data user.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('content.user.delete')
  @include('content.user.create')
  @include('content.user.update')

  <!-- Create Modal -->
  

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
  </script>
@endsection
