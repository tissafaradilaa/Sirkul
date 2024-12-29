<?php
include 'D:/Laragon/www/perpustakaan-main/inc/koneksi.php';


if (isset($_POST['Simpan'])) {
    $id_sk = $_POST['id_sk'];
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $petugas_pinjam = $_POST['petugas_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];

    // Query untuk menyimpan data sirkulasi
    $query_simpan = mysqli_query($koneksi, "INSERT INTO tb_sirkulasi (id_sk, id_anggota, id_buku, tgl_pinjam, tgl_kembali, petugas_pinjam, status)
                                             VALUES ('$id_sk', '$id_anggota', '$id_buku', '$tgl_pinjam', '$tgl_kembali', '$petugas_pinjam', 'PIN')");

    if ($query_simpan) {
        echo "<script>
                Swal.fire({title: 'Tambah Data Berhasil', icon: 'success', confirmButtonText: 'OK'})
                .then((result) => {
                    if (result.value) {
                        window.location = 'index.php?page=data_sirkul';
                    }
                })
              </script>";
    } else {
        echo "<script>
                Swal.fire({title: 'Tambah Data Gagal', icon: 'error', confirmButtonText: 'OK'})
                .then((result) => {
                    if (result.value) {
                        window.location = 'index.php?page=add_sirkul';
                    }
                })
              </script>";
    }
}
?>
