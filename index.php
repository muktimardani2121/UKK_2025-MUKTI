<?php
include 'koneksi.php';
$sql = "SELECT * FROM user";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optional for icons) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Manajemen User</a>
    </div>
</nav>

<!-- Content -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Daftar Pengguna</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm" id="btnTambah">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </button>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (isset($_GET['status'])): ?>
        <?php
            $status = $_GET['status'];
            $msg = '';
            $alertClass = 'success';

            switch ($status) {
                case 'sukses_tambah':
                    $msg = '✅ Data berhasil ditambahkan!';
                    break;
                case 'sukses_edit':
                    $msg = '✏️ Data berhasil diupdate!';
                    break;
                case 'sukses_hapus':
                    $msg = '🗑️ Data berhasil dihapus!';
                    break;
                case 'gagal':
                    $msg = '❌ Terjadi kesalahan, silakan coba lagi.';
                    $alertClass = 'danger';
                    break;
            }
        ?>
        <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card Wrapper -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telpon</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_array($query)) { ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= $row['telpon'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit" data-id="<?= $row['id'] ?>">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm btn-hapus" data-id="<?= $row['id'] ?>">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Data -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Tambah Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="telpon" class="form-label">Telpon</label>
                        <input type="number" class="form-control" id="telpon" name="telpon" required>
                    </div>
                    <input type="hidden" id="id" name="id">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Show modal untuk tambah data
    $('#modalForm').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset(); // Reset form ketika modal ditutup
        $('#btnSubmit').text('Tambah Data'); // Ubah tombol jadi 'Tambah Data'
        $('#modalFormLabel').text('Tambah Data Pengguna'); // Judul Modal
    });

    // Open modal untuk edit data
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'get_data.php',
            method: 'GET',
            data: {id: id},
            success: function(response) {
                var data = JSON.parse(response);
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#telpon').val(data.telpon);
                $('#btnSubmit').text('Update Data');
                $('#modalFormLabel').text('Edit Data Pengguna');
                $('#modalForm').modal('show');
            }
        });
    });

    // Submit form via AJAX
    $('#formData').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'proses.php',  // URL untuk menambah atau edit data
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response == 'sukses') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data telah disimpan.',
                        icon: 'success',
                        confirmButtonText: 'Tutup'
                    }).then(() => {
                        $('#modalForm').modal('hide');
                        location.reload(); // Reload halaman setelah data berhasil disimpan
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan.',
                        icon: 'error',
                        confirmButtonText: 'Tutup'
                    });
                }
            }
        });
    });

    // Hapus data dengan konfirmasi
    $('.btn-hapus').on('click', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `hapus.php?id=${id}`;
            }
        });
    });
});
</script>

</body>
</html>
