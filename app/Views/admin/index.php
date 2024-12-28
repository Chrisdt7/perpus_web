<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-
scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>pustaka-booking | <?= $judul; ?></title>
    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- row ux-->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2 bg-primary">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-md font-weight-bold text-white text-uppercase mb-1">Jumlah Anggota
                                    </div>
                                    <div class="h1 mb-0 font-weight-bold text-white">
                                        <?= $this->ModelUser->getUserWhere(['role_id' => 1])->num_rows(); ?></div>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('user/anggota'); ?>"><i
                                            class="fas fa-users fa-3x text-warning"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2 bg-warning">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-md font-weight-bold text-white text-uppercase mb-1">Stok Buku
                                        Terdaftar</div>
                                    <div class="h1 mb-0 font-weight-bold text-white">
                                        <?php
                                            $where = ['stok != 0'];
                                            $totalstok = $this->ModelBuku->total('stok', $where);
                                            echo $totalstok;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('buku'); ?>"><i class="fas fa-book fa-3x text-primary"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2 bg-danger">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-md font-weight-bold text-white text-uppercase mb-1">Buku yang
                                        dipinjam</div>
                                    <div class="h1 mb-0 font-weight-bold text-white">
                                        <?php
                                            $where = ['dipinjam != 0'];
                                            $totaldipinjam = $this->ModelBuku->total('dipinjam', $where);
                                            echo $totaldipinjam;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('user'); ?>"><i
                                            class="fas fa-user-tag fa-3x text-success"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2 bg-success">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-md font-weight-bold text-white text-uppercase mb-1">Buku yang
                                        dibooking</div>
                                    <div class="h1 mb-0 font-weight-bold text-white">
                                        <?php
                                            $where = ['dibooking !=0'];
                                            $totaldibooking = $this->ModelBuku->total('dibooking', $where);
                                            echo $totaldibooking;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('user'); ?>"><i
                                            class="fas fa-shopping-cart fa-3x text-danger"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row ux-->
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- row table-->
            <div class="row">
                <div class="table-responsive table-bordered col-sm-5 ml-auto mr-auto mt-2">
                    <div class="page-header">
                        <span class="fas fa-users text-primary mt-2 "> Data User</span>
                        <a class="text-danger" href="<?php echo base_url('user/data_user'); ?>"><i
                                class="fas fa-search mt-2 float-right"> Tampilkan</i></a>
                    </div>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Anggota</th>
                                <th>Email</th>
                                <th>Role ID</th>
                                <th>Aktif</th>
                                <th>Member Sejak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($anggota as $a) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $a['nama']; ?></td>
                                <td><?= $a['email']; ?></td>
                                <td><?= $a['role_id']; ?></td>
                                <td><?= $a['is_active']; ?></td>
                                <td><?= date('Y', $a['tanggal_input']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive table-bordered col-sm-5 ml-auto mr-auto mt-2">
                    <div class="page-header">
                        <span class="fas fa-book text-warning mt-2"> Data Buku</span>
                        <a href="<?= base_url('buku'); ?>"><i class="fas fa-search text-primary mt-2 float-right">
                                Tampilkan</i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mt-3" id="table-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul Buku</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th>Tahun Terbit</th>
                                    <th>ISBN</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1;
                                    foreach ($buku as $b) {
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $b['judul_buku']; ?></td>
                                    <td><?= $b['pengarang']; ?></td>
                                    <td><?= $b['penerbit']; ?></td>
                                    <td><?= $b['tahun_terbit']; ?></td>
                                    <td><?= $b['isbn']; ?></td>
                                    <td><?= $b['stok']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end of row table-->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Web Programming Univ. BSI with
                    Bootstrap SB Admin 2 <?= date('Y'); ?></span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin
                        mau keluar?</h5>
                    <button class="close" type="button" data- dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika
                    kamu yakin sudah selesai.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= base_url('autentifikasi/logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script>
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label ').addClass("selected").html(fileName);
            }); $(document).ready(function() {
            $("#table-datatable").dataTable();
        }); $('.alert-message').alert().delay(3500).slideUp('slow');
    </script>
</body>

</html>