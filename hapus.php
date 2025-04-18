<?php
include 'koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM user WHERE id = $id";
if (mysqli_query($koneksi, $sql)) {
    header('Location: index.php?status=sukses_hapus');
} else {
    header('Location: index.php?status=gagal');
}
?>
