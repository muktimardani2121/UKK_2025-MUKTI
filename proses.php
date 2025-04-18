<?php
include 'koneksi.php';

if ($_POST['id']) {
    // Update data
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telpon = $_POST['telpon'];
    $sql = "UPDATE user SET nama='$nama', alamat='$alamat', telpon='$telpon' WHERE id='$id'";
    if (mysqli_query($koneksi, $sql)) {
        echo 'sukses';
    } else {
        echo 'gagal';
    }
} else {
    // Insert data
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telpon = $_POST['telpon'];
    $sql = "INSERT INTO user (nama, alamat, telpon) VALUES ('$nama', '$alamat', '$telpon')";
    if (mysqli_query($koneksi, $sql)) {
        echo 'sukses';
    } else {
        echo 'gagal';
    }
}
?>
