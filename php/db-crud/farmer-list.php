<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   OPERATION "READ"  ->  SQL: SELECT
   File: php/db-crud/farmer-list.php

   THE FLOW OF THIS PAGE
       1. read the optional search text from $_GET
       2. dbSelectFarmers() runs ONE prepared SELECT and returns
          every matching row as a PHP array
       3. a foreach loop prints one <tr> per row

   THE STATEMENT ACTUALLY SENT TO MySQL

       SELECT id, name, phone, village, milk_quantity,
              fat_percentage, created_at
       FROM   farmers
       WHERE  name LIKE ? OR village LIKE ? OR phone LIKE ?
       ORDER  BY id DESC

   Without a search box the WHERE line is simply left out, and the
   same prepared statement is used with no values bound at all.
   ============================================================ */

/* ---------- STEP 0 : SESSION AND THE HELPERS -------------------- */
session_start();

require_once __DIR__ . '/db-farmers.php';
require_once __DIR__ . '/db-validate.php';


/* ---------- STEP 1 : OPEN THE CONNECTION ------------------------ */
$connection = dbConnect();


/* ---------- STEP 2 : THE SEARCH TEXT ---------------------------- */

/* $_GET['q'] is what the visitor typed in the search box, for
   example farmer-list.php?q=Ramesh
   The ?? '' keeps the page working when there is no ?q= at all. */
$searchText = trim($_GET['q'] ?? '');

/* One prepared SELECT serves both cases: with the search text and
   without it. */
$farmers = dbSelectFarmers($connection, $searchText);

/* dbSelectFarmers() cannot put a red box next to a form field, so
   it parks any SQL reason in this variable instead. */
$listError = $GLOBALS['db_last_error'] ?? '';

/* The four summary cards. */
$summary = dbFarmerSummary($connection);

$pageTitle = 'All Farmers Stored in MySQL (SELECT)';
$pageSection = 'list';
$pageTotal = $summary['farmer_count'];

require_once __DIR__ . '/db-header.php';
?>

    <!-- ============ THE SEARCH BOX ============
         A plain GET form. GET is used (not POST) because a search
         can safely be repeated by pressing F5, and because the
         search text then appears in the address bar, which makes
         the prepared statement easy to demonstrate. -->
    <section>
        <h2>Farmer List &mdash; SQL <code>SELECT</code></h2>

        <p class="centre-note">
            ASSIGNMENT 13 &bull; the rows below were printed by a
            <code>foreach</code> loop over the array returned by
            <code>dbSelectFarmers()</code> &bull; no JavaScript is
            used on this page &bull; the search box is an ordinary
            <code>&lt;form method="get"&gt;</code>
        </p>

        <form method="get" action="farmer-list.php" class="db-search-form">
            <label for="db-search">Search by name, village or mobile number</label>
            <div class="db-search-row">
                <input type="search" id="db-search" name="q"
                       value="<?= safeText($searchText) ?>"
                       placeholder="e.g. Wadgaon or 9876543210"
                       maxlength="60">
                <button type="submit" class="btn">Search</button>
                <!-- The link clears the ?q= from the address bar,
                     which is what makes the search box empty again. -->
                <a class="btn btn-light" href="farmer-list.php">Show All</a>
            </div>
            <p class="field-hint">
                The text is never glued into the SQL. It is sent as a value and
                matched with <code>LIKE</code>, so the <code>?</code> marks in the
                statement and the <code>sss</code> type list always stay in step.
            </p>
        </form>

        <?php if ($listError !== '') { ?>
        <p class="db-message db-error"><?= safeText($listError) ?></p>
        <?php } ?>
    </section>

    <!-- ============ THE SUMMARY NUMBERS ============ -->
    <section>
        <h2>What the Table Contains</h2>

        <div class="flex-row">
            <div class="flex-item">
                <h3>Farmers Found</h3>
                <p class="db-value"><?= safeText((string) count($farmers)) ?></p>
                <p><?= $searchText === '' ? 'the whole table' : 'matching "' . safeText($searchText) . '"' ?></p>
            </div>
            <div class="flex-item">
                <h3>Milk per Day</h3>
                <p class="db-value"><?= safeNumber($summary['total_milk'], 2) ?> L</p>
                <p>SUM(milk_quantity) over the whole table</p>
            </div>
            <div class="flex-item">
                <h3>Average Fat</h3>
                <p class="db-value"><?= safeNumber($summary['average_fat'], 2) ?> %</p>
                <p>AVG(fat_percentage) over the whole table</p>
            </div>
            <div class="flex-item">
                <h3>Milk Value per Day</h3>
                <p class="db-value">Rs <?= safeNumber($summary['total_amount'], 2) ?></p>
                <p>Litres x the rate of each farmer's own fat</p>
            </div>
        </div>
    </section>

    <!-- ========================================================
         THE FARMER TABLE
         This is the READ of the assignment. Every cell is printed
         through safeText(), so a value stored in MySQL can never
         become HTML code when it is read back.
         ======================================================== -->
    <section>
        <h2>
            <?= $searchText === '' ? 'All Farmers' : 'Search Results' ?>
            (<?= safeText((string) count($farmers)) ?> row<?= count($farmers) === 1 ? '' : 's' ?>)
        </h2>

        <div class="log-window">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Village</th>
                        <th>Milk (L)</th>
                        <th>Fat %</th>
                        <th>Rate (Rs)</th>
                        <th>Amount (Rs)</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($farmers) === 0) { ?>
                    <!-- ============ EMPTY STATE ============
                         Two different reasons lead here: the table is
                         genuinely empty, or the search found nothing.
                         The message says which one it is. -->
                    <tr>
                        <td colspan="10">
                            <?php if ($searchText === '') { ?>
                                The table <code><?= safeText(FARMERS_TABLE) ?></code> is empty.
                                Run <code>database\schema.sql</code> for the 6 sample farmers,
                                or <a href="farmer-create.php">add the first one</a>.
                            <?php } else { ?>
                                No farmer matches &quot;<?= safeText($searchText) ?>&quot;.
                                <a href="farmer-list.php">Show all farmers</a> instead.
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } else { ?>

                        <?php foreach ($farmers as $farmer) {
                            /* The numbers arrive from MySQL as STRINGS
                               ("18.50"), so they are cast to float
                               before number_format() and the rate are
                               used on them. */
                            $milk = (float) $farmer['milk_quantity'];
                            $fat  = (float) $farmer['fat_percentage'];
                            $rate = rateForFat($fat);
                            $amount = dailyAmount($milk, $fat);
                        ?>
                        <tr>
                            <!-- The id is written into every link, so
                                 the Edit and Delete pages know which
                                 farmer they are working on. -->
                            <td><?= safeText($farmer['id']) ?></td>
                            <td><?= safeText($farmer['name']) ?></td>
                            <td><?= safeText($farmer['phone']) ?></td>
                            <td><?= safeText($farmer['village']) ?></td>
                            <td><?= safeNumber($milk, 2) ?></td>
                            <td><?= safeNumber($fat, 1) ?></td>
                            <td><?= safeNumber($rate, 2) ?></td>
                            <td><?= safeNumber($amount, 2) ?></td>
                            <td><?= safeText($farmer['created_at']) ?></td>
                            <td class="db-actions">
                                <a class="db-action-link"
                                   href="farmer-edit.php?id=<?= safeText($farmer['id']) ?>">Update</a>
                                <a class="db-action-link db-action-delete"
                                   href="farmer-delete.php?id=<?= safeText($farmer['id']) ?>">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <p class="register-status">
            The rate and the amount columns are <strong>not</strong> stored in
            MySQL &mdash; they are worked out by
            <code>rateForFat()</code> and <code>dailyAmount()</code> in
            <code>db-helpers.php</code>, using the same rate board as
            Assignments 6, 7, 11 and 12. A value that can be calculated should
            not be stored twice.
        </p>

        <p>
            <a class="btn" href="farmer-create.php">Add a New Farmer (INSERT)</a>
            <a class="btn btn-light" href="index.php">Back to the A13 home page</a>
        </p>
    </section>

    <aside>
        <h3>How this page proves the read is a real SELECT</h3>
        <ul>
            <li>Close the MySQL service, refresh this page and the rows are
                replaced by a written error &mdash; the rows cannot come from
                the HTML file, because this file contains no farmer at all.</li>
            <li>Open the table in phpMyAdmin while this page is open: both
                show the same rows, because they read the same table.</li>
            <li>Open phpMyAdmin's <em>SQL</em> tab and run the SELECT yourself.
                The same rows come back.</li>
            <li>Delete a row in phpMyAdmin and press F5 here: it disappears.
                The page holds no copy of its own.</li>
        </ul>
    </aside>

<?php
dbDisconnect($connection);

require_once __DIR__ . '/db-footer.php';
