<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   OPERATION "DELETE"  ->  SQL: DELETE
   File: php/db-crud/farmer-delete.php

   WHY THIS PAGE HAS TWO STEPS
   A DELETE cannot be undone by reloading the page, so the page is
   built as a CONFIRM screen:

       step 1  farmer-delete.php?id=4          (GET)
               read the row with a prepared SELECT and show who is
               about to be removed, with the farmer's name typed
               into the confirmation box

       step 2  farmer-delete.php                (POST)
               run ONE prepared DELETE

   Two steps is the honest beginner answer to "what if I press the
   wrong button". In a real society the second step would also
   check that the person is logged in, because at the moment
   anybody who opens this page can delete a record.

   THE STATEMENT ACTUALLY SENT TO MySQL

       DELETE FROM farmers WHERE id = ?
   ============================================================ */

/* ---------- STEP 0 : SESSION AND THE HELPERS -------------------- */
session_start();

require_once __DIR__ . '/db-farmers.php';
require_once __DIR__ . '/db-validate.php';


/* ---------- STEP 1 : OPEN THE CONNECTION ------------------------ */
$connection = dbConnect();


/* ---------- STEP 2 : WHICH FARMER, AND WHAT STAGE? -------------- */

/* Two ways in:
     - a GET with ?id=4   -> show the confirmation screen
     - a POST with the id -> do the delete
   Both are read into one variable, so there is a single place that
   answers "which farmer?". */
$farmerId = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

/* Did the visitor really press the "Yes, delete" button? */
$deleteConfirmed = ($_SERVER['REQUEST_METHOD'] === 'POST');

$farmerRow = null;
$notFoundMessage = '';
$statusText = '';
$statusClass = '';


/* ---------- STEP 3 : READ THE ROW, OR DELETE IT ---------------- */

if ($farmerId > 0) {

    /* THE PREPARED SELECT of this page. It is needed on BOTH
       stages: to show who is about to be deleted, and to check
       after a delete whether anything was actually removed. */
    $farmerRow = dbSelectFarmerById($connection, $farmerId);

    if ($farmerRow === null && !$deleteConfirmed) {
        $notFoundMessage = 'No farmer was found with number ' . $farmerId
            . ', so there is nothing to delete. It may already have been removed.';
    }

    if ($deleteConfirmed) {

        /* ---------- STAGE 2 : REALLY DELETE ---------- */

        /* The row is re-read on purpose. If it is already gone, the
           delete is skipped and a clear message is printed, instead
           of running a DELETE that changes nothing. */
        if ($farmerRow === null) {
            $statusClass = 'error';
            $statusText = 'Farmer number ' . $farmerId . ' was not found, so no DELETE '
                        . 'statement was run.';
        } else {

            /* Remember the name BEFORE the row disappears, so the
               confirmation sentence can still name the farmer. */
            $deletedName = (string) $farmerRow['name'];

            /* ---------- THE CONFIRMATION CHECK ----------
               This is validation like any other: read the answer,
               compare it with the real name, and refuse if it does
               not match.

               strcasecmp() compares two strings ignoring capital
               letters and returns 0 when they are the same, so
               "ramesh patil" is accepted for "Ramesh Patil".
               A person must still type something, and it must be the
               whole name - a single letter would otherwise pass. */
            $typedConfirmation = postField('confirm_name');

            if ($typedConfirmation === '') {
                $statusClass = 'error';
                $statusText = 'Nothing was deleted, because the confirmation box was '
                            . 'empty. Type "' . $deletedName . '" and press the red '
                            . 'button again.';

            } elseif (strcasecmp($typedConfirmation, $deletedName) !== 0) {
                $statusClass = 'error';
                $statusText = 'Nothing was deleted, because the typed name does not '
                            . 'match "' . $deletedName . '". Check the spelling and '
                            . 'confirm again.';

            } else {

                /* THE DATABASE WRITE - reached only when the
                   confirmation was typed correctly. */
                $result = dbDeleteFarmer($connection, $farmerId);

                if ($result['ok']) {
                    dbFlashSet('ok', $result['message']
                        . ' ' . $deletedName . ' is no longer in the '
                        . FARMERS_TABLE . ' table.');

                    header('Location: farmer-list.php');
                    exit;
                }

                $statusClass = 'error';
                $statusText = $result['message'];
            }
        }

        /* After a failed delete the row is read again so the
           confirmation screen can still be printed below. */
        $farmerRow = dbSelectFarmerById($connection, $farmerId);
    }

} else {
    $notFoundMessage = 'No farmer number was given. Open this page from the Delete '
        . 'link of a row in the farmer list, or add the id to the address bar, '
        . 'for example farmer-delete.php?id=1';
}

$pageError = $GLOBALS['db_last_error'] ?? '';

$pageTitle = 'Delete a Farmer (DELETE)';
$pageSection = 'delete';
$pageTotal = dbFarmerSummary($connection)['farmer_count'];

require_once __DIR__ . '/db-header.php';
?>

    <section>
        <h2>Delete a Farmer &mdash; SQL <code>DELETE</code></h2>

        <p class="centre-note">
            ASSIGNMENT 13 &bull; the row is read with a prepared
            <code>SELECT ... WHERE id = ?</code> and removed with a prepared
            <code>DELETE ... WHERE id = ?</code> &bull; the id is bound with
            <code>i</code> in both &bull; the delete happens only after a second,
            deliberate press
        </p>

        <?php if ($pageError !== '') { ?>
        <p class="db-message db-error"><?= safeText($pageError) ?></p>
        <?php } ?>

        <?php if ($statusText !== '') { ?>
        <p class="db-message <?= $statusClass === 'error' ? 'db-error' : 'db-ok' ?>" id="delete-status">
            <?= safeText($statusText) ?>
        </p>
        <?php } ?>

        <?php if ($farmerRow === null) { ?>
        <!-- ============ NOTHING TO DELETE ============ -->
        <p class="db-message db-error" id="delete-not-found">
            <?= safeText($notFoundMessage) ?>
        </p>

        <p>
            <a class="btn" href="farmer-list.php">Back to the farmer list</a>
            <a class="btn btn-light" href="index.php">Back to the A13 home page</a>
        </p>

        <?php } else {
            /* ============ THE CONFIRMATION SCREEN ============ */
            $deleteMilk = (float) $farmerRow['milk_quantity'];
            $deleteFat  = (float) $farmerRow['fat_percentage'];
        ?>

        <div class="db-danger-box" id="delete-warning">
            <p>
                <strong>Careful.</strong> The farmer below is about to be
                removed from the table permanently.
            </p>
            <p>
                There is no "undo" in SQL &mdash; <code>DELETE</code> takes the
                row away and only a backup can bring it back. This page
                therefore asks twice.
            </p>
        </div>

        <!-- What is about to be lost. Printed from the row the
             prepared SELECT returned. -->
        <dl class="profile-list">
            <dt>id</dt>
            <dd><?= safeText($farmerRow['id']) ?></dd>

            <dt>name</dt>
            <dd><?= safeText($farmerRow['name']) ?></dd>

            <dt>phone</dt>
            <dd><?= safeText($farmerRow['phone']) ?></dd>

            <dt>village</dt>
            <dd><?= safeText($farmerRow['village']) ?></dd>

            <dt>milk_quantity</dt>
            <dd><?= safeNumber($deleteMilk, 2) ?> litres a day</dd>

            <dt>fat_percentage</dt>
            <dd><?= safeNumber($deleteFat, 1) ?> %</dd>

            <dt>Daily milk value that will be lost</dt>
            <dd>Rs <?= safeNumber(dailyAmount($deleteMilk, $deleteFat), 2) ?></dd>

            <dt>created_at</dt>
            <dd><?= safeText($farmerRow['created_at']) ?></dd>
        </dl>

        <!-- ============ THE CONFIRMATION FORM ============
             There is no Cancel button of type="submit", because that
             would look like a second way to delete. "Keep the farmer"
             is a plain LINK, so the only thing on this page that can
             reach the database is the red button. -->
        <form method="post" action="farmer-delete.php" id="farmer-delete-form">

            <!-- The same hidden-id trick as farmer-edit.php. -->
            <input type="hidden" name="id" value="<?= safeText((string) $farmerId) ?>">

            <div class="db-confirm-row">
                <label for="confirm-name">
                    To confirm, type the farmer's name exactly:
                    <strong><?= safeText($farmerRow['name']) ?></strong>
                </label>
                <input type="text" id="confirm-name" name="confirm_name"
                       placeholder="type the name to enable the button"
                       autocomplete="off">
                <p class="field-hint">
                    This box is checked by PHP on the next step. A page made only
                    of HTML cannot do this check on its own, because the button
                    would be pressed before any HTML check runs.
                </p>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-danger">Yes, delete this farmer</button>
                <a class="btn btn-light" href="farmer-list.php">Keep the farmer (cancel)</a>
            </div>
        </form>

        <?php } ?>
    </section>

    <section>
        <h2>How the Two-Step Confirmation Works</h2>

        <div class="flex-row">
            <div class="flex-item">
                <h3>Step 1 &mdash; the question</h3>
                <p>The page is opened with <code>?id=4</code>. PHP runs one
                   prepared <code>SELECT</code> and prints the farmer.</p>
                <p>Nothing is deleted yet.</p>
            </div>
            <div class="flex-item">
                <h3>Step 2 &mdash; the answer</h3>
                <p>The name is typed and the red button sends the form back
                   with <code>method="post"</code> and the same id in a hidden
                   field.</p>
                <p>The row is read once more, so a farmer deleted from another
                   tab in the meantime is not reported as a success.</p>
            </div>
            <div class="flex-item">
                <h3>Step 3 &mdash; the statement</h3>
                <p><code>DELETE FROM farmers WHERE id = ?</code> with the id
                   bound as an integer.</p>
                <p><code>affected_rows</code> is checked: 0 means "there was no
                   such row", which is reported as a failure rather than a
                   success.</p>
            </div>
        </div>

        <p class="db-code">
            <code>
                DELETE FROM farmers WHERE id = ?;<br>
                -- bind_param('i', 4)
            </code>
        </p>
    </section>

    <aside>
        <h3>Known limits, said honestly</h3>
        <ul>
            <li><strong>No login check.</strong> Anybody who can reach this
                page can delete a record. A real society would put
                <code>session_start()</code> + an
                <code>if (!isset($_SESSION['staff'])) { ... }</code> guard
                here. Assignment 12 already shows the session half of that.</li>
            <li><strong>No CSRF token.</strong> The confirmation box is a
                speed bump for a human, not a token. A real form would send a
                random code in the session and compare it.</li>
            <li><strong>No soft delete.</strong> A column such as
                <code>is_active</code> would hide a row without destroying it.
                That is a genuine design decision, not a missing feature.</li>
        </ul>
    </aside>

<?php
dbDisconnect($connection);

require_once __DIR__ . '/db-footer.php';
