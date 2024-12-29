<?php
// kode 9 digit
$carikode = mysqli_query($koneksi, "SELECT id_sk FROM tb_sirkulasi ORDER BY id_sk DESC");
$datakode = mysqli_fetch_array($carikode);
$kode = $datakode['id_sk'];
$urut = substr($kode, 1, 3);
$tambah = (int) $urut + 1;

if (strlen($tambah) == 1) {
    $format = "S" . "00" . $tambah;
} elseif (strlen($tambah) == 2) {
    $format = "S" . "0" . $tambah;
} else {
    $format = "S" . $tambah;
}
?>

<section class="content-header">
    <h1>
        Sirkulasi
        <small>Buku</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Si Perpustakaan</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Peminjaman</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Id Sirkulasi</label>
                            <input type="text" name="id_sk" id="id_sk" class="form-control" value="<?php echo $format; ?>" readonly />
                        </div>

                        <div class="form-group">
                            <label>Nama Peminjam</label>
                            <select name="id_anggota" id="id_anggota" class="form-control select2" style="width: 100%;">
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                // ambil data dari database
                                $query = "SELECT * FROM tb_anggota";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                    echo "<option value='" . $row['id_anggota'] . "'>";
                                    echo $row['id_anggota'] . " - " . $row['nama'];
                                    echo "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Buku</label>
                            <select name="id_buku" id="id_buku" class="form-control select2" style="width: 100%;">
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                // ambil data dari database
                                $query = "SELECT * FROM tb_buku";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                    echo "<option value='" . $row['id_buku'] . "'>";
                                    echo $row['id_buku'] . " - " . $row['judul_buku'];
                                    echo "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tgl Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label>Petugas</label>
                            <select name="id_petugas_sirkulasi" id="id_petugas_sirkulasi" class="form-control select2" style="width: 100%;">
                                <option value="" selected="selected">-- Pilih --</option>
                                <?php
                                // ambil data dari database
                                $query = "SELECT * FROM tb_petugas_sirkulasi";
                                $hasil = mysqli_query($koneksi, $query);
                                while ($row = mysqli_fetch_array($hasil)) {
                                    echo "<option value='" . $row['id_petugas_sirkulasi'] . "'>";
                                    echo $row['nama_petugas']; // Menampilkan nama petugas
                                    echo "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                        <a href="?page=data_sirkul" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<?php
if (isset($_POST['Simpan'])) {
    // Validasi form untuk memastikan semua field wajib terisi
    if (empty($_POST['id_anggota']) || $_POST['id_anggota'] == '-- Pilih --' ||
        empty($_POST['id_buku']) || $_POST['id_buku'] == '-- Pilih --' ||
        empty($_POST['tgl_pinjam']) ||
        empty($_POST['id_petugas_sirkulasi']) || $_POST['id_petugas_sirkulasi'] == '-- Pilih --') {
        
        // Jika ada field kosong, tampilkan notifikasi gagal
        echo "<script>
        Swal.fire({title: 'Tambah Data Gagal', text: 'Semua field harus diisi dengan benar.', icon: 'error', confirmButtonText: 'OK'}).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_sirkul';
            }
        })</script>";
        exit;
    }

    // menangkap post tgl pinjam
    $tgl_p = $_POST['tgl_pinjam'];
    // membuat tgl kembali
    $tgl_k = date('Y-m-d', strtotime('+7 days', strtotime($tgl_p)));
    $tgl_hk = date('Y-m-d');
    
    // Query untuk menyimpan data
    $sql_simpan = "INSERT INTO tb_sirkulasi (id_sk, id_buku, id_anggota, tgl_pinjam, status, tgl_kembali, tgl_dikembalikan, id_petugas_sirkulasi) 
    VALUES (
        '" . $_POST['id_sk'] . "',
        '" . $_POST['id_buku'] . "',
        '" . $_POST['id_anggota'] . "',
        '" . $_POST['tgl_pinjam'] . "',
        'PIN',
        '" . $tgl_k . "',
        '" . $tgl_hk . "',
        '" . $_POST['id_petugas_sirkulasi'] . "'
    );";

    // Eksekusi query
    $query_simpan = mysqli_query($koneksi, $sql_simpan);
    mysqli_close($koneksi);

    if ($query_simpan) {
        echo "<script>
        Swal.fire({title: 'Tambah Data Berhasil', text: '', icon: 'success', confirmButtonText: 'OK'}).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Tambah Data Gagal', text: '', icon: 'error', confirmButtonText: 'OK'}).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_sirkul';
            }
        })</script>";
    }
}
?>
