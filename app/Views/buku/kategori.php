<!-- Begin Page Content -->
<div class="container-fluid">
    <?= session()->getFlashdata('pesan'); ?>
    <div class="row">
        <div class="col-lg-3">
            <?php if (isset($validation)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $validation->listErrors(); ?>
            </div>
            <?php } ?>
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#kategoriBaruModal">
                <i class="fas fa-file-alt"></i> Tambah Kategori
            </a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Pilihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $a = 1; foreach ($kategori as $k) { ?>
                    <tr>
                        <th scope="row"><?= $a++; ?></th>
                        <td><?= isset($k['nama_kategori']) ? $k['nama_kategori'] : ''; ?></td>
                        <td>
                            <?php if (isset($k['id_kategori'])) { ?>
                            <a href="<?= base_url('buku/ubahBuku/') . $k['id_kategori']; ?>" class="badge badge-info" data-toggle="modal" data-target="#ubahKategoriModal">
                                <i class="fas fa-edit"></i> Ubah
                            </a>
                            <a href="<?= base_url('buku/hapusbuku/') . $k['id_kategori']; ?>" class="badge badge-danger"
                                onclick="return confirm('Kamu yakin akan menghapus <?= $judul . ' ' . (isset($k['nama_kategori']) ? $k['nama_kategori'] : ''); ?>?');">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal Tambah kategori baru-->
<div class="modal fade" id="kategoriBaruModal" tabindex="-1" role="dialog" aria-labelledby="kategoriBaruModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategoriBaruModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('buku/tambahKategori'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <select name="kategori" class="form-control form-control-user">
                            <option value="">Pilih Kategori</option>
                            <?php $k = ['Sains', 'Hobby', 'Komputer', 'Komunikasi', 'Hukum', 'Agama', 'Populer', 'Bahasa', 'Komik']; ?>
                            <?php foreach ($k as $kategori) { ?>
                            <option value="<?= $kategori; ?>"><?= $kategori; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-ban"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal Tambah Kategori -->