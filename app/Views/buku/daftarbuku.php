<?= session()->getFlashdata('pesan'); ?>

<div style="padding: 25px;">
    <div class="x_panel">
        <div class="x_content">
            <!-- Tampilkan semua produk -->
            <div class="row">
                <!-- looping products -->
                <?php foreach ($buku as $book): ?>
                    <div class="col-md-2 col-md-3">
                        <div class="thumbnail" style="height: 370px;">
                            <?php if (isset($book['pengarang'])): ?>
                                <img src="<?= base_url(); ?>assets/img/upload/<?= $book['image']; ?>" style="max-width:100%; max-height: 100%; height: 200px; width: 180px">
                                <div class="caption">
                                    <h5 style="min-height:30px;"><?= $book['pengarang']; ?></h5>
                                    <h5><?= $book['penerbit']; ?></h5>
                                    <h5><?= substr($book['tahun_terbit'], 0, 4); ?></h5>
                                    <p>
                                        <?php if ($book['stok'] < 1): ?>
                                            <i class='btn btn-outline-primary fas fw fa-shopping-cart'> Booking&nbsp;&nbsp;0</i>
                                        <?php else: ?>
                                            <a class='btn btn-outline-primary fas fw fa-shopping-cart' href='<?= base_url('booking/tambahBooking/' . $book['id']); ?>'> Booking</a>
                                        <?php endif; ?>
                                            <a class="btn btn-outline-warning fas fw fa-search" href="<?= base_url('home/detailBuku/' . $book['id']); ?>"> Detail</a>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- end looping -->
            </div>
        </div>
    </div>
</div>
