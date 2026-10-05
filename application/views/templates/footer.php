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
        (function() {
            var layout = document.getElementById('pcoded');
            if (!layout) return;

            var content = layout.querySelector('.pcoded-content');
            var sidebar = layout.querySelector('.pcoded-navbar');
            if (!content || !sidebar) return;

            function syncSidebarOverlap() {
                var sidebarRect = sidebar.getBoundingClientRect();
                var contentRect = content.getBoundingClientRect();
                var sidebarStyle = window.getComputedStyle(sidebar);
                var contentStyle = window.getComputedStyle(content);
                var sidebarVisible = sidebarRect.width > 0 && sidebarRect.right > 0 && sidebarStyle.display !== 'none' && sidebarStyle.visibility !== 'hidden' && parseFloat(sidebarStyle.opacity) > 0.01;
                var appliedOffset = parseFloat(content.getAttribute('data-sidebar-applied-offset')) || 0;
                var baseLeft = contentRect.left - appliedOffset;
                var baseMargin = parseFloat(content.getAttribute('data-sidebar-base-margin'));

                if (isNaN(baseMargin)) {
                    baseMargin = parseFloat(contentStyle.marginLeft) || 0;
                    content.setAttribute('data-sidebar-base-margin', baseMargin);
                }

                var overlap = sidebarVisible ? Math.max(0, Math.ceil(sidebarRect.right - baseLeft)) : 0;

                if (overlap > 1) {
                    var offset = baseMargin + overlap;
                    content.style.setProperty('margin-left', offset + 'px', 'important');
                    content.style.setProperty('margin-right', '0', 'important');
                    content.style.setProperty('width', 'calc(100% - ' + offset + 'px)', 'important');
                    content.style.setProperty('max-width', 'calc(100% - ' + offset + 'px)', 'important');
                    content.style.setProperty('box-sizing', 'border-box', 'important');
                    content.setAttribute('data-sidebar-offset', 'true');
                    content.setAttribute('data-sidebar-applied-offset', overlap);
                } else if (content.hasAttribute('data-sidebar-offset')) {
                    content.style.setProperty('margin-left', baseMargin + 'px', 'important');
                    content.style.removeProperty('margin-right');
                    content.style.removeProperty('width');
                    content.style.removeProperty('max-width');
                    content.style.removeProperty('box-sizing');
                    content.removeAttribute('data-sidebar-offset');
                    content.removeAttribute('data-sidebar-applied-offset');
                    content.removeAttribute('data-sidebar-base-margin');
                }
            }

            var sidebarObserver = new MutationObserver(syncSidebarOverlap);
            sidebarObserver.observe(layout, {
                attributes: true,
                attributeFilter: ['class', 'vertical-nav-type', 'pcoded-device-type', 'vertical-placement', 'vertical-effect']
            });
            sidebarObserver.observe(sidebar, {
                attributes: true,
                attributeFilter: ['class', 'style']
            });
            sidebar.addEventListener('transitionend', syncSidebarOverlap);
            window.addEventListener('resize', syncSidebarOverlap);
            setTimeout(syncSidebarOverlap, 0);
            setTimeout(syncSidebarOverlap, 300);
        })();

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
