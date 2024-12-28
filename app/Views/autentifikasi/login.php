
<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Halaman Login!!</h1>
                                </div>
                                <?= view('CustomValidation/Custom_error_template') ?>
                                <?php if (session()->getFlashdata('pesan')) : ?>
                                <?= session()->getFlashdata('pesan') ?>
                                <?php endif; ?>
                                <form class="user" method="post" action="<?= base_url('autentifikasi'); ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user"
                                            value="<?= old('email'); ?>" id="email" placeholder="Masukkan Alamat Email"
                                            name="email">
                                        <?= service('validation')->showError('email', 'list'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password"
                                            placeholder="Password" name="password">
                                        <?= service('validation')->showError('password', 'list'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Masuk
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('autentifikasi/lupaPassword'); ?>">Lupa
                                        Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('autentifikasi/registrasi'); ?>">Daftar
                                        Member!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>