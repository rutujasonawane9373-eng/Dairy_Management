<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 12
   Shared page header - File: php/includes/header.php

   Every PHP page includes this file after it has started the session
   and after it has set $pageTitle and $pageSection. The file only
   PRINTS HTML - it never changes any data.

   $pageTitle    the text of the <title> and of the gold banner
   $pageSection  'home' / 'register' / 'profile' / 'logout' - used to
                 put class="active" on the menu link of this page
   ============================================================ */

/* isset() asks "does this variable exist?". The ?? operator gives the
   title a default value, so a page that forgets to set it still works. */
$pageTitle = $pageTitle ?? 'PHP Farmer Registration';
$pageSection = $pageSection ?? '';

/* Read the session for the status strip below the menu.
   session_id() is the unique code of this visitor's session file. */
$sessionId = session_id();

if ($sessionId === '') {
    /* After session_destroy() the session is gone, so there is no id. */
    $sessionIdText = 'closed';
} else {
    /* substr() cuts out only the first 8 characters - the full id is
       long and is of no use on the page. */
    $sessionIdText = substr($sessionId, 0, 8) . '...';
}

/* The time of the last registration, printed in the strip. */
$lastRegistration = 'none yet';

if (isset($_SESSION['farmer_profile']['registered_at'])) {
    $lastRegistration = $_SESSION['farmer_profile']['registered_at'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dairy Management System - Assignment 12: PHP form handling, server-side validation, string functions and sessions for farmer registration.">
    <title><?= safeText($pageTitle) ?> | Dairy Management System</title>

    <!-- ASSIGNMENT 3 : EXTERNAL CSS. The PHP pages use the SAME
         stylesheet as the HTML pages, so the project keeps one look.
         ".." goes up from php/ to the project folder, then into
         frontend/. This is the same relative-path rule as
         "../css/style.css" inside frontend/pages/. -->
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>

<!-- ASSIGNMENT 5 style hook (the same idea as class="page-centre").
     The two Assignment 12 CSS rules are written for this class only. -->
<body class="page-php">

    <header>
        <h1>Dairy Management System</h1>
        <p><?= safeText($pageTitle) ?> - Assignment 12 (PHP)</p>
    </header>

    <nav>
        <ul>
            <!-- Links back to the HTML pages of Assignments 1-7.
                 "../../" is not needed: php/ is one level deep. -->
            <li><a href="../frontend/index.html">Home</a></li>
            <li><a href="../frontend/pages/about.html">About</a></li>
            <li><a href="../frontend/pages/dashboard.html">Dashboard</a></li>
            <li><a href="../frontend/pages/collection-centre.html">Collection Centre</a></li>
            <li><a href="../frontend/pages/farmers.html">Farmers (A7)</a></li>

            <!-- The four pages of this assignment. The link of the page
                 that is open right now gets class="active", which
                 style.css highlights in gold. -->
            <li><a href="index.php" <?= $pageSection === 'home' ? 'class="active"' : '' ?>>PHP Home</a></li>
            <li><a href="register.php" <?= $pageSection === 'register' ? 'class="active"' : '' ?>>PHP Register</a></li>
            <li><a href="profile.php" <?= $pageSection === 'profile' ? 'class="active"' : '' ?>>PHP Profile</a></li>
            <li><a href="logout.php" <?= $pageSection === 'logout' ? 'class="active"' : '' ?>>PHP Logout</a></li>
        </ul>
    </nav>

    <!-- ======================================================
         THE SESSION STRIP - written by PHP, not by JavaScript.
         Everything here comes out of $_SESSION, which is why the
         numbers survive a change of page.
         ====================================================== -->
    <p class="register-status">
        SESSION: <strong><?= farmerIsRegistered() ? 'a farmer is registered' : 'no farmer registered yet' ?></strong>
        &bull; session id = <code><?= safeText($sessionIdText) ?></code>
        &bull; registrations in this session = <strong><?= registeredCount() ?></strong>
        &bull; last registration = <strong><?= safeText($lastRegistration) ?></strong>
    </p>

    <main>