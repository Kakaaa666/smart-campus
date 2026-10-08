                </div> <!-- End .pcoded-wrapper -->
            </div> <!-- End .pcoded-main-container -->
        </div> <!-- End .pcoded-container -->
    </div> <!-- End #pcoded -->

    <!-- Required Jquery -->
    <script type="text/javascript" src="<?= base_url('assets/js/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui/jquery-ui.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/popper.js/popper.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/smart-campus-dialogs.js?v=' . time()) ?>"></script>
    <!-- waves js -->
    <script src="<?= base_url('assets/pages/waves/js/waves.min.js') ?>"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-slimscroll/jquery.slimscroll.js') ?>"></script>
    <!-- slimscroll js -->
    <script src="<?= base_url('assets/js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
    <!-- menu js -->
    <script src="<?= base_url('assets/js/pcoded.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vertical/vertical-layout.js?v=' . filemtime(FCPATH . 'assets/js/vertical/vertical-layout.js')) ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/script.js') ?>"></script>
    <script>
        // Desktop: ukuran sidebar disimpan antarhalaman dan hanya diubah oleh hamburger.
        // Karena klik menu memuat halaman baru, localStorage mencegah sidebar kembali kecil.
        $(function() {
            var sidebarRoot = document.getElementById('pcoded');
            var menuButton = document.getElementById('mobile-collapse');
            var storageKey = 'smart-campus-sidebar-size';
            if (!sidebarRoot || !menuButton) return;

            function savedType() {
                return localStorage.getItem(storageKey) === 'expanded' ? 'expanded' : 'collapsed';
            }

            function setSidebarType(type) {
                sidebarRoot.setAttribute('vertical-nav-type', type);
                localStorage.setItem(storageKey, type);

                // Sidebar kecil hanya menampilkan ikon induk; tutup seluruh submenu.
                if (type === 'collapsed') {
                    $(sidebarRoot).find('.pcoded-hasmenu').removeClass('pcoded-trigger');
                }
            }

            // Jalankan setelah plugin PCoded selesai memasang konfigurasi awalnya.
            setSidebarType(savedType());

            // Tangani tombol ini sebelum handler plugin, lalu ubah status sendiri.
            menuButton.addEventListener('click', function(event) {
                if (window.innerWidth < 769) return;

                event.preventDefault();
                event.stopImmediatePropagation();
                setSidebarType(savedType() === 'expanded' ? 'collapsed' : 'expanded');
            }, true);

            // Semua trigger lain dari plugin tidak boleh mengubah ukuran sidebar.
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 769 || event.target.closest('#mobile-collapse')) return;

                // Mode ikon: buka/tutup submenu dari ikon induknya secara eksplisit.
                // Ini tidak mengubah ukuran sidebar dan tidak bergantung pada handler PCoded.
                var parentMenuLink = event.target.closest('.pcoded-navbar .pcoded-item > .pcoded-hasmenu > a');
                if (sidebarRoot.getAttribute('vertical-nav-type') === 'collapsed' && parentMenuLink) {
                    event.preventDefault();
                    event.stopImmediatePropagation();

                    var parentMenu = parentMenuLink.parentElement;
                    $(sidebarRoot).find('.pcoded-item > .pcoded-hasmenu')
                        .not(parentMenu)
                        .removeClass('pcoded-trigger');
                    $(parentMenu).toggleClass('pcoded-trigger');
                    return;
                }

                if (event.target.closest('.pcoded-navbar > .sidebar_toggle, .pcoded-overlay-box, .menu-toggle a')) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            }, true);

            // Pengaman jika plugin mencoba mengubah atribut sidebar dari interaksi lain.
            new MutationObserver(function() {
                if (window.innerWidth >= 769 && sidebarRoot.getAttribute('vertical-nav-type') !== savedType()) {
                    sidebarRoot.setAttribute('vertical-nav-type', savedType());
                }
            }).observe(sidebarRoot, { attributes: true, attributeFilter: ['vertical-nav-type'] });
        });
    </script>
    <script>
        // Memastikan preloader langsung hilang begitu DOM siap
        (function() {
            function removeLoader() {
                var loader = document.querySelector('.theme-loader');
                if (loader) {
                    loader.style.opacity = '0';
                    setTimeout(function() {
                        if (loader.parentNode) loader.parentNode.removeChild(loader);
                    }, 300);
                }
            }
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                setTimeout(removeLoader, 300);
            } else {
                window.addEventListener('DOMContentLoaded', removeLoader);
                window.addEventListener('load', removeLoader);
            }
            setTimeout(removeLoader, 1000); // Batas maksimal mutlak 1 detik
        })();
    </script>
</body>

</html>
