<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   SHARED PAGE HEADER
   File: php/db-crud/db-header.php

   The same job as php/includes/header.php of Assignment 12, but
   it is a SEPARATE file on purpose - the four pages of Assignment
   12 keep working exactly as they were, and Assignment 13 does
   not touch a single line of them.

   WHAT A PAGE MUST SET BEFORE INCLUDING THIS FILE
       $pageTitle     the <title> and the gold banner text
       $pageSection   which menu link to mark class="active"
                      'home' / 'list' / 'create' / 'edit' / 'delete'
       $pageTotal     number of farmers in the table, or null
                      (printed in the database strip below)

   The file only PRINTS. It opens no connection and reads no
   table, so it still works on a page where the database failed
   to answer.
   ============================================================ */

require_once __DIR__ . '/db-helpers.php';

/* The database strip below prints DB_NAME and FARMERS_TABLE, so
   the settings file is loaded here as well. require_once means
   the file is read only once even if several files ask for it. */
require_once __DIR__ . '/db-config.php';

/* The ?? operator gives a default, so a page that forgets to set
   one of them still works instead of printing a warning. */
$pageTitle = $pageTitle ?? 'PHP + MySQL Farmer Records';
$pageSection = $pageSection ?? '';
$pageTotal = $pageTotal ?? null;

/* The message parked by the previous page after a successful
   INSERT / UPDATE / DELETE. dbFlashTake() also removes it, so it
   is shown exactly once. */
$flash = dbFlashTake();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dairy Management System - Assignment 13: PHP and MySQL database connectivity with full CRUD (INSERT, SELECT, UPDATE, DELETE) using prepared statements.">
    <title><?= safeText($pageTitle) ?> | Dairy Management System</title>

    <!-- ASSIGNMENT 3 : EXTERNAL CSS. The A13 pages use the SAME
         stylesheet as the HTML pages of Assignments 1-7 and as the
         PHP pages of Assignment 12, so the whole project has one
         look.
         This file sits two levels deep (php/db-crud/), so TWO ".."
         steps are needed to reach the project root:
             ../..  ->  Dairy_Management
             /frontend/css/style.css  ->  the one stylesheet -->
    <link rel="stylesheet" href="../../frontend/css/style.css">
</head>

<!-- ASSIGNMENT 5 style hook. Every rule of the ASSIGNMENT 13
     block of style.css is written for body.page-db only, so
     Assignments 1-12 are not affected. -->
<body class="page-db">

    <header>
        <h1>Dairy Management System</h1>
        <p><?= safeText($pageTitle) ?> - Assignment 13 (PHP + MySQL CRUD)</p>
    </header>

    <nav>
        <ul>
            <!-- ============ LINKS TO THE EARLIER ASSIGNMENTS ==========
                 All five HTML pages and the four Assignment 12 PHP
                 pages are one click away, so the CRUD screen is part
                 of the same application and not a separate project. -->
            <li><a href="../../frontend/index.html">Home</a></li>
            <li><a href="../../frontend/pages/about.html">About</a></li>
            <li><a href="../../frontend/pages/dashboard.html">Dashboard</a></li>
            <li><a href="../../frontend/pages/collection-centre.html">Collection Centre</a></li>
            <li><a href="../../frontend/pages/farmers.html">Farmers (A7)</a></li>

            <!-- ============ ASSIGNMENT 12 (PHP + SESSIONS) ============ -->
            <li><a href="../index.php">A12 PHP Home</a></li>
            <li><a href="../register.php">A12 Register</a></li>

            <!-- ============ ASSIGNMENT 13 (THIS ASSIGNMENT) ============
                 The page that is open now gets class="active", which
                 style.css highlights in gold. -->
            <li><a href="index.php" <?= $pageSection === 'home' ? 'class="active"' : '' ?>>A13 Home</a></li>
            <li><a href="farmer-list.php" <?= $pageSection === 'list' ? 'class="active"' : '' ?>>A13 List</a></li>
            <li><a href="farmer-create.php" <?= $pageSection === 'create' ? 'class="active"' : '' ?>>A13 Create</a></li>
            <li><a href="farmer-edit.php" <?= $pageSection === 'edit' ? 'class="active"' : '' ?>>A13 Update</a></li>
            <li><a href="farmer-delete.php" <?= $pageSection === 'delete' ? 'class="active"' : '' ?>>A13 Delete</a></li>
        </ul>
    </nav>

    <!-- ==========================================================
         THE DATABASE STRIP - written by PHP, not by JavaScript.
         It answers the first question of the viva at a glance:
         which database are we talking to, and how many rows are
         in the table right now?
         ========================================================== -->
    <p class="register-status">
        DATABASE: <strong><code><?= safeText(DB_NAME) ?></code></strong>
        &bull; table = <strong><code><?= safeText(FARMERS_TABLE) ?></code></strong>
        &bull; connection = <strong><?= $pageTotal === null ? 'not needed on this page' : 'open (' . safeText(DB_HOST) . ':' . safeText((string) DB_PORT) . ')' ?></strong>
        &bull; farmers in the table = <strong><?= $pageTotal === null ? '?' : safeText((string) $pageTotal) ?></strong>
        &bull; every SQL uses a <strong>prepared statement</strong>
    </p>

    <main>

        <!-- ============ THE ONE-TIME MESSAGE ============
             Printed by db-header.php itself so that no page has to
             repeat it. The class is "ok" (green) after a successful
             write and "error" (red) when the write was refused. -->
        <?php if ($flash['message'] !== '') { ?>
        <p class="db-message <?= $flash['type'] === 'error' ? 'db-error' : 'db-ok' ?>" id="db-flash-message">
            <?= safeText($flash['message']) ?>
        </p>
        <?php } ?>
