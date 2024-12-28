<div class="container">
    <center>
        <table>
            <tr>
                <td>
                    <div class="table-responsive full-width">
                        <table class="table table-bordered table-striped table-hover" id="table-datatable">
                            <tr>
                                <th>No.</th>
                                <th>Buku</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Pilihan</th>
                            </tr>
                            <?php foreach ($temp as $key => $t) : ?>
                                <tr>
                                    <td><?= $key + 1; ?></td>
                                    <td>
                                        <img src="<?= base_url('assets/img/upload/' . $t['image']); ?>" class="rounded" alt="No Picture" width="10%">
                                    </td>
                                    <td><?= $t['penulis']; ?></td>
                                    <td><?= $t['penerbit']; ?></td>
                                    <td><?= substr($t['tahun_terbit'], 0, 4); ?></td>
                                    <td>
                                        <a href="<?= base_url('booking/hapusbooking/' . $t['id_buku']); ?>" onclick="return confirm('Yakin tidak Jadi Booking <?= $t['judul_buku']; ?>')">
                                            <i class="btn btn-sm btn-outline-danger fas fw fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url(); ?>"><span class="fas fw fa-play"></span> Lanjutkan Booking Buku</a>
                    <a class="btn btn-sm btn-outline-success" href="<?= base_url('booking/bookingSelesai/' . session()->get('id_user')); ?>"><span class="fas fw fa-stop"></span> Selesaikan Booking</a>
                </td>
            </tr>
        </table>
    </center>
</div>
