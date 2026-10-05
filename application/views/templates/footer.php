                </div> <!-- End .pcoded-wrapper -->
            </div> <!-- End .pcoded-main-container -->
        </div> <!-- End .pcoded-container -->
    </div> <!-- End #pcoded -->

    <!-- Required Jquery -->
    <script type="text/javascript" src="<?= base_url('assets/js/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui/jquery-ui.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/popper.js/popper.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!-- waves js -->
    <script src="<?= base_url('assets/pages/waves/js/waves.min.js') ?>"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-slimscroll/jquery.slimscroll.js') ?>"></script>
    <!-- slimscroll js -->
    <script src="<?= base_url('assets/js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
    <!-- menu js -->
    <script src="<?= base_url('assets/js/pcoded.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vertical/vertical-layout.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/script.js') ?>"></script>
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
