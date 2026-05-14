<?php
/**
 * resupply - Footer Include
 * Updated for new folder structure (May 14, 2026)
 * Closes the container and body started in header.php
 */
?>

    </div> <!-- End of .container from header.php -->

    <!-- Footer -->
    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container text-center text-muted small">
            &copy; <?= date('Y') ?> Resupply Rocket • Built for Quarry Tile Shop
            <br>
            <a href="#" class="text-muted text-decoration-none">Privacy</a> • 
            <a href="#" class="text-muted text-decoration-none">Support</a>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle (required for navbar, dropdowns, alerts, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Optional: Any global custom JavaScript can go here -->
    <script>
        // Global JS ready when the new folder structure loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('%c✅ Resupply loaded with new clean folder structure', 'color: #28a745; font-weight: bold;');
        });
    </script>
</body>
</html>
