<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   OPERATION "CREATE"  ->  SQL: INSERT
   File: php/db-crud/farmer-create.php

   THE FLOW OF THIS PAGE
       1. the browser sends the five fields with method="post"
       2. PHP reads $_POST and cleans every value with trim()
       3. runFarmerValidation() checks the five rules
          - a wrong value comes back with a red box and nothing
            is written to MySQL
          - no error -> dbInsertFarmer() runs a PREPARED
            INSERT statement
       4. the browser is redirected to farmer-list.php, so
          pressing F5 cannot save the same farmer twice

   THE STATEMENT ACTUALLY SENT TO MySQL

       INSERT INTO farmers
           (name, phone, village, milk_quantity, fat_percentage)
       VALUES (?, ?, ?, ?, ?)

   with the five values attached separately by bind_param('sssdd', ...).
   ============================================================ */

/* ---------- STEP 0 : SESSION AND THE HELPERS -------------------- */
session_start();

require_once __DIR__ . '/db-farmers.php';
require_once __DIR__ . '/db-validate.php';


/* ---------- STEP 1 : OPEN THE CONNECTION ------------------------ */
$connection = dbConnect();


/* ---------- STEP 2 : TWO BOXES USED WHILE THE PAGE RUNS --------- */

/* $errors holds one reason per REJECTED field. It is empty while
   the form is being filled in for the first time. */
$errors = [];

/* $old holds what was typed, so nothing has to be typed twice if
   the form comes back with a red box. */
$old = [
    'name'           => '',
    'phone'          => '',
    'village'        => '',
    'milk_quantity'  => '',
    'fat_percentage' => '',
];

/* The neutral hint shown above the form. */
$statusText = 'Fill in the five fields and press "Save Farmer". PHP checks every '
            . 'value on the server, then saves the row with a prepared INSERT.';
$statusClass = '';


/* ---------- STEP 3 : WAS THE FORM SENT BACK TO US? ------------- */

/* $_SERVER['REQUEST_METHOD'] is 'GET' when the page is opened and
   'POST' when the form arrives. So this whole block runs only the
   second time. */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* --- 3a. READ the five fields and trim the spaces off --- */
    $old['name']           = postField('name');
    $old['phone']          = postField('phone');
    $old['village']        = postField('village');
    $old['milk_quantity']  = postField('milk_quantity');
    $old['fat_percentage'] = postField('fat_percentage');

    /* --- 3b. VALIDATE ON THE SERVER (no SQL yet) --- */
    $errors = runFarmerValidation($old);

    /* --- 3c. count() is the number of wrong fields --- */
    if (count($errors) === 0) {

        /* Nothing is wrong, so build the row and save it.
           buildFarmerValues() casts the two numbers to float. */
        $farmerValues = buildFarmerValues($old);

        /* THE DATABASE WRITE. Everything about SQL lives in
           dbInsertFarmer() - this page never writes SQL itself. */
        $result = dbInsertFarmer($connection, $farmerValues);

        if ($result['ok']) {
            /* A one-time message is parked in the session and
               carried across the redirect below. */
            dbFlashSet('ok', $result['message']
                . ' The record is now permanent - closing the browser will not remove it.');

            /* ---------- POST / REDIRECT / GET ----------
               header() writes a line into the HTTP response instead
               of printing anything, and the browser then asks for
               farmer-list.php. exit stops the script, because PHP
               would otherwise keep printing the form below and the
               browser would complain "headers already sent". */
            header('Location: farmer-list.php');
            exit;
        }

        /* The INSERT itself failed - most often the phone number is
           already in the table (MySQL error 1062). */
        $statusClass = 'error';
        $statusText = $result['message'] . ' Nothing was written to the database.';

    } else {
        /* At least one value was rejected. The database is NOT
           touched - that is the whole point of validating first. */
        $statusClass = 'error';
        $statusText = count($errors) . ' value(s) were rejected by PHP, so no row was '
                    . 'added to the database. Please correct the red fields below.';
    }
}


/* ---------- STEP 4 : THE CURRENT ROW COUNT ---------------------- */
/* Shown in the database strip of db-header.php, so the visitor can
   see the table grow after every save. */
$pageTotal = dbFarmerSummary($connection)['farmer_count'];

$pageTitle = 'Add a New Farmer (INSERT)';
$pageSection = 'create';

require_once __DIR__ . '/db-header.php';
?>

    <section>
        <h2>Add a New Farmer &mdash; SQL <code>INSERT</code></h2>

        <p class="centre-note">
            ASSIGNMENT 13 &bull; five fields sent with
            <strong>method="post"</strong> &bull; checked by
            <code>runFarmerValidation()</code> &bull; saved by
            <code>dbInsertFarmer()</code> with a prepared statement
            <span class="required-mark">*</span> fields are compulsory
        </p>

        <!-- The one message box of the page. -->
        <p class="db-message <?= $statusClass === 'error' ? 'db-error' : '' ?>" id="create-status">
            <?= safeText($statusText) ?>
        </p>

        <!-- ============ THE LIST OF REJECTED VALUES ============ -->
        <!-- Printed only when at least one field failed, so the
             farmer sees every problem in one place. -->
        <?php if (count($errors) > 0) { ?>
        <div class="db-error-box" id="create-error-list">
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
             Every input has a name attribute. That name becomes the
             $_POST key read by postField('...') above - the name
             attribute and the PHP code must always use the SAME
             word. The five names match the five columns of the
             table, which is deliberate and makes the INSERT easy to
             follow.
             oldValue() prints what was typed, so an error does not
             cost the farmer their typing. -->
        <form method="post" action="farmer-create.php" class="farmer-form" id="farmer-create-form">

            <div class="form-grid">

                <!-- ---- Name ---- -->
                <div class="form-row">
                    <label for="create-name">Farmer Name <span class="required-mark">*</span></label>
                    <input type="text" id="create-name" name="name"
                           value="<?= oldValue($old, 'name') ?>"
                           <?= isset($errors['name']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. Ramesh Patil"
                           autocomplete="name"
                           required minlength="3" maxlength="60"
                           pattern="[A-Za-z][A-Za-z .'-]{2,}">
                    <p class="field-hint">3 to 60 characters; letters, spaces, dot, apostrophe, hyphen.</p>
                    <p class="error-message"><?= safeText($errors['name'] ?? '') ?></p>
                </div>

                <!-- ---- Phone ---- -->
                <div class="form-row">
                    <label for="create-phone">Mobile Number <span class="required-mark">*</span></label>
                    <input type="tel" id="create-phone" name="phone"
                           value="<?= oldValue($old, 'phone') ?>"
                           <?= isset($errors['phone']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. 9876543210"
                           autocomplete="tel"
                           inputmode="numeric" maxlength="10"
                           required pattern="[6-9][0-9]{9}">
                    <p class="field-hint">10 digits starting with 6-9. Must be different from every other farmer, because the column has a UNIQUE key.</p>
                    <p class="error-message"><?= safeText($errors['phone'] ?? '') ?></p>
                </div>

                <!-- ---- Village ---- -->
                <div class="form-row">
                    <label for="create-village">Village / Address <span class="required-mark">*</span></label>
                    <input type="text" id="create-village" name="village"
                           value="<?= oldValue($old, 'village') ?>"
                           <?= isset($errors['village']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. Wadgaon, Tal. Shahada"
                           required minlength="3" maxlength="60">
                    <p class="field-hint">3 to 60 characters.</p>
                    <p class="error-message"><?= safeText($errors['village'] ?? '') ?></p>
                </div>

                <!-- ---- Milk Quantity ---- -->
                <div class="form-row">
                    <label for="create-milk">Average Milk per Day (L) <span class="required-mark">*</span></label>
                    <input type="number" id="create-milk" name="milk_quantity"
                           value="<?= oldValue($old, 'milk_quantity') ?>"
                           <?= isset($errors['milk_quantity']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. 18.5"
                           step="0.5" min="0.5" max="100"
                           required>
                    <p class="field-hint">0.5 to 100 litres.</p>
                    <p class="error-message"><?= safeText($errors['milk_quantity'] ?? '') ?></p>
                </div>

                <!-- ---- Fat Percentage ---- -->
                <div class="form-row">
                    <label for="create-fat">Fat Percentage <span class="required-mark">*</span></label>
                    <input type="number" id="create-fat" name="fat_percentage"
                           value="<?= oldValue($old, 'fat_percentage') ?>"
                           <?= isset($errors['fat_percentage']) ? 'class="field-error"' : '' ?>
                           placeholder="e.g. 4.6"
                           step="0.1" min="3" max="8"
                           required>
                    <p class="field-hint">3% to 8%. 3.5% and above is paid at 42 Rs/litre (rateForFat()).</p>
                    <p class="error-message"><?= safeText($errors['fat_percentage'] ?? '') ?></p>
                </div>

                <div class="form-buttons">
                    <!-- The button that sends the form to this page
                         with POST. It is the INSERT trigger. -->
                    <button type="submit" class="btn">Save Farmer</button>
                    <!-- type="reset" only empties the boxes in the
                         browser; nothing reaches the server. -->
                    <button type="reset" class="btn btn-light">Clear Form</button>
                </div>

            </div>
        </form>
    </section>

    <!-- ========================================================
         THE SIX SAMPLE ROWS
         The table created by database/schema.sql starts with six
         farmers. This line warns about them, because a duplicate
         mobile number is the one mistake that a visitor is likely
         to make first - and it is the clearest demonstration of
         the UNIQUE key.
         ======================================================== -->
    <section>
        <h2>The Prepared Statement That Runs</h2>

        <p class="register-status">
            Current row count in <code><?= safeText(DB_NAME) ?>.<?= safeText(FARMERS_TABLE) ?></code>:
            <strong><?= safeText((string) $pageTotal) ?></strong>.
            The table was filled with 6 sample farmers by
            <code>database/schema.sql</code>. Try saving one of their mobile
            numbers again &mdash; PHP accepts the 10 digits, MySQL refuses
            the row because of the <code>UNIQUE</code> key, and the page
            answers with error 1062 turned into a sentence.
        </p>

        <p class="db-code">
            <code>
                INSERT INTO farmers (name, phone, village, milk_quantity, fat_percentage)<br>
                VALUES (?, ?, ?, ?, ?);<br>
                -- bind_param('sssdd', name, phone, village, milk, fat)
            </code>
        </p>

        <p>
            <a class="btn" href="farmer-list.php">See the stored rows (SELECT)</a>
            <a class="btn btn-light" href="index.php">Back to the A13 home page</a>
        </p>
    </section>

    <aside>
        <h3>How to read this page in the viva</h3>
        <ul>
            <li>The form uses <code>method="post"</code> and posts back to
                itself, so one file both prints and checks the form.</li>
            <li>The five <code>name</code> attributes match the five column
                names &mdash; that is why the INSERT reads so easily.</li>
            <li>Validation runs BEFORE any SQL, so a wrong value never
                reaches the database.</li>
            <li><code>dbInsertFarmer()</code> is the only place in the whole
                project that writes an INSERT, and it uses
                <code>prepare()</code> + <code>bind_param()</code> +
                <code>execute()</code>.</li>
            <li>After a success the browser is redirected, so pressing F5
                cannot insert the same farmer a second time.</li>
        </ul>
    </aside>

<?php
dbDisconnect($connection);

require_once __DIR__ . '/db-footer.php';
