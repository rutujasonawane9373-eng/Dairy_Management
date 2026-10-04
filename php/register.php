<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 12
   PAGE 1 of 3 : FARMER REGISTRATION
   File: php/register.php

   WHAT THIS PAGE DEMONSTRATES
     1. FORM HANDLING - the <form method="post" action="register.php">
        sends the six fields to this same page, and PHP reads them
        out of the built-in $_POST array.
     2. SERVER-SIDE VALIDATION - the same six rules that Assignment 7
        checks in JavaScript, written again here in PHP. PHP decides,
        not the browser.
     3. STRING MANIPULATION - trim(), strlen(), strtoupper(),
        strtolower(), ucwords(), str_replace(), str_pad(), substr(),
        str_repeat(), number_format(), sprintf() and the
        concatenation operator "." (all in includes/functions.php).
     4. SESSION MANAGEMENT - session_start(), three session values
        and the redirect that hands the data to profile.php.

   HOW TO RUN IT (PHP has no database in this assignment)
     1. Open a terminal in this project's php/ folder.
     2. Start the PHP built-in web server:
            php -S localhost:8000
     3. Open http://localhost:8000/register.php in the browser.
        A .php file MUST be opened through a server - double-clicking
        it in Windows only shows the source code.

   WHAT IT DELIBERATELY DOES NOT DO
     No MySQL, no INSERT, no UPDATE and no delete. The record lives
     in $_SESSION only and disappears when the browser is closed.
     That is Assignment 13.
   ============================================================ */


/* ---------- STEP 0 : START THE SESSION AND LOAD THE HELPERS ---------- */

/* session_start() must be the FIRST statement of the page. It opens
   (or re-opens) the small server-side file that belongs to this
   visitor and copies its content into the $_SESSION array.
   Every page of this assignment calls session_start(), which is how
   profile.php can still see what was typed here. */
session_start();

/* __DIR__ is the folder of this file, so the require works no matter
   which folder the server was started from. require_once means "load
   this file, but only the first time it is asked for". */
require_once __DIR__ . '/includes/functions.php';

/* The title is passed to header.php through this variable. */
$pageTitle = 'PHP Farmer Registration';
$pageSection = 'register';

require_once __DIR__ . '/includes/header.php';


/* ---------- STEP 1 : TWO CONTAINERS USED WHILE THE PAGE RUNS ---------- */

/* $errors holds one message per WRONG field, written as
   'field name' => 'reason'. While the form has just been opened the
   array is empty, which means "nothing has been checked yet". */
$errors = [];

/* $old holds what the farmer typed. Without it a rejected value would
   disappear and the farmer would have to type everything again.
   Each key starts as an empty string. */
$old = [
    'farmerName'    => '',
    'mobile'        => '',
    'email'         => '',
    'village'       => '',
    'milkQuantity'  => '',
    'fatPercentage' => '',
];

/* A one-line summary of what happened, printed in the message box
   above the form. */
$statusText = 'Fill in the farmer\'s details and press "Register Farmer". '
            . 'The values are checked by PHP on the server, not only in the browser.';
$statusClass = '';


/* ---------- STEP 2 : WAS THE FORM SUBMITTED? ---------- */

/* $_SERVER['REQUEST_METHOD'] is 'GET' when the page is opened and
   'POST' when the form was sent back to it. So everything inside
   this if() runs only on the second visit.
   (The other common way to test this is isset($_POST['submit']),
   which needs a submit button with a name attribute.) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* --- 2a. READ the six fields and clean every one with trim() --- */
    $old['farmerName']    = postText('farmerName');
    $old['mobile']        = postText('mobile');
    $old['email']         = postText('email');
    $old['village']       = postText('village');
    $old['milkQuantity']  = postText('milkQuantity');
    $old['fatPercentage'] = postText('fatPercentage');

    /* --- 2b. VALIDATE ON THE SERVER --- */
    /* runValidation() runs the six rules of functions.php and returns
       only the fields that were rejected. */
    $errors = runValidation($old);

    /* --- 2c. count() tells us how many fields were rejected --- */
    if (count($errors) === 0) {

        /* Everything is correct, so the farmer record is built.
           buildProfile() is where all the string functions are used:
           the name is cleaned, the mobile number is tidied and the
           farmer code is built with strtoupper() + str_pad() + "." */
        $profile = buildProfile($old);

        /* The record is written into the session. */
        saveFarmerInSession($profile);

        /* A message that must survive the redirect below, so it is
           stored in the session instead of being printed now. */
        $_SESSION['flash_success'] = greetingLine($profile['name'])
            . ' Farmer code ' . $profile['code']
            . ' was created and kept in the PHP session.';

        /* ---------- STEP 3 : REDIRECT AFTER A SUCCESSFUL POST ----- */
        /* header() writes a line into the HTTP response instead of
           printing text. The browser then asks for profile.php.
           Without this, pressing F5 would send the same form again
           and register the farmer twice.
           exit stops the script here, because PHP would otherwise
           keep printing the form below and the browser would report
           "headers already sent". */
        header('Location: profile.php');
        exit;

    } else {
        /* At least one value was rejected: write the summary message.
           count() is the number of wrong fields. */
        $statusClass = 'error';
        $statusText = count($errors) . ' value(s) were rejected by PHP. '
                    . 'Please correct the red fields below and send the form again.';
    }
}


/* ---------- STEP 4 : TOTALS OF THE FARMERS ALREADY IN THE SESSION ---------- */

/* The table at the bottom of the page is built from the session, so
   it grows every time a registration succeeds.
   The loop also adds up the litres and the money. */
$totalMilk = 0;
$totalAmount = 0;

foreach (registeredFarmers() as $savedFarmer) {
    $totalMilk += $savedFarmer['milk'];
    $totalAmount += $savedFarmer['amount'];
}
?>

    <section>
        <h2>Farmer Registration (PHP)</h2>

        <!-- Assignment 5 style information strip -->
        <p class="centre-note">
            ASSIGNMENT 12 &bull; the form below is sent with
            <strong>method="post"</strong> to this same page &bull;
            PHP reads it from <code>$_POST</code> &bull;
            every value is checked on the server with
            <code>strlen()</code>, <code>preg_match()</code> and
            <code>is_numeric()</code> &bull; fields marked
            <span class="required-mark">*</span> are compulsory
        </p>

        <!-- The one message box of the form. PHP writes either the
             neutral hint (class "") or the red failure message
             (class "form-message error"). -->
        <p class="register-status form-message <?= $statusClass ?>" id="php-form-status">
            <?= safeText($statusText) ?>
        </p>

        <!-- ============ THE LIST OF EVERY REJECTED VALUE ============ -->
        <!-- This block is printed only when at least one field failed,
             so the farmer sees every problem in one place and does not
             have to look for the red boxes one by one. -->
        <?php if (count($errors) > 0) { ?>
        <div class="form-message error" id="php-error-list">
            <p><strong>PHP rejected the following values:</strong></p>
            <ul>
                <?php foreach ($errors as $badField => $why) { ?>
                <li><strong><?= safeText(fieldLabel($badField)) ?>:</strong>
                    <?= safeText($why) ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>

        <!-- ============ THE FORM ============
             method="post"  -> the values travel inside the request body
             action          -> which file must check them. It is this
                               same page, which is why one file can both
                               print the form and check it.
             Every input has a name attribute - that name becomes the
             $_POST key, so it must match postText('...') above.
             novalidate is NOT used here: the browser's own bubbles would
             hide the messages PHP wrote. The PHP rules are the ones the
             farmer must read. -->
        <form method="post" action="register.php" class="farmer-form" id="php-farmer-form">

            <div class="form-grid">

                <!-- ---- Farmer Name ---- -->
                <div class="form-row">
                    <label for="farmer-name">Farmer Name <span class="required-mark">*</span></label>
                    <!-- The value attribute prints what the farmer typed.
                         The short echo tag ( = plus ?>) is the short form of
                         the echo statement and is always switched on.
                         safeText() is used everywhere, so a value that
                         contains an HTML bracket can never become HTML code. -->
                    <input type="text" id="farmer-name" name="farmerName"
                           value="<?= oldValue($old, 'farmerName') ?>"
                           <?= isset($errors['farmerName']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. Ramesh Patil"
                           autocomplete="name"
                           required minlength="3" maxlength="40"
                           pattern="[A-Za-z][A-Za-z .'-]{2,}">
                    <p class="error-message"><?= safeText($errors['farmerName'] ?? '') ?></p>
                </div>

                <!-- ---- Mobile Number ---- -->
                <div class="form-row">
                    <label for="farmer-mobile">Mobile Number <span class="required-mark">*</span></label>
                    <input type="tel" id="farmer-mobile" name="mobile"
                           value="<?= oldValue($old, 'mobile') ?>"
                           <?= isset($errors['mobile']) ? 'class="field-error"' : '' ?>
                           placeholder="10-digit mobile number"
                           autocomplete="tel"
                           inputmode="numeric" maxlength="10"
                           required pattern="[6-9][0-9]{9}">
                    <p class="error-message"><?= safeText($errors['mobile'] ?? '') ?></p>
                </div>

                <!-- ---- Email (optional) ---- -->
                <div class="form-row">
                    <label for="farmer-email">Email</label>
                    <!-- This field is NOT required. PHP accepts an empty
                         value and checks the shape only when it is
                         filled - the same optional rule as Assignment 7. -->
                    <input type="email" id="farmer-email" name="email"
                           value="<?= oldValue($old, 'email') ?>"
                           <?= isset($errors['email']) ? 'class="field-error"' : '' ?>
                           placeholder="optional - name@example.com"
                           autocomplete="email" maxlength="60">
                    <p class="field-hint">Optional - leave it blank if the farmer has no email.</p>
                    <p class="error-message"><?= safeText($errors['email'] ?? '') ?></p>
                </div>

                <!-- ---- Village / Address ---- -->
                <div class="form-row">
                    <label for="farmer-village">Village / Address <span class="required-mark">*</span></label>
                    <input type="text" id="farmer-village" name="village"
                           value="<?= oldValue($old, 'village') ?>"
                           <?= isset($errors['village']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. Wadgaon, Tal. Shahada"
                           required minlength="3" maxlength="60">
                    <p class="error-message"><?= safeText($errors['village'] ?? '') ?></p>
                </div>

                <!-- ---- Milk Quantity ---- -->
                <div class="form-row">
                    <label for="milk-quantity">Average Milk per Day (L) <span class="required-mark">*</span></label>
                    <input type="number" id="milk-quantity" name="milkQuantity"
                           value="<?= oldValue($old, 'milkQuantity') ?>"
                           <?= isset($errors['milkQuantity']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. 18.5"
                           required min="0.5" max="100" step="0.5">
                    <p class="field-hint">Litres the farmer brings on an average day.</p>
                    <p class="error-message"><?= safeText($errors['milkQuantity'] ?? '') ?></p>
                </div>

                <!-- ---- Fat Percentage ---- -->
                <div class="form-row">
                    <label for="fat-percentage">Fat Percentage <span class="required-mark">*</span></label>
                    <input type="number" id="fat-percentage" name="fatPercentage"
                           value="<?= oldValue($old, 'fatPercentage') ?>"
                           <?= isset($errors['fatPercentage']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. 4.6"
                           required min="3" max="8" step="0.1">
                    <p class="field-hint">3.5% and above is paid at 42 Rs/litre (PHP decides this in rateForFat()).</p>
                    <p class="error-message"><?= safeText($errors['fatPercentage'] ?? '') ?></p>
                </div>

                <!-- ---- The two buttons ---- -->
                <div class="form-buttons">
                    <!-- This is the button that sends the form to this
                         page with POST. -->
                    <button type="submit" class="btn">Register Farmer</button>
                    <!-- type="reset" only empties the boxes in the
                         browser; it never reaches the server. -->
                    <button type="reset" class="btn btn-light">Clear Form</button>
                </div>

            </div>
        </form>
    </section>

    <!-- ========================================================
         THE SESSION TABLE
         This table is printed by PHP, not by JavaScript. It is
         rebuilt on every visit from $_SESSION['registered_farmers'],
         which is why the rows are still there after a page change.
         ======================================================== -->
    <section>
        <h2>Farmers Registered in This PHP Session</h2>

        <p class="register-status" id="php-session-count">
            <?php if (registeredCount() === 0) { ?>
                No farmer registered yet in this session. Register one above and the
                row will appear here.
            <?php } else { ?>
                <?= registeredCount() ?> farmer(s) registered in this session &bull;
                <?= safeText(number_format($totalMilk, 1)) ?> litres a day &bull;
                Rs <?= safeText(number_format($totalAmount, 2)) ?> a day in milk value
                (calculated with a foreach loop and += in PHP).
            <?php } ?>
        </p>

        <div class="log-window">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Farmer Code</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Village</th>
                        <th>Milk (L)</th>
                        <th>Fat %</th>
                        <th>Rate (Rs)</th>
                        <th>Amount (Rs)</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (registeredCount() === 0) { ?>
                    <tr>
                        <td colspan="10">No record yet - this table is filled by PHP from the session.</td>
                    </tr>
                    <?php } else { ?>
                        <?php $rowNumber = 1; ?>
                        <?php foreach (registeredFarmers() as $savedFarmer) { ?>
                        <tr>
                            <td><?= $rowNumber ?></td>
                            <td><?= safeText($savedFarmer['code']) ?></td>
                            <td><?= safeText($savedFarmer['name']) ?></td>
                            <td><?= safeText($savedFarmer['mobile_display']) ?></td>
                            <td><?= safeText($savedFarmer['village']) ?></td>
                            <td><?= safeText(number_format($savedFarmer['milk'], 1)) ?></td>
                            <td><?= safeText(number_format($savedFarmer['fat'], 1)) ?></td>
                            <td><?= safeText(number_format($savedFarmer['rate'], 2)) ?></td>
                            <td><?= safeText(number_format($savedFarmer['amount'], 2)) ?></td>
                            <td><?= safeText($savedFarmer['registered_at']) ?></td>
                        </tr>
                        <?php $rowNumber++; ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Reminders for the society staff who fill this form. -->
    <aside>
        <h3>How this page works (read before the viva)</h3>
        <ul>
            <li>The form sends its values with <code>method="post"</code> to this same page.</li>
            <li>PHP reads them from <code>$_POST</code> and cleans each one with <code>trim()</code>.</li>
            <li>Six rules run on the server; every rejected field gets a red border and a red message.</li>
            <li>A correct form is built into a farmer record and stored in <code>$_SESSION</code>.</li>
            <li>The browser is then sent to <code>profile.php</code>, which reads the same session.</li>
            <li>No SQL statement is used anywhere in this assignment - storage comes with Assignment 13.</li>
        </ul>
    </aside>

<?php
require_once __DIR__ . '/includes/footer.php';