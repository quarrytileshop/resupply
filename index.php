<?php
/**
 * resupply - Index Page (Landing / Redirect)
 * Updated for new folder structure (May 14, 2026)
 * Simple redirect to login - no path changes needed
 */

// This is the very first file visitors hit.
// It immediately sends them to the login page.
header("Location: login.php");
exit;
?>