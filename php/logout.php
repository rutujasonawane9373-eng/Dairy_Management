<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 12
   PAGE 3 of 3 : ENDING THE SESSION (LOGOUT)
   File: php/logout.php

   The last part of the session demonstration. A session must be
   closed properly, otherwise the data would stay on the server.

   The three steps used here are the standard ones:

     1. $_SESSION = array()   clear every value of the session
     2. session_destroy()    delete the session file on the server
     3. setcookie()          ask the browser to delete the cookie

   Run it with:  php -S localhost:8000     (inside the php/ folder)
   ============================================================ */

/* ---------- STEP 0 : START THE SESSION, BECAUSE IT MUST EXIST ------
   To destroy a session PHP first has to open it. */
session_start();

require_once __DIR__ . '/includes/functions.php';

/* ---------- STEP 1 : REMEMBER WHAT WAS IN IT, FOR THE MESSAGE ------
   The values are read BEFORE the session is emptied, otherwise the
   message could not name the farmer. */
$farmerName = $_SESSION['farmer_profile']['name'] ?? '';
$registrations = registeredCount();
$countedMilk = 0;

foreach (registeredFarmers() as $savedFarmer) {
    $countedMilk += $savedFarmer['milk'];
}

$pageTitle = 'PHP Session Logout';
$pageSection = 'logout';

/* ---------- STEP 2 : EMPTY AND DESTROY THE SESSION ----------------- */

/* Step 1 of the three: remove every key of $_SESSION. */
$_SESSION = array();

/* Step 2: delete the session file that was created by
   session_start(). After this call the session no longer exists. */
session_destroy();

/* Step 3: delete the cookie too, so the browser does not send an id
   that points to nothing. session_get_cookie_params() gives back the
   path and flags that were used when the cookie was created, and the
   expiry time in the past is what removes a cookie. */
if (ini_get('session.use_cookies')) {
    $cookieParams = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieParams['path'],
        $cookieParams['domain'],
        $cookieParams['secure'],
        $cookieParams['httponly']
    );
}

require_once __DIR__ . '/includes/header.php';
?>

    <section>
        <h2>Session Ended</h2>

        <!-- ============ CONFIRMATION MESSAGE ============
             Every line below was read out of the session BEFORE it was
             destroyed, which is why the numbers are still correct. -->
        <p class="register-status form-message ok" id="php-logout-message">
            The PHP session has been ended.
            <?php if ($farmerName !== '') { ?>
                The profile of <strong><?= safeText($farmerName) ?></strong> has been removed.
            <?php } else { ?>
                No farmer was registered in this session.
            <?php } ?>
        </p>

        <!-- The proof, printed with the values that were captured a
             moment ago: this is the string concatenation operator at
             its simplest. -->
        <p class="centre-note">
            This visit ended with
            <strong><?= $registrations ?></strong> registration(s) and
            <strong><?= safeText(number_format($countedMilk, 1)) ?></strong> litres a day.
            <?= $registrations === 1 ? 'That was one farmer.' : 'Those were all the farmers of this visit.' ?>
        </p>

        <!-- registeredCount() is now 0 and farmerIsRegistered() is
             false, because the session array was emptied. -->
        <div class="flex-row">
            <div class="flex-item">
                <h3>Session Status Now</h3>
                <p class="total-value"><?= farmerIsRegistered() ? 'OPEN' : 'CLOSED' ?></p>
                <p>session_id() = <?= session_id() === '' ? 'empty' : 'still set' ?></p>
            </div>
            <div class="flex-item">
                <h3>Registrations Left</h3>
                <p class="total-value"><?= registeredCount() ?></p>
                <p>$_SESSION['registration_count'] is gone</p>
            </div>
            <div class="flex-item">
                <h3>What Happens Next</h3>
                <p>Open the register page again and the count starts from 0,
                   because nothing was written to a database.</p>
            </div>
        </div>

        <p>
            <a class="btn" href="register.php">Start a New Session</a>
            <a class="btn btn-light" href="index.php">Back to the PHP Home Page</a>
        </p>
    </section>

    <aside>
        <h3>What session_destroy() really does</h3>
        <ul>
            <li>It deletes the session DATA on the server.</li>
            <li>It does not unset the global variables, so
                <code>$_SESSION</code> is cleared with <code>$_SESSION = array()</code> first.</li>
            <li>It does not delete the cookie, which is why
                <code>setcookie()</code> is also used.</li>
            <li>The next page that calls <code>session_start()</code> gets a brand new empty session.</li>
        </ul>
    </aside>

<?php
require_once __DIR__ . '/includes/footer.php';