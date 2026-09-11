                    <div class="pcoded-content">
                        <div class="pcoded-inner-content" style="padding-top: 20px;">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5><i class="fa fa-bookmark text-c-blue m-r-10"></i><?= isset($page_title) ? $page_title : 'Data' ?></h5>
                                                        <span><?= isset($card_subtitle) ? $card_subtitle : 'Informasi modul ' . (isset($page_title) ? $page_title : '') ?></span>
                                                        <div class="card-header-right">
                                                            <ul class="list-unstyled card-option">
                                                                <li><i class="fa fa-wrench open-card-option"></i></li>
                                                                <li><i class="fa fa-window-maximize full-card"></i></li>
                                                                <li><i class="fa fa-minus minimize-card"></i></li>
                                                                <li><i class="fa fa-refresh reload-card"></i></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                            <i class="fa fa-info-circle m-r-5"></i> Modul <strong><?= isset($page_title) ? $page_title : 'Halaman' ?></strong> siap dikembangkan untuk integrasi data akademik.
                                                        </div>
                                                        <p class="text-muted">
                                                            Halaman ini telah terhubung dengan arsitektur CodeIgniter 3 (Controller & View). Anda dapat menambahkan tabel data, formulir, atau query database ke dalam tampilan ini.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
