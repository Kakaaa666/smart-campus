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

            function positionCollapsedSubmenu(parentLi, submenu) {
                if (!submenu) return;
                var rect = parentLi.getBoundingClientRect();
                var top = rect.top;
                var left = rect.right > 0 ? rect.right : 70;

                // Tambahkan judul menu induk sebagai header di panel melayang jika belum ada
                var menuTextEl = parentLi.querySelector(':scope > a .pcoded-mtext');
                var titleText = menuTextEl ? menuTextEl.textContent.trim() : '';
                var existingHeader = submenu.querySelector('.sc-collapsed-submenu-header');
                if (!existingHeader && titleText) {
                    var headerLi = document.createElement('li');
                    headerLi.className = 'sc-collapsed-submenu-header';
                    headerLi.textContent = titleText;
                    submenu.insertBefore(headerLi, submenu.firstChild);
                }

                // Hitung posisi awal
                submenu.style.position = 'fixed';
                submenu.style.left = left + 'px';
                submenu.style.top = top + 'px';
                submenu.style.display = 'block';

                // Cegah submenu meluap melewati bagian bawah layar
                var submenuHeight = submenu.offsetHeight || 220;
                var winHeight = window.innerHeight;
                if (top + submenuHeight > winHeight - 10) {
                    top = Math.max(10, winHeight - submenuHeight - 10);
                    submenu.style.top = top + 'px';
                }
            }

            function closeCollapsedSubmenus() {
                $(sidebarRoot).find('.pcoded-item > .pcoded-hasmenu.pcoded-trigger').each(function() {
                    $(this).removeClass('pcoded-trigger');
                    var sub = this.querySelector('.pcoded-submenu');
                    if (sub) {
                        sub.style.top = '';
                        sub.style.left = '';
                        sub.style.display = '';
                    }
                });
            }

            function setSidebarType(type) {
                sidebarRoot.setAttribute('vertical-nav-type', type);
                localStorage.setItem(storageKey, type);

                // Sidebar kecil hanya menampilkan ikon induk; tutup seluruh submenu.
                if (type === 'collapsed') {
                    closeCollapsedSubmenus();
                } else {
                    $(sidebarRoot).find('.pcoded-submenu').css({ position: '', top: '', left: '', display: '' });
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

            // Handler klik untuk menu dan trigger
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 769 || event.target.closest('#mobile-collapse')) return;

                var isCollapsed = sidebarRoot.getAttribute('vertical-nav-type') === 'collapsed';

                if (isCollapsed) {
                    var parentMenuLink = event.target.closest('.pcoded-navbar .pcoded-item > .pcoded-hasmenu > a');
                    if (parentMenuLink) {
                        event.preventDefault();
                        event.stopImmediatePropagation();

                        var parentMenu = parentMenuLink.parentElement;
                        var isCurrentlyOpen = $(parentMenu).hasClass('pcoded-trigger');

                        // Tutup submenu lain yang mungkin sedang terbuka
                        closeCollapsedSubmenus();

                        if (!isCurrentlyOpen) {
                            $(parentMenu).addClass('pcoded-trigger');
                            var submenu = parentMenu.querySelector('.pcoded-submenu');
                            if (submenu) {
                                positionCollapsedSubmenu(parentMenu, submenu);
                            }
                        }
                        return;
                    }

                    // Jika klik di luar submenu dan bukan parentMenuLink, tutup submenu yang terbuka
                    if (!event.target.closest('.pcoded-submenu')) {
                        closeCollapsedSubmenus();
                    }
                }

                if (event.target.closest('.pcoded-navbar > .sidebar_toggle, .pcoded-overlay-box, .menu-toggle a')) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            }, true);

            // Tutup flyout submenu jika tombol Escape ditekan
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && sidebarRoot.getAttribute('vertical-nav-type') === 'collapsed') {
                    closeCollapsedSubmenus();
                }
            });

            // Perbarui posisi submenu saat scrolling
            window.addEventListener('scroll', function() {
                if (sidebarRoot.getAttribute('vertical-nav-type') === 'collapsed') {
                    var activeTrigger = sidebarRoot.querySelector('.pcoded-item > .pcoded-hasmenu.pcoded-trigger');
                    if (activeTrigger) {
                        var sub = activeTrigger.querySelector('.pcoded-submenu');
                        if (sub) positionCollapsedSubmenu(activeTrigger, sub);
                    }
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
