<?php
require_once 'config/database.php';

// Check if user is logged in
$isLoggedIn = is_client_authenticated();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Chat Test</title>";
echo "<link rel='stylesheet' href='/public/css/chat-widget.css'>";
echo "</head><body>";

echo "<div style='padding: 20px; font-family: Arial, sans-serif;'>";
echo "<h1>Chat System Test</h1>";
echo "<p><strong>User logged in:</strong> " . ($isLoggedIn ? 'Yes' : 'No') . "</p>";
echo "<p><strong>User ID:</strong> " . ($userId ? $userId : 'Not set') . "</p>";

if ($isLoggedIn) {
    echo "<p style='color: green;'>✓ User is authenticated. Chat widget should work.</p>";
    echo "<p>The chat widget should appear in the bottom-right corner.</p>";
} else {
    echo "<p style='color: red;'>✗ User is not authenticated. Please log in first.</p>";
    echo "<p><a href='/home/login'>Go to Login</a></p>";
}

echo "<hr>";
echo "<h2>Session Debug Info:</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "</div>";

if ($isLoggedIn) {
    echo "<script>";
    echo "var userLoggedIn = true;";
    echo "</script>";
    echo "<script src='/public/js/chat-widget-core.js'></script>";
} else {
    echo "<script>";
    echo "var userLoggedIn = false;";
    echo "</script>";
}

echo "</body></html>";
?>
