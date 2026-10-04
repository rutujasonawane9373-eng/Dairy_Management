<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   PAGE 1 : THE START PAGE AND THE CONNECTION CHECK
   File: php/db-crud/index.php

   This page has no form. It does three things:

     1. OPENS the connection to MySQL and says plainly whether it
        worked. If it did not, dbConnect() prints the error page
        and stops - this file never carries on.
     2. SHOWS what is inside the database: which server, which
        database, which table, how many rows.
     3. EXPLAINS where each of the four CRUD operations lives, so
        the page doubles as the map of the assignment.

   RUN IT
       cd C:\...\Dairy_Management
       php -S localhost:8000
       open http://localhost:8000/php/db-crud/

   The server must be started from the PROJECT ROOT, not from
   inside php/db-crud/, because the menu links go up two levels to
   frontend/css/style.css.
   ============================================================ */

/* ---------- STEP 0 : SESSION FIRST, THEN THE HELPERS ----------- */
/* The session is needed by the one-time message of db-header.php.
   It is started before anything is printed, exactly as in
   Assignment 12, because header() cannot be used after output. */
session_start();

/* db-farmers.php loads db-connect.php, which loads db-config.php,
   so this ONE require brings in the whole chain. */
require_once __DIR__ . '/db-farmers.php';
require_once __DIR__ . '/db-validate.php';


/* ---------- STEP 1 : OPEN THE CONNECTION ------------------------ */

/* dbConnect() either returns a working mysqli object, or prints
   the "Cannot connect to the database" page and calls exit().
   Because of exit(), the rest of this file only ever runs with a
   live connection in $connection. */
$connection = dbConnect();


/* ---------- STEP 2 : ASK THE DATABASE ABOUT ITSELF ------------- */

/* The details of the running MySQL server. These four lines are
   the visible proof that the connection really worked - they are
   the numbers the server itself reports. */
$serverVersion = 'unknown';
$currentUser   = 'unknown';
$currentDb     = 'unknown';

try {
    /* query() runs one fixed sentence that has no typed text in it,
       so no question mark and no prepared statement is needed. */
    $serverResult = $connection->query("SELECT VERSION() AS version, USER() AS who, DATABASE() AS db_name");

    if ($serverResult !== false) {
        $serverRow = $serverResult->fetch_assoc();
        $serverResult->free();

        $serverVersion = (string) $serverRow['version'];
        $currentUser   = (string) $serverRow['who'];
        $currentDb     = (string) $serverRow['db_name'];
    }
} catch (mysqli_sql_exception $problem) {
    /* This should not happen once the connection is open, but a
       catch block costs nothing and stops a warning from reaching
       the visitor. */
    $serverVersion = 'could not be read';
}

/* The summary numbers of the farmers table (COUNT, SUM, AVG). */
$summary = dbFarmerSummary($connection);

/* The page title and the menu link to mark. */
$pageTitle = 'PHP + MySQL Database Connectivity and CRUD';
$pageSection = 'home';
$pageTotal = $summary['farmer_count'];

require_once __DIR__ . '/db-header.php';
?>

    <!-- ==========================================================
         1. THE CONNECTION CHECK
         The first thing to show, because every later step depends
         on it. Each line is a number the MySQL server reported
         about itself.
         ========================================================== -->
    <section>
        <h2>1. The Database Connection</h2>

        <p class="centre-note">
            <code>dbConnect()</code> in <code>db-connect.php</code> opens the
            connection with <code>new mysqli(host, user, password, database, port)</code>
            and the five values come from <code>db-config.php</code>. If this
            section is visible, the connection worked.
        </p>

        <div class="flex-row">
            <div class="flex-item">
                <h3>Connection</h3>
                <p class="db-value"><?= $pageTotal === null ? '?' : 'OPEN' ?></p>
                <p>MySQL answered on <code><?= safeText(DB_HOST) ?>:<?= safeText((string) DB_PORT) ?></code></p>
            </div>
            <div class="flex-item">
                <h3>MySQL Version</h3>
                <p class="db-value"><?= safeText($serverVersion) ?></p>
                <p>Reported by <code>SELECT VERSION()</code></p>
            </div>
            <div class="flex-item">
                <h3>Logged in as</h3>
                <p class="db-value"><?= safeText($currentUser) ?></p>
                <p>Reported by <code>USER()</code></p>
            </div>
            <div class="flex-item">
                <h3>Open Database</h3>
                <p class="db-value"><?= safeText($currentDb) ?></p>
                <p>Reported by <code>DATABASE()</code></p>
            </div>
        </div>

        <p class="register-status">
            Character set in use: <strong><code><?= safeText($connection->character_set_name()) ?></code></strong>
            &mdash; set by <code>$connection-&gt;set_charset('<?= safeText(DB_CHARSET) ?>')</code>
            in <code>db-connect.php</code>, so Indian names are stored and read
            back correctly.
        </p>
    </section>

    <!-- ==========================================================
         2. THE DATABASE AND THE TABLE
         ========================================================== -->
    <section>
        <h2>2. The Database and Its Table</h2>

        <p class="centre-note">
            One database, one table. The whole SQL that created them is in
            <code>database/schema.sql</code>; every row shown in this project
            since is a row of this one table.
        </p>

        <div class="log-window">
            <table>
                <thead>
                    <tr>
                        <th>Column</th>
                        <th>MySQL type</th>
                        <th>What it stores</th>
                        <th>Rule</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>id</code></td>
                        <td><code>INT AUTO_INCREMENT PRIMARY KEY</code></td>
                        <td>The farmer number, given by MySQL itself</td>
                        <td>Never empty, never repeated</td>
                    </tr>
                    <tr>
                        <td><code>name</code></td>
                        <td><code>VARCHAR(60)</code></td>
                        <td>The farmer's full name</td>
                        <td><code>NOT NULL</code></td>
                    </tr>
                    <tr>
                        <td><code>phone</code></td>
                        <td><code>VARCHAR(15)</code></td>
                        <td>The 10-digit mobile number, kept as text</td>
                        <td><code>NOT NULL</code> + <code>UNIQUE</code></td>
                    </tr>
                    <tr>
                        <td><code>village</code></td>
                        <td><code>VARCHAR(60)</code></td>
                        <td>Village or address of the farmer</td>
                        <td><code>NOT NULL</code></td>
                    </tr>
                    <tr>
                        <td><code>milk_quantity</code></td>
                        <td><code>DECIMAL(6,2)</code></td>
                        <td>Average litres of milk per day</td>
                        <td><code>NOT NULL</code> + <code>CHECK &gt; 0</code></td>
                    </tr>
                    <tr>
                        <td><code>fat_percentage</code></td>
                        <td><code>DECIMAL(3,1)</code></td>
                        <td>Fat of the milk, 3% to 8%</td>
                        <td><code>NOT NULL</code> + <code>CHECK</code></td>
                    </tr>
                    <tr>
                        <td><code>created_at</code></td>
                        <td><code>TIMESTAMP</code></td>
                        <td>Date and time of the registration</td>
                        <td>Filled in by MySQL itself</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex-row">
            <div class="flex-item">
                <h3>Farmers in the table</h3>
                <p class="db-value"><?= safeText((string) $summary['farmer_count']) ?></p>
                <p><code>SELECT COUNT(*)</code></p>
            </div>
            <div class="flex-item">
                <h3>Milk per day</h3>
                <p class="db-value"><?= safeNumber($summary['total_milk'], 2) ?> L</p>
                <p><code>SUM(milk_quantity)</code></p>
            </div>
            <div class="flex-item">
                <h3>Average fat</h3>
                <p class="db-value"><?= safeNumber($summary['average_fat'], 2) ?> %</p>
                <p><code>AVG(fat_percentage)</code></p>
            </div>
            <div class="flex-item">
                <h3>Milk value per day</h3>
                <p class="db-value">Rs <?= safeNumber($summary['total_amount'], 2) ?></p>
                <p>Litres x the rate of each farmer's own fat</p>
            </div>
        </div>
    </section>

    <!-- ==========================================================
         3. WHERE EACH OF THE FOUR OPERATIONS LIVES
         ========================================================== -->
    <section>
        <h2>3. The Four Operations (CRUD)</h2>

        <p class="centre-note">
            All four SQL statements are written in <code>php/db-crud/db-farmers.php</code>
            and every one of them is a prepared statement.
        </p>

        <div class="log-window">
            <table>
                <thead>
                    <tr>
                        <th>CRUD letter</th>
                        <th>SQL command</th>
                        <th>Function in <code>db-farmers.php</code></th>
                        <th>Page that calls it</th>
                        <th>Type letters bound</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Create</strong></td>
                        <td><code>INSERT</code></td>
                        <td><code>dbInsertFarmer()</code></td>
                        <td><a href="farmer-create.php">farmer-create.php</a></td>
                        <td><code>sssdd</code></td>
                    </tr>
                    <tr>
                        <td><strong>Read</strong></td>
                        <td><code>SELECT</code></td>
                        <td><code>dbSelectFarmers()</code>, <code>dbSelectFarmerById()</code></td>
                        <td><a href="farmer-list.php">farmer-list.php</a>, <a href="farmer-edit.php">farmer-edit.php</a></td>
                        <td><code>sss</code> and <code>i</code></td>
                    </tr>
                    <tr>
                        <td><strong>Update</strong></td>
                        <td><code>UPDATE</code></td>
                        <td><code>dbUpdateFarmer()</code></td>
                        <td><a href="farmer-edit.php">farmer-edit.php</a></td>
                        <td><code>sssddi</code></td>
                    </tr>
                    <tr>
                        <td><strong>Delete</strong></td>
                        <td><code>DELETE</code></td>
                        <td><code>dbDeleteFarmer()</code></td>
                        <td><a href="farmer-delete.php">farmer-delete.php</a></td>
                        <td><code>i</code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p>
            <a class="btn" href="farmer-create.php">Try the INSERT</a>
            <a class="btn btn-light" href="farmer-list.php">See the stored rows</a>
        </p>
    </section>

    <!-- ==========================================================
         4. WHAT THE PROJECT DOES NOT USE
         ========================================================== -->
    <section>
        <h2>4. What This Assignment Deliberately Does Not Use</h2>

        <ul>
            <li><strong>No Node.js and no Express</strong> &mdash; that is
                Assignment 14, and the REST API is Assignment 15.</li>
            <li><strong>No PDO</strong> &mdash; MySQLi answers the same
                question with three steps that are easier to show.</li>
            <li><strong>No JavaScript on these pages</strong> &mdash; the
                farmer list is drawn by a <code>foreach</code> loop in PHP,
                and the search box is a plain <code>&lt;form method="get"&gt;</code>.</li>
            <li><strong>No string-built SQL</strong> &mdash; not one
                statement anywhere in this assignment puts a typed value
                into the SQL text.</li>
            <li><strong>No login page</strong> &mdash; every visitor can
                add, change and delete a farmer, which is fine for a
                college project and would of course need a session check
                in a real society.</li>
        </ul>
    </section>

    <aside>
        <h3>If the connection fails, check these four things in order</h3>
        <ol>
            <li><strong>Is MySQL running?</strong> Open
                <code>services.msc</code>, find <code>MySQL80</code> and
                start it if it is stopped.</li>
            <li><strong>Does the database exist?</strong> Run
                <code>database\schema.sql</code> once. Without it,
                <code>dbConnect()</code> reports
                <em>Unknown database 'dairy_management'</em>.</li>
            <li><strong>Is the password right?</strong> <code>DB_PASS</code>
                in <code>php/db-crud/db-config.php</code> must match the
                password of the MySQL account named by <code>DB_USER</code>.</li>
            <li><strong>Is PHP's MySQL driver there?</strong> Run
                <code>php -m</code> and look for <code>mysqli</code>.
                If it is missing, enable <code>extension=mysqli</code> in
                <code>php.ini</code> and restart the server.</li>
        </ol>
    </aside>

<?php
/* Close the connection politely at the end of the page. PHP would
   do it by itself, but writing it down is good practice. */
dbDisconnect($connection);

require_once __DIR__ . '/db-footer.php';
