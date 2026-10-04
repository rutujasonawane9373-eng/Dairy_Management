<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   THE DATABASE CONNECTION
   File: php/db-crud/db-connect.php

   WHAT THIS FILE IS
   ONE function, dbConnect(), that opens the connection to
   MySQL and gives the MySQLi object back to the page.

   MYSQLi OR PDO?
   Both can talk to MySQL. This project uses MYSQLi because its
   three steps are easy to show one by one in the viva:

       1. $connection->prepare($sql)   <-- write the SQL once
       2. $statement->bind_param(...)  <-- send the values apart
       3. $statement->execute()        <-- run it

   (PDO can do exactly the same with $pdo->prepare() and
   $statement->execute([...]). Both are accepted by the college.
   MYSQLi is chosen because the names above are the ones printed
   in most textbooks.)

   THE FIVE ARGUMENTS OF new mysqli()
       host, user, password, database, port
   They are not typed here - they are read from db-config.php,
   which is the only file that holds them.
   ============================================================ */

/* Load the settings. db-connect.php uses three constants
   (DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_CHARSET),
   and so does every other file of this assignment. */
require_once __DIR__ . '/db-config.php';


/* ============================================================
   PART 1 : PRINT A FRIENDLY ERROR PAGE AND STOP
   ------------------------------------------------------------
   If the database cannot be reached, the honest thing to do is
   SAY SO on the screen. A blank white page would look like a
   broken program; a written message explains what happened.

   This function prints its own complete HTML page on purpose:
   it must be able to work even when the rest of the project
   (db-header.php) cannot be loaded. It then stops the script
   with exit, because there is nothing left to run.
   ============================================================ */

/**
 * Print a styled "something went wrong" page and stop the script.
 *
 * @param string $title   short heading for the page
 * @param string $reason  the technical sentence shown in <code>
 * @param string $fix     what the user should actually do about it
 *
 * @return void  (this function never returns - it calls exit)
 */
function dbShowError(string $title, string $reason, string $fix): void
{
    /* http_response_code() tells the browser the page did not work.
       500 = "Internal Server Error". It does not print anything,
       it only sets the status of the HTTP response. */
    http_response_code(500);

    /* The one and only rule for printing untrusted text is used
       here too, so a technical message can never become HTML. */
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeReason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
    $safeFix = htmlspecialchars($fix, ENT_QUOTES, 'UTF-8');

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Error | Dairy Management System</title>

    <!-- The SAME stylesheet as the rest of the project, so even the
         error page keeps the dairy colours. Two ".." steps up go
         from php/db-crud/ to the project root, then into frontend/. -->
    <link rel="stylesheet" href="../../frontend/css/style.css">
</head>
<body class="page-db">
    <header>
        <h1>Dairy Management System</h1>
        <p>Assignment 13 (PHP + MySQL) - database connection problem</p>
    </header>

    <main>
        <section>
            <h2><?= $safeTitle ?></h2>

            <p class="db-message db-error">
                The page could not be shown because the database is not available.
            </p>

            <h3>The technical reason</h3>
            <p class="db-code"><code><?= $safeReason ?></code></p>

            <h3>What to check</h3>
            <p><?= $safeFix ?></p>

            <h3>Remember these four things</h3>
            <ol>
                <li>Is the MySQL service actually running?
                    <code>services.msc</code> &rarr; <code>MySQL80</code> &rarr; Start.</li>
                <li>Does the database exist? Run
                    <code>database\schema.sql</code> once.</li>
                <li>Is <code>DB_USER</code> / <code>DB_PASS</code> in
                    <code>db-config.php</code> correct?</li>
                <li>Is PHP's <code>mysqli</code> extension enabled?
                    Check with <code>php -m</code>.</li>
            </ol>

            <p>
                <a class="btn" href="index.php">Try the connection again</a>
                <a class="btn btn-light" href="../../frontend/index.html">Back to the home page</a>
            </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Dairy Management System - College Web Development Project</p>
    </footer>
</body>
</html>
    <?php

    /* exit stops the script immediately. Without it PHP would carry
       on and print the rest of the page under the error message. */
    exit;
}


/* ============================================================
   PART 2 : OPEN THE CONNECTION
   ------------------------------------------------------------
   dbConnect() is called at the top of every page of this
   assignment, in the same way that session_start() is called at
   the top of every page of Assignment 12.

   WHY try / catch AND NOT "if ($connection->connect_error)"?
   Since PHP 8.1 the mysqli extension reports problems by THROWING
   an exception instead of only setting an error flag. Old tutorials
   (and older PHP books) use connect_error, which still works if
   mysqli_report(MYSQLI_REPORT_OFF) is set first. try / catch needs
   no such switch, so it is the version used here.

   WHAT new mysqli() RETURNS
   On success  : a connection object ($connection).
   On failure  : it throws mysqli_sql_exception, which is caught
                 below, so $connection is never a broken object.
   ============================================================ */

/**
 * Open the connection to MySQL and return the MySQLi object.
 *
 * @return mysqli  a live connection, or the error page + exit
 */
function dbConnect(): mysqli
{
    try {
        /* THE CONNECTION LINE - the whole of "database connectivity"
           in one statement. Five values, all read from db-config.php:

              DB_HOST   ->  where the server is        (127.0.0.1)
              DB_USER   ->  which MySQL account        (root)
              DB_PASS   ->  its password               ('')
              DB_NAME   ->  which database to open     (dairy_management)
              DB_PORT   ->  which door to knock on     (3306)
        */
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

        /* set_charset() must be called straight after connecting.
           It tells the server "everything I send you is utf8mb4",
           so a village name written in Devanagari is stored and
           read back correctly. It is the single most common cause
           of "question marks instead of letters" in a PHP project. */
        $connection->set_charset(DB_CHARSET);

        return $connection;

    } catch (mysqli_sql_exception $problem) {
        /* getMessage() is the sentence the MySQL server sent,
           for example "Access denied for user 'root'@'localhost'". */
        dbShowError(
            'Cannot connect to the database',
            $problem->getMessage(),
            'Open php/db-crud/db-config.php and check DB_USER, DB_PASS, '
            . 'DB_NAME and DB_PORT. Then check that MySQL is running and '
            . 'that the database dairy_management exists.'
        );
    }

    /* dbShowError() always calls exit, so control never reaches here.
       The line below only exists so that PHP does not complain that
       $connection was not defined. */
    return $connection;
}


/* ============================================================
   PART 3 : CLOSE THE CONNECTION WHEN THE PAGE IS FINISHED
   ------------------------------------------------------------
   A connection is like a telephone call: it should be hung up.
   db-farmers.php ends every function with $statement->close(),
   which closes one SQL statement, and the connection itself is
   closed automatically when the page finishes - PHP closes it at
   the end of the request. These two lines make that explicit and
   are good practice in long scripts.
   ============================================================ */

/**
 * Close the connection politely.
 */
function dbDisconnect(mysqli $connection): void
{
    $connection->close();
}


/* ============================================================
   PART 4 : SMALL RESULT HELPER
   ------------------------------------------------------------
   Every function in db-farmers.php returns the same shape:

        array('ok' => true|false, 'message' => 'one sentence')

   So the calling page only has to ask two questions:
        if ($result['ok'] === false) { show the error }
        else                        { show the success }
   Using one fixed shape everywhere keeps the pages short.
   ============================================================ */

/**
 * Build the standard result array.
 *
 * @param bool   $ok      did the operation work?
 * @param string $message the sentence to show on the page
 *
 * @return array
 */
function dbResult(bool $ok, string $message): array
{
    return ['ok' => $ok, 'message' => $message];
}
