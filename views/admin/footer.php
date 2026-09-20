<?php
// views/admin/footer.php
$app_name    = function_exists('getAppName') ? getAppName() : 'Wisnu Wibisono';
$app_version = function_exists('getAppVersion') ? getAppVersion() : '1.0.0';
?>
    </div>
    <!-- /#content -->

    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span style="font-size: 12px;">
                    <i class="fas fa-user-circle" style="color: #7c3aed;"></i>
                    <span style="color: #1a2634; font-weight: 500;">
                        <?= htmlspecialchars($app_name) ?>
                    </span>
                    <span style="color: #8a94a6;">&copy; <?= date('Y'); ?></span>
                    <span style="color: #d1d5db; margin: 0 8px;">|</span>
                    <span style="color: #8a94a6; font-size: 11px;">
                        <i class="fas fa-code"></i> v<?= htmlspecialchars($app_version) ?>
                    </span>
                </span>
            </div>
        </div>
    </footer>

</div>
<!-- /#content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- ============================================
     FUNCTION TOGGLE SIDEBAR (WAJIB DI ATAS LIBRARY)
     ============================================ -->
<script>
function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    if (sidebar) sidebar.classList.toggle('show');
    if (overlay) overlay.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            var sidebar = document.querySelector('.sidebar');
            if (sidebar) sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
});
</script>

<!-- ============================================
     LIBRARIES
     ============================================ -->
<script src="<?= vendor_asset('jquery/jquery.min.js') ?>"></script>
<script src="<?= vendor_asset('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= asset('js/ruang-admin.min.js') ?>"></script>

</body>
</html>