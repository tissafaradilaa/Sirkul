<section class="content-header">
    <h1>
        Sirkulasi
        <small>Buku</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="?page=add_sirkul" title="Tambah Data" class="btn btn-primary">
                <i class="glyphicon glyphicon-plus"></i> Tambah Data
            </a>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID SKL</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Denda</th>
                            <th>Kelola</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = $koneksi->query("
                            SELECT s.id_sk, b.judul_buku,
                                a.id_anggota, a.nama,
                                s.tgl_pinjam, s.tgl_kembali,
                                p.id_petugas_sirkulasi, p.nama_petugas
                            FROM tb_sirkulasi s
                            INNER JOIN tb_buku b ON s.id_buku = b.id_buku
                            INNER JOIN tb_anggota a ON s.id_anggota = a.id_anggota
                            LEFT JOIN tb_petugas_sirkulasi p ON s.id_petugas_sirkulasi = p.id_petugas_sirkulasi
                            WHERE s.status = 'PIN'
                            ORDER BY s.tgl_pinjam DESC
                        ");
                        while ($data = $sql->fetch_assoc()) {
                            $tgl_pinjam = date("d/M/Y", strtotime($data['tgl_pinjam']));
                            $tgl_kembali = date("d/M/Y", strtotime($data['tgl_kembali']));
                            
                            // Hitung denda
                            $u_denda = 1000;
                            $tgl1 = date("Y-m-d");
                            $tgl2 = $data['tgl_kembali'];
                            $pecah1 = explode("-", $tgl1);
                            $date1 = $pecah1[2];
                            $month1 = $pecah1[1];
                            $year1 = $pecah1[0];

                            $pecah2 = explode("-", $tgl2);
                            $date2 = $pecah2[2];
                            $month2 = $pecah2[1];
                            $year2 =  $pecah2[0];

                            $jd1 = GregorianToJD($month1, $date1, $year1);
                            $jd2 = GregorianToJD($month2, $date2, $year2);

                            $selisih = $jd1 - $jd2;
                            $denda = $selisih * $u_denda;
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data['id_sk']; ?></td>
                                <td><?php echo $data['judul_buku']; ?></td>
                                <td><?php echo $data['id_anggota']; ?> - <?php echo $data['nama']; ?></td>
                                <td><?php echo $tgl_pinjam; ?></td>
                                <td><?php echo $tgl_kembali; ?></td>
                                <td>
                                    <?php if ($selisih <= 0) { ?>
                                        <span class="label label-primary">Masa Peminjaman</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">
                                            Rp. <?= $denda ?>
                                        </span><br> Terlambat : <?= $selisih ?> Hari
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="?page=panjang&kode=<?php echo $data['id_sk']; ?>" onclick="return confirm('Perpanjang Data Ini ?')" title="Perpanjang" class="btn btn-success">
                                        <i class="glyphicon glyphicon-upload"></i>
                                    </a>
                                    <a href="?page=kembali&kode=<?php echo $data['id_sk']; ?>" onclick="return confirm('Kembalikan Buku Ini ?')" title="Kembalikan" class="btn btn-danger">
                                        <i class="glyphicon glyphicon-download"></i>
                                    </a>
                                </td>
                                <td>
                                    <?php echo isset($data['nama_petugas']) ? $data['nama_petugas'] : '<i>Data tidak tersedia</i>'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <h4> 
        *Note<br>
        Masa peminjaman buku adalah <span style="color:red; font-weight:bold;">7 hari</span> dari tanggal peminjaman.<br>
        Jika buku dikembalikan lebih dari masa peminjaman, maka akan dikenakan <span style="color:red; font-weight:bold;">denda</span><br>
        sebesar <span style="color:red; font-weight:bold;">Rp 1.000/hari</span>.
    </h4>
</section>

<?php
$query = "SELECT s.*, b.judul_buku, a.nama AS nama_peminjam, p.nama_petugas
          FROM tb_sirkulasi s
          JOIN tb_buku b ON s.id_buku = b.id_buku
          JOIN tb_anggota a ON s.id_anggota = a.id_anggota
          JOIN tb_petugas_sirkulasi p ON s.id_petugas_sirkulasi = p.id_petugas_sirkulasi
          ORDER BY s.tgl_pinjam DESC";

$hasil = mysqli_query($koneksi, $query);
?>
</table>
