<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   THE FOUR CRUD OPERATIONS
   File: php/db-crud/db-farmers.php

   THE WHOLE ASSIGNMENT IS IN THIS ONE FILE.

   CRUD
     Create  -> INSERT   -> dbInsertFarmer()
     Read    -> SELECT   -> dbSelectFarmers(), dbSelectFarmerById()
     Update  -> UPDATE   -> dbUpdateFarmer()
     Delete  -> DELETE   -> dbDeleteFarmer()

   EVERY STATEMENT USES A PREPARED STATEMENT
   ------------------------------------------------------------
   THE WRONG WAY (never used anywhere in this project) -
   the typed value is glued into the SQL text:

       $sql = "INSERT INTO farmers (name) VALUES ('" . $name . "')";
       $connection->query($sql);

   If a farmer typed   '; DROP TABLE farmers; --   the value
   would no longer be data, it would become part of the command.
   That is SQL INJECTION.

   THE RIGHT WAY (used in all five functions below) -
   the SQL is written ONCE with question marks, and the values
   travel to the server SEPARATELY:

       $sql = "INSERT INTO farmers (name, ...) VALUES (?, ?, ?, ?, ?)";
       $statement = $connection->prepare($sql);
       $statement->bind_param('sssdd', ...);
       $statement->execute();

   The server never mixes the two, so no typed text can ever
   become a command. The values are also sent to the database as
   DATA TYPES (string / integer / double) instead of as text,
   which is faster and safer.

   THE THREE STEPS, ALWAYS IN THIS ORDER
       1. prepare()  - send the SQL with the ? marks
       2. bind_param() - say what type each ? is, and give the values
       3. execute()  - run it

   THE TYPE LETTERS USED HERE
       s  string   -> name, phone, village
       i  integer  -> id
       d  double   -> milk_quantity, fat_percentage
   The letters must appear in the SAME ORDER as the question
   marks in the SQL. A common viva mistake is to forget this.
   ============================================================ */

/* Every file declares the files it actually uses, so the pages
   only have to require this one.

     db-config.php   DB_HOST, DB_USER, DB_PASS, ... and FARMERS_TABLE
                     (loaded by db-connect.php)
     db-connect.php  dbConnect(), dbResult(), dbShowError(), dbDisconnect()
     db-helpers.php  dailyAmount() and rateForFat(), used by
                     dbFarmerSummary() below

   Without this second require the "Call to undefined function
   dailyAmount()" fatal error appears, because the pages call
   dbFarmerSummary() BEFORE they include db-header.php. */
require_once __DIR__ . '/db-connect.php';
require_once __DIR__ . '/db-helpers.php';


/* ============================================================
   OPERATION 1 : CREATE  ->  INSERT
   dbInsertFarmer()
   ------------------------------------------------------------
   Called by farmer-create.php after the five values have passed
   the PHP validation.

   NOTE THE MISSING created_at IN THE SQL: the column has the
   default CURRENT_TIMESTAMP, so MySQL fills it in by itself and
   PHP never has to know the date.
   ============================================================ */

/**
 * Insert one new farmer into the farmers table.
 *
 * @param mysqli $connection an open connection from dbConnect()
 * @param array  $farmer     the five values from buildFarmerValues()
 *
 * @return array  array('ok' => bool, 'message' => string)
 */
function dbInsertFarmer(mysqli $connection, array $farmer): array
{
    /* FARMERS_TABLE is the constant 'farmers' from db-config.php,
       so the table name is written only once in the project.
       The "?" marks are the five holes the values will fill. */
    $sql = "INSERT INTO " . FARMERS_TABLE . "
                (name, phone, village, milk_quantity, fat_percentage)
            VALUES (?, ?, ?, ?, ?)";

    try {
        /* ---- STEP 1 : PREPARE ---- */
        $statement = $connection->prepare($sql);

        if ($statement === false) {
            return dbResult(false, 'The INSERT statement could not be prepared.');
        }

        /* ---- STEP 2 : BIND ----
           'sssdd' is the type of the five question marks:
             s -> name            (text)
             s -> phone           (text, so the leading zero survives)
             s -> village         (text)
             d -> milk_quantity   (number)
             d -> fat_percentage  (number) */
        $statement->bind_param(
            'sssdd',
            $farmer['name'],
            $farmer['phone'],
            $farmer['village'],
            $farmer['milk_quantity'],
            $farmer['fat_percentage']
        );

        /* ---- STEP 3 : EXECUTE ---- */
        $statement->execute();

        /* insert_id is the AUTO_INCREMENT number MySQL just gave
           to the new row - 1, then 2, then 3... It is the fastest
           way to learn the id of a row that was just inserted. */
        $newFarmerId = $connection->insert_id;

        /* close() finishes this one statement. */
        $statement->close();

        return dbResult(
            true,
            'Farmer saved in the database. The new record number is ' . $newFarmerId . '.'
        );

    } catch (mysqli_sql_exception $problem) {
        /* 1062 is MySQL's "Duplicate entry" error. It arrives when
           the phone number is already in the table, because that
           column has a UNIQUE key. Turning the error number into
           a friendly sentence is the difference between a usable
           application and a screen full of SQL. */
        if ($problem->getCode() === 1062) {
            return dbResult(
                false,
                'This mobile number is already registered. Please check the number and try again.'
            );
        }

        return dbResult(false, 'The farmer could not be saved: ' . $problem->getMessage());
    }
}


/* ============================================================
   OPERATION 2 : READ  ->  SELECT  (THE LIST)
   dbSelectFarmers()
   ------------------------------------------------------------
   Called by farmer-list.php. The optional search box adds a
   WHERE clause, which is why this function is also the place
   where a prepared statement really earns its keep: the LIKE
   pattern contains text typed by the user.
   ============================================================ */

/**
 * Read every farmer, or only those matching a search text.
 *
 * @param mysqli  $connection an open connection
 * @param string  $searchText name / village / phone to look for ('' = all)
 *
 * @return array  a list of rows, each row an associative array
 */
function dbSelectFarmers(mysqli $connection, string $searchText = ''): array
{
    $sql = "SELECT id, name, phone, village, milk_quantity, fat_percentage, created_at
            FROM " . FARMERS_TABLE;

    if ($searchText !== '') {
        /* Three question marks appear here, so the type list in
           bind_param() must be 'sss'. */
        $sql .= " WHERE name LIKE ? OR village LIKE ? OR phone LIKE ?";
    }

    /* Newest farmer first - that is what a collection centre
       wants to see. */
    $sql .= " ORDER BY id DESC";

    try {
        $statement = $connection->prepare($sql);

        if ($statement === false) {
            return [];
        }

        if ($searchText !== '') {
            /* The % signs are SQL's own wildcards: LIKE '%ram%'
               finds "Ramesh" anywhere inside the text. The % is
               part of the SEARCH, not part of the SQL syntax, so
               it is bound as a value like any other text. */
            $likeName    = '%' . $searchText . '%';
            $likeVillage = '%' . $searchText . '%';
            $likePhone   = '%' . $searchText . '%';

            $statement->bind_param('sss', $likeName, $likeVillage, $likePhone);
        }

        $statement->execute();

        /* get_result() hands back the whole result set, because
           the mysqlnd driver is compiled into PHP (check with
           php -m). Without it MySQLi forces the slower
           bind_result() + fetch() loop. */
        $result = $statement->get_result();

        /* An empty array is returned when the table is empty, so
           the page can print its own "no farmer yet" message. */
        $farmers = [];

        /* fetch_assoc() gives the next row as
           ['id' => '1', 'name' => 'Ramesh Patil', ...] and
           returns NULL when there are no rows left. */
        while ($row = $result->fetch_assoc()) {
            $farmers[] = $row;
        }

        $statement->close();

        return $farmers;

    } catch (mysqli_sql_exception $problem) {
        /* A read cannot show a red box next to a form field, so
           the reason is printed in the page's own message strip
           and an empty list is returned. */
        $GLOBALS['db_last_error'] = 'The farmer list could not be read: ' . $problem->getMessage();

        return [];
    }
}


/* ============================================================
   OPERATION 3 : READ ONE ROW  ->  SELECT ... WHERE id = ?
   dbSelectFarmerById()
   ------------------------------------------------------------
   farmer-edit.php needs the current values of ONE farmer so it
   can fill the boxes. The id arrives in the address bar
   (?id=4), so it is user input and is bound with 'i' - never
   pasted into the SQL.
   ============================================================ */

/**
 * Read one farmer by its id.
 *
 * @param mysqli $connection an open connection
 * @param int    $id         the farmer number to read
 *
 * @return array|null  the row, or null when no such farmer exists
 */
function dbSelectFarmerById(mysqli $connection, int $id): ?array
{
    /* LIMIT 1 is not strictly needed, because id is the PRIMARY
       KEY and therefore unique - it is written only to show that
       one row is expected. */
    $sql = "SELECT id, name, phone, village, milk_quantity, fat_percentage, created_at
            FROM " . FARMERS_TABLE . "
            WHERE id = ?
            LIMIT 1";

    try {
        $statement = $connection->prepare($sql);

        if ($statement === false) {
            return null;
        }

        /* 'i' = integer. This is the binding that makes
           farmer-edit.php?id=4 OR 1=1 harmless. */
        $statement->bind_param('i', $id);
        $statement->execute();

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        $statement->close();

        /* fetch_assoc() returns NULL for "no rows", so NULL is
           passed straight on to the caller. */
        return $row === null ? null : $row;

    } catch (mysqli_sql_exception $problem) {
        $GLOBALS['db_last_error'] = 'The farmer could not be read: ' . $problem->getMessage();

        return null;
    }
}


/* ============================================================
   OPERATION 4 : UPDATE  ->  UPDATE
   dbUpdateFarmer()
   ------------------------------------------------------------
   Called by farmer-edit.php. The values are the five typed
   fields; the WHERE clause uses the hidden id field of the form.

   THE HIDDEN FIELD IS IMPORTANT
   <input type="hidden" name="id" value="4"> is not visible to
   the farmer, but it travels with the form like any other field.
   Without it PHP would not know WHICH row to change.
   ============================================================ */

/**
 * Change the five values of one existing farmer.
 *
 * @param mysqli $connection an open connection
 * @param int    $id         the farmer number to change
 * @param array  $farmer     the five new values
 *
 * @return array  array('ok' => bool, 'message' => string)
 */
function dbUpdateFarmer(mysqli $connection, int $id, array $farmer): array
{
    $sql = "UPDATE " . FARMERS_TABLE . "
            SET name = ?,
                phone = ?,
                village = ?,
                milk_quantity = ?,
                fat_percentage = ?
            WHERE id = ?";

    try {
        $statement = $connection->prepare($sql);

        if ($statement === false) {
            return dbResult(false, 'The UPDATE statement could not be prepared.');
        }

        /* 'sssddi' - five values, then the id.
           The order must follow the order of the ? marks:
              name, phone, village, milk_quantity, fat_percentage, id */
        $statement->bind_param(
            'sssddi',
            $farmer['name'],
            $farmer['phone'],
            $farmer['village'],
            $farmer['milk_quantity'],
            $farmer['fat_percentage'],
            $id
        );

        $statement->execute();

        /* affected_rows tells how many rows were really changed.
           MySQL reports 0 when the row exists but every value is
           identical to the old one - which is NOT an error. */
        $changedRows = $statement->affected_rows;
        $statement->close();

        if ($changedRows === 0) {
            return dbResult(
                true,
                'Nothing was changed, because the farmer already had exactly these values.'
            );
        }

        return dbResult(true, 'Farmer number ' . $id . ' was updated in the database.');

    } catch (mysqli_sql_exception $problem) {
        /* The same duplicate-phone problem as in the INSERT. */
        if ($problem->getCode() === 1062) {
            return dbResult(
                false,
                'This mobile number already belongs to another farmer, so the record was not changed.'
            );
        }

        return dbResult(false, 'The farmer could not be updated: ' . $problem->getMessage());
    }
}


/* ============================================================
   OPERATION 5 : DELETE  ->  DELETE
   dbDeleteFarmer()
   ------------------------------------------------------------
   Called by farmer-delete.php. Only ONE value is needed - the
   id - and it is bound with 'i' exactly like in the SELECT and
   the UPDATE.
   ============================================================ */

/**
 * Delete one farmer from the table.
 *
 * @param mysqli $connection an open connection
 * @param int    $id         the farmer number to delete
 *
 * @return array  array('ok' => bool, 'message' => string)
 */
function dbDeleteFarmer(mysqli $connection, int $id): array
{
    $sql = "DELETE FROM " . FARMERS_TABLE . " WHERE id = ?";

    try {
        $statement = $connection->prepare($sql);

        if ($statement === false) {
            return dbResult(false, 'The DELETE statement could not be prepared.');
        }

        $statement->bind_param('i', $id);
        $statement->execute();

        $deletedRows = $statement->affected_rows;
        $statement->close();

        /* affected_rows of 0 means there was no such row - for
           example the record was deleted a moment ago from another
           tab, or the id in the address bar was changed by hand. */
        if ($deletedRows === 0) {
            return dbResult(false, 'No farmer was found with number ' . $id . ', so nothing was deleted.');
        }

        return dbResult(true, 'Farmer number ' . $id . ' was deleted from the database.');

    } catch (mysqli_sql_exception $problem) {
        return dbResult(false, 'The farmer could not be deleted: ' . $problem->getMessage());
    }
}


/* ============================================================
   OPERATION 6 : THE SUMMARY NUMBERS
   dbFarmerSummary()
   ------------------------------------------------------------
   The four little cards at the top of the list page. A total,
   an average fat and a total daily milk value - the same idea as
   the summary strip of Assignment 12, but now the numbers come
   out of MySQL instead of a PHP array.

   WHY query() AND NOT A PREPARED STATEMENT HERE?
   Honest answer, and a good viva point: a prepared statement
   exists to protect text that came from a user. This SQL has no
   "?" and no typed text inside it - it is a fixed sentence with
   numbers standing in for other numbers. Using prepare() here
   would be cargo-culting, not safety.

   (The four operations above, which DO take user input, all use
   prepared statements. That is the rule, not "always prepare".)
   ============================================================ */

/**
 * Work out the summary numbers of the farmers table.
 *
 * @param mysqli $connection an open connection
 *
 * @return array  farmer_count, total_milk, average_fat, total_amount
 */
function dbFarmerSummary(mysqli $connection): array
{
    $summary = [
        'farmer_count' => 0,
        'total_milk'   => 0.0,
        'average_fat'  => 0.0,
        'total_amount' => 0.0,
    ];

    /* AVG() and SUM() are computed BY MySQL, so PHP receives one
       single row instead of every farmer. */
    $sql = "SELECT COUNT(*)                    AS farmer_count,
                   SUM(milk_quantity)            AS total_milk,
                   AVG(fat_percentage)           AS average_fat
            FROM " . FARMERS_TABLE;

    try {
        $result = $connection->query($sql);

        if ($result === false) {
            return $summary;
        }

        $row = $result->fetch_assoc();
        $result->free();

        /* An empty table gives NULL from SUM() and AVG(), not 0,
           so (float) is used to turn NULL into a number the page
           can print. */
        $farmerCount = (int) ($row['farmer_count'] ?? 0);
        $totalMilk   = (float) ($row['total_milk'] ?? 0);
        $averageFat  = (float) ($row['average_fat'] ?? 0);

        /* The daily money is worked out in PHP, one farmer at a
           time, because the rate depends on EACH farmer's own fat
           and not on an average. */
        $totalAmount = 0.0;

        foreach (dbSelectFarmers($connection) as $farmerRow) {
            $totalAmount += dailyAmount(
                (float) $farmerRow['milk_quantity'],
                (float) $farmerRow['fat_percentage']
            );
        }

        $summary['farmer_count'] = $farmerCount;
        $summary['total_milk']   = $totalMilk;
        $summary['average_fat']  = $averageFat;
        $summary['total_amount'] = $totalAmount;

        return $summary;

    } catch (mysqli_sql_exception $problem) {
        $GLOBALS['db_last_error'] = 'The summary could not be read: ' . $problem->getMessage();

        return $summary;
    }
}
