<?php
session_start();

// If user is logged in, send to dashboard 
if (!empty($_SESSION['user_id'])) {
    header('Location: ./Dashboard.php'); 
    exit;
}

// Not logged in — show public home page
include __DIR__ . '/Home.php';
?>