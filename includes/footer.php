<?php
/**
 * resupply - Footer Include (Professional Rewrite)
 * Clean, consistent, no duplicate Bootstrap JS
 * Date: May 15, 2026
 */
?>

    </div> <!-- End of .container from header.php -->

    <!-- Footer -->
    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container text-center text-muted small">
            &copy; <?= date('Y') ?> Resupply Rocket • Quarry Tile Shop
            <br>
            <a href="<?= BASE_URL ?>privacy.php" class="text-muted text-decoration-none">Privacy</a> • 
            <a href="<?= BASE_URL ?>support.php" class="text-muted text-decoration-none">Support</a>
        </div>
    </footer>

    <!-- Bootstrap JS (only once) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Global JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('%c✅ Resupply Rocket Professional Edition loaded successfully', 'color: #28a745; font-weight: bold;');
        });
    </script>
</body>
</html>