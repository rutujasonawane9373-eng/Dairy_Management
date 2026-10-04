<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   OPERATION "UPDATE"  ->  SQL: SELECT ... WHERE id = ? , then UPDATE
   File: php/db-crud/farmer-edit.php

   THE FLOW OF THIS PAGE
       1. the id arrives in the address bar: farmer-edit.php?id=4
       2. dbSelectFarmerById() reads that ONE row with a prepared
          statement and the five boxes are filled with its values
       3. the form is sent back with method="post" - the same id
          travels in a HIDDEN field
       4. the five values are validated, then dbUpdateFarmer()
          runs the prepared UPDATE

   WHY TWO STATEMENTS ARE NEEDED
   Reading the current row is itself a database operation (a
   prepared SELECT), so this page demonstrates two of the four
   letters of CRUD - which is normal and worth saying in the viva.

   THE STATEMENT ACTUALLY SENT TO MySQL

       SELECT id, name, phone, ... FROM farmers WHERE id = ? LIMIT 1

       UPDATE farmers
       SET name = ?, phone = ?, village = ?,
           milk_quantity = ?, fat_percentage = ?
       WHERE id = ?
   ============================================================ */

/* ---------- STEP 0 : SESSION AND THE HELPERS -------------------- */
session_start();

require_once __DIR__ . '/db-farmers.php';
require_once __DIR__ . '/db-validate.php';


/* ---------- STEP 1 : OPEN THE CONNECTION ------------------------ */
$connection = dbConnect();


/* ---------- STEP 2 : WHICH FARMER IS BEING EDITED? ------------- */

/* (int) is PHP's cast: it turns "4" into 4 and "4abc" into 4.
   Casting straight away means the value is a number before it is
   used, and it is the number that is later bound with 'i'. */
$farmerId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

/* $farmerRow starts as null and is filled by a SELECT below. */
$farmerRow = null;
$notFoundMessage = '';

if ($farmerId > 0) {
    /* THE FIRST PREPARED STATEMENT OF THIS PAGE - the READ that
       fills the boxes. */
    $farmerRow = dbSelectFarmerById($connection, $farmerId);

    if ($farmerRow === null) {
        /* No row came back, so the id in the address bar is wrong.
           Saying so plainly is better than printing an empty form. */
        $notFoundMessage = 'No farmer was found with number ' . $farmerId
            . '. It may already have been deleted, or the id in the address '
            . 'bar was changed by hand.';
    }
} else {
    $notFoundMessage = 'No farmer number was given. Open this page from the '
        . 'Update link of a row in the farmer list, or add the id to the '
        . 'address bar, for example farmer-edit.php?id=1';
}


/* ---------- STEP 3 : WAS THE FORM SENT BACK? -------------------- */

/* $errors: one reason per rejected field. */
$errors = [];

/* $old: what is currently typed / stored, so nothing is lost when
   the form comes back with a red box. It is seeded from the row
   read out of MySQL, which is why the boxes already hold the
   current values. */
$old = [
    'name'           => '',
    'phone'          => '',
    'village'        => '',
    'milk_quantity'  => '',
    'fat_percentage' => '',
];

if ($farmerRow !== null) {
    $old['name']           = (string) $farmerRow['name'];
    $old['phone']          = (string) $farmerRow['phone'];
    $old['village']        = (string) $farmerRow['village'];
    $old['milk_quantity']  = (string) $farmerRow['milk_quantity'];
    $old['fat_percentage'] = (string) $farmerRow['fat_percentage'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $farmerId > 0) {

    /* --- 3a. READ the five visible fields and trim them --- */
    $old['name']           = postField('name');
    $old['phone']          = postField('phone');
    $old['village']        = postField('village');
    $old['milk_quantity']  = postField('milk_quantity');
    $old['fat_percentage'] = postField('fat_percentage');

    /* The id is NOT read from the address bar on this visit - it
       comes from the hidden field, so it is checked as well. */
    $farmerId = (int) postField('id');

    /* --- 3b. VALIDATE --- */
    $errors = runFarmerValidation($old);

    if (count($errors) === 0) {

        $farmerValues = buildFarmerValues($old);

        /* THE DATABASE WRITE. */
        $result = dbUpdateFarmer($connection, $farmerId, $farmerValues);

        if ($result['ok']) {
            dbFlashSet('ok', $result['message']);

            header('Location: farmer-list.php');
            exit;
        }

        $statusClass = 'error';
        $statusText = $result['message'] . ' The old values are still in the database.';

    } else {
        $statusClass = 'error';
        $statusText = count($errors) . ' value(s) were rejected by PHP, so the UPDATE '
                    . 'was not run at all. Please correct the red fields below.';
    }
} else {
    /* The neutral hint, shown while the form is being filled in. */
    $statusText = 'Change any field and press "Save Changes". PHP checks the five '
                . 'values, then runs one prepared UPDATE statement.';
    $statusClass = '';
}

/* Anything dbSelectFarmerById() or dbUpdateFarmer() parked here. */
$pageError = $GLOBALS['db_last_error'] ?? '';

$pageTitle = 'Update a Farmer (UPDATE)';
$pageSection = 'edit';
$pageTotal = dbFarmerSummary($connection)['farmer_count'];

require_once __DIR__ . '/db-header.php';
?>

    <section>
        <h2>Update a Farmer &mdash; SQL <code>UPDATE</code></h2>

        <p class="centre-note">
            ASSIGNMENT 13 &bull; the row is read with a prepared
            <code>SELECT ... WHERE id = ?</code> and changed with a prepared
            <code>UPDATE ... WHERE id = ?</code> &bull; the id is bound with the
            type letter <code>i</code> in both statements
        </p>

        <?php if ($pageError !== '') { ?>
        <p class="db-message db-error"><?= safeText($pageError) ?></p>
        <?php } ?>

        <?php if ($farmerRow === null) { ?>
        <!-- ============ NOTHING TO EDIT ============
             Reached when no id was given, or when the id named a row
             that does not exist. Nothing is printed as a form,
             because a form with empty boxes would invite the visitor
             to create a second farmer by accident. -->
        <p class="db-message db-error" id="edit-not-found">
            <?= safeText($notFoundMessage) ?>
        </p>

        <p>
            <a class="btn" href="farmer-list.php">Back to the farmer list</a>
            <a class="btn btn-light" href="farmer-create.php">Add a new farmer instead</a>
        </p>

        <?php } else { ?>

        <p class="db-message <?= $statusClass === 'error' ? 'db-error' : '' ?>" id="edit-status">
            Editing farmer number <strong><?= safeText((string) $farmerId) ?></strong>,
            saved on <?= safeText((string) $farmerRow['created_at']) ?>.
            &mdash; <?= safeText($statusText) ?>
        </p>

        <!-- ============ THE LIST OF REJECTED VALUES ============ -->
        <?php if (count($errors) > 0) { ?>
        <div class="db-error-box" id="edit-error-list">
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
             The action deliberately has no ?id= in it. The id is sent
             by the HIDDEN field below, which is the correct way: the
             value that decides WHICH row changes should come from the
             form, and it is still bound as a number, never as SQL. -->
        <form method="post" action="farmer-edit.php" class="farmer-form" id="farmer-edit-form">

            <div class="form-grid">

                <!-- ---- THE HIDDEN ID ----
                     type="hidden" is not visible to the farmer, but it
                     travels with the form exactly like a visible box.
                     Without it PHP would not know which farmer to
                     change, and the UPDATE would have no WHERE clause. -->
                <input type="hidden" id="edit-id" name="id" value="<?= safeText((string) $farmerId) ?>">

                <div class="form-row">
                    <label for="edit-name">Farmer Name <span class="required-mark">*</span></label>
                    <input type="text" id="edit-name" name="name"
                           value="<?= oldValue($old, 'name') ?>"
                           <?= isset($errors['name']) ? 'class="field-error"' : '' ?>
                           autocomplete="name"
                           required minlength="3" maxlength="60"
                           pattern="[A-Za-z][A-Za-z .'-]{2,}">
                    <p class="error-message"><?= safeText($errors['name'] ?? '') ?></p>
                </div>

                <div class="form-row">
                    <label for="edit-phone">Mobile Number <span class="required-mark">*</span></label>
                    <input type="tel" id="edit-phone" name="phone"
                           value="<?= oldValue($old, 'phone') ?>"
                           <?= isset($errors['phone']) ? 'class="field-error"' : '' ?>
                           autocomplete="tel"
                           inputmode="numeric" maxlength="10"
                           required pattern="[6-9][0-9]{9}">
                    <p class="field-hint">Another farmer may already hold this number - MySQL will refuse the change.</p>
                    <p class="error-message"><?= safeText($errors['phone'] ?? '') ?></p>
                </div>

                <div class="form-row">
                    <label for="edit-village">Village / Address <span class="required-mark">*</span></label>
                    <input type="text" id="edit-village" name="village"
                           value="<?= oldValue($old, 'village') ?>"
                           <?= isset($errors['village']) ? 'class="field-error"' : '' ?>
                           required minlength="3" maxlength="60">
                    <p class="error-message"><?= safeText($errors['village'] ?? '') ?></p>
                </div>

                <div class="form-row">
                    <label for="edit-milk">Average Milk per Day (L) <span class="required-mark">*</span></label>
                    <input type="number" id="edit-milk" name="milk_quantity"
                           value="<?= oldValue($old, 'milk_quantity') ?>"
                           <?= isset($errors['milk_quantity']) ? 'class="field-error"' : '' ?>
                           step="0.5" min="0.5" max="100"
                           required>
                    <p class="error-message"><?= safeText($errors['milk_quantity'] ?? '') ?></p>
                </div>

                <div class="form-row">
                    <label for="edit-fat">Fat Percentage <span class="required-mark">*</span></label>
                    <input type="number" id="edit-fat" name="fat_percentage"
                           value="<?= oldValue($old, 'fat_percentage') ?>"
                           <?= isset($errors['fat_percentage']) ? 'class="field-error"' : '' ?>
                           step="0.1" min="3" max="8"
                           required>
                    <p class="error-message"><?= safeText($errors['fat_percentage'] ?? '') ?></p>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn">Save Changes</button>
                    <!-- A reset button would throw away the values
                         that were just loaded from MySQL, so a plain
                         link back to the same id is used instead. -->
                    <a class="btn btn-light" href="farmer-edit.php?id=<?= safeText((string) $farmerId) ?>">Reload from MySQL</a>
                </div>

            </div>
        </form>

        <?php } ?>
    </section>

    <!-- ========================================================
         THE ROW AS IT STANDS NOW
         A <dl> description list printed straight from the array
         that the prepared SELECT returned, so the page proves
         where the values in the boxes came from.
         ======================================================== -->
    <?php if ($farmerRow !== null) {
        $currentMilk = (float) $farmerRow['milk_quantity'];
        $currentFat  = (float) $farmerRow['fat_percentage'];
    ?>
    <section>
        <h2>The Row as MySQL Holds It Right Now</h2>

        <dl class="profile-list">
            <dt>id</dt>
            <dd><?= safeText($farmerRow['id']) ?>
                <span class="field-hint">AUTO_INCREMENT - written by MySQL, never by the visitor</span></dd>

            <dt>name</dt>
            <dd><?= safeText($farmerRow['name']) ?> <span class="field-hint">VARCHAR(60) NOT NULL</span></dd>

            <dt>phone</dt>
            <dd><?= safeText($farmerRow['phone']) ?>
                <span class="field-hint">VARCHAR(15) NOT NULL UNIQUE - stored as text, so a leading zero would survive</span></dd>

            <dt>village</dt>
            <dd><?= safeText($farmerRow['village']) ?> <span class="field-hint">VARCHAR(60) NOT NULL</span></dd>

            <dt>milk_quantity</dt>
            <dd><?= safeNumber($currentMilk, 2) ?> L
                <span class="field-hint">DECIMAL(6,2) - exact, unlike FLOAT</span></dd>

            <dt>fat_percentage</dt>
            <dd><?= safeNumber($currentFat, 1) ?> %
                <span class="field-hint">DECIMAL(3,1) - paid at
                <?= safeNumber(rateForFat($currentFat), 2) ?> Rs/litre</span></dd>

            <dt>created_at</dt>
            <dd><?= safeText($farmerRow['created_at']) ?>
                <span class="field-hint">TIMESTAMP DEFAULT CURRENT_TIMESTAMP - the UPDATE does not touch it, so it still shows the ORIGINAL registration date</span></dd>
        </dl>
    </section>
    <?php } ?>

    <aside>
        <h3>Three things worth saying about this page</h3>
        <ul>
            <li><strong>Two statements, both prepared.</strong> The SELECT
                fills the boxes and the UPDATE saves them; the letters are
                <code>i</code> for the read and <code>sssddi</code> for the
                write.</li>
            <li><strong>created_at is not in the UPDATE.</strong> The column
                keeps the value it was given by
                <code>DEFAULT CURRENT_TIMESTAMP</code>, so the page can still
                show when the farmer first registered. This is worth a mark.</li>
            <li><strong>affected_rows.</strong> If the new values are
                identical to the old ones, MySQL reports 0 changed rows. The
                page says &quot;nothing was changed&rdquo; instead of
                pretending it worked &mdash; 0 is not an error here.</li>
        </ul>
    </aside>

<?php
dbDisconnect($connection);

require_once __DIR__ . '/db-footer.php';
