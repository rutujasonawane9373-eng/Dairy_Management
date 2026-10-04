<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 12
   START PAGE : php/index.php

   This is the page that opens when the PHP built-in server is
   started without a file name:

       cd php
       php -S localhost:8000
       open http://localhost:8000/

   It only explains the flow and links to the three working pages.
   It has no form of its own.
   ============================================================ */

/* ---------- SESSION MANAGEMENT : start the session here too ------- */
session_start();

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'PHP Farmer Registration and Profile';
$pageSection = 'home';

require_once __DIR__ . '/includes/header.php';
?>

    <section>
        <h2>Assignment 12 - PHP in the Dairy Management System</h2>

        <p class="centre-note">
            Four PHP files &bull; no database &bull; no JavaScript on these pages
            &bull; the theme comes from the same
            <code>frontend/css/style.css</code> as the HTML pages
        </p>

        <!-- ============ WHAT IS DEMONSTRATED AND WHERE ============ -->
        <div class="log-window">
            <table>
                <thead>
                    <tr>
                        <th>PHP topic</th>
                        <th>Where it is used in this project</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Form handling with POST</td>
                        <td><code>register.php</code> - the
                            <code>&lt;form method="post" action="register.php"&gt;</code>
                            and the reading of <code>$_POST</code></td>
                    </tr>
                    <tr>
                        <td>Server-side validation</td>
                        <td><code>includes/functions.php</code> -
                            <code>checkFarmerName()</code>,
                            <code>checkMobileNumber()</code>,
                            <code>checkEmail()</code>,
                            <code>checkVillage()</code>,
                            <code>checkMilkQuantity()</code>,
                            <code>checkFatPercentage()</code></td>
                    </tr>
                    <tr>
                        <td>String manipulation</td>
                        <td><code>includes/functions.php</code> -
                            <code>trim()</code>, <code>strlen()</code>,
                            <code>strtoupper()</code>, <code>ucwords()</code>,
                            <code>strtolower()</code>, <code>str_replace()</code>,
                            <code>str_pad()</code>, <code>substr()</code>,
                            <code>str_repeat()</code>, <code>number_format()</code>,
                            <code>sprintf()</code> and the concatenation
                            operator <code>.</code></td>
                    </tr>
                    <tr>
                        <td>Session management</td>
                        <td><code>session_start()</code> on every page,
                            <code>$_SESSION['farmer_profile']</code>,
                            <code>$_SESSION['registered_farmers']</code>,
                            <code>$_SESSION['registration_count']</code>,
                            <code>$_SESSION['flash_success']</code>, and
                            <code>session_destroy()</code> in
                            <code>logout.php</code></td>
                    </tr>
                    <tr>
                        <td>Safe output</td>
                        <td><code>safeText()</code> -
                            <code>htmlspecialchars()</code> is used on every
                            value that came from the form</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ============ THE INTEGRATED FLOW ============ -->
    <section>
        <h2>The Flow</h2>

        <div class="flex-row">
            <div class="flex-item">
                <h3>Step 1 - Open the form</h3>
                <p>The farmer fills in the six fields and presses
                   "Register Farmer".</p>
                <p><a class="btn" href="register.php">Go to step 1</a></p>
            </div>
            <div class="flex-item">
                <h3>Step 2 - PHP checks it</h3>
                <p>PHP reads <code>$_POST</code>, cleans each value with
                   <code>trim()</code> and runs six server-side rules.</p>
                <p>A wrong value comes back with a red message; a correct
                   form is built into a farmer record.</p>
            </div>
            <div class="flex-item">
                <h3>Step 3 - PHP remembers it</h3>
                <p>The record is stored in <code>$_SESSION</code> and the
                   browser is redirected to the profile page.</p>
                <p><a class="btn" href="profile.php">Go to step 3</a></p>
            </div>
            <div class="flex-item">
                <h3>Step 4 - Session ends</h3>
                <p>The profile page proves the session works; the logout
                   page destroys it and the counters return to 0.</p>
                <p><a class="btn btn-light" href="logout.php">Go to step 4</a></p>
            </div>
        </div>

        <p class="register-status">
            Session state right now: <strong><?= registeredCount() ?></strong> registration(s)
            in this session. If the strip above says "no farmer registered yet",
            start at step 1.
        </p>
    </section>

    <aside>
        <h3>How to run these pages without XAMPP</h3>
        <ul>
            <li>Open a terminal inside the project's <code>php/</code> folder.</li>
            <li>Run <code>php -S localhost:8000</code> - this starts PHP's own
                small web server.</li>
            <li>Open <code>http://localhost:8000/</code> in the browser.</li>
            <li>Stop the server with <kbd>Ctrl</kbd> + <kbd>C</kbd>.</li>
            <li>A <code>.php</code> file must always be opened through a
                server. Opening it from the file system shows the source code.</li>
        </ul>
    </aside>

<?php
require_once __DIR__ . '/includes/footer.php';