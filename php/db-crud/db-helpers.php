<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   SMALL HELPERS USED BY EVERY PAGE
   File: php/db-crud/db-helpers.php

   These functions do not talk to the database. They only help the
   pages print things nicely and remember a message for one page
   load. They are separated from db-connect.php (connection) and
   db-farmers.php (SQL) so that each file has ONE job - which is
   exactly what a viva examiner likes to hear.
   ============================================================ */


/* ============================================================
   PART 1 : PRINT A VALUE SAFELY
   ------------------------------------------------------------
   This part is inherited from Assignment 12 and is important
   enough to repeat: TWO different dangers exist in a PHP project
   and they must not be confused.

   DANGER 1 - SQL INJECTION  ->  stopped by PREPARED STATEMENTS
                                (db-farmers.php)
   DANGER 2 - XSS, i.e. a typed value turning into HTML code
                                ->  stopped by htmlspecialchars()
                                    (this file, safeText())

   A prepared statement protects the DATABASE.
   htmlspecialchars() protects the PAGE.
   A complete project needs both, and this assignment shows both.
   ============================================================ */

/**
 * Print any text safely on the page.
 *
 * htmlspecialchars() turns
 *      <   into  &lt;
 *      >   into  &gt;
 *      &   into  &amp;
 *      "   into  &quot;
 *      '   into  &#039;
 *
 * so the browser shows the characters instead of treating them as
 * HTML. ENT_QUOTES means "also escape the quote marks", and
 * 'UTF-8' matches DB_CHARSET in db-config.php.
 *
 * Every value that came out of $_POST, $_GET or the database MUST
 * be printed through this function.
 */
function safeText(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}


/**
 * The same idea for a number. Used for id, milk and fat, which
 * always come out of the database as strings.
 */
function safeNumber(float $number, int $decimals = 2): string
{
    return safeText(number_format($number, $decimals));
}


/* ============================================================
   PART 2 : THE MESSAGE SHOWN ONCE AFTER A WRITE
   ------------------------------------------------------------
   After a successful INSERT, UPDATE or DELETE the page must not
   simply print "done" and stay there, because then pressing F5
   would send the same form again and the farmer would be saved
   TWICE. The usual answer is the Post/Redirect/Get pattern:

       farmer-create.php   POST happened, record saved
              |
              |  header('Location: farmer-list.php');  exit;
              v
       farmer-list.php    GET only - safe to refresh any number
                          of times

   But the success sentence has to survive that jump, and a
   redirect throws away everything printed so far. So the
   sentence is parked in the SESSION for the length of one
   redirect. That is what dbFlashSet() / dbFlashTake() do -
   "flash" means "seen once and then gone".
   ============================================================ */

/**
 * Park a message in the session so the next page can show it once.
 *
 * @param string $type    'ok' (green) or 'error' (red)
 * @param string $message the sentence to show
 */
function dbFlashSet(string $type, string $message): void
{
    $_SESSION['db_flash_type'] = $type;
    $_SESSION['db_flash_message'] = $message;
}

/**
 * Read the parked message and delete it in the same call.
 *
 * Removing it here is why the message does NOT reappear when the
 * page is refreshed - which is the correct behaviour of a
 * Post/Redirect/Get page.
 */
function dbFlashTake(): array
{
    $type = $_SESSION['db_flash_type'] ?? '';
    $message = $_SESSION['db_flash_message'] ?? '';

    /* unset() removes one key from the $_SESSION array. */
    unset($_SESSION['db_flash_type']);
    unset($_SESSION['db_flash_message']);

    return ['type' => $type, 'message' => $message];
}


/* ============================================================
   PART 3 : THE RATE BOARD, SHARED WITH EVERY OTHER ASSIGNMENT
   ------------------------------------------------------------
   The same rule appears in Assignment 6 (main.js), Assignment 7
   (main.js), Assignment 11 (main.js) and Assignment 12
   (functions.php):

        fat 3.5% and above  ->  42 Rs per litre
        fat below 3.5%      ->  40 Rs per litre

   It is repeated here in PHP on purpose: each assignment is a
   separate, self-contained piece of work, so each one must be
   readable on its own.
   ============================================================ */

/**
 * The rate paid for one litre of milk with the given fat %.
 */
function rateForFat(float $fat): float
{
    if ($fat >= 3.5) {
        return 42.0;
    }

    return 40.0;
}

/**
 * The daily milk value of one farmer: litres x rate.
 */
function dailyAmount(float $milk, float $fat): float
{
    return $milk * rateForFat($fat);
}


/* ============================================================
   PART 4 : READING ONE FIELD OF THE FORM
   ------------------------------------------------------------
   Assignment 12 taught this; it is repeated because the CRUD
   form needs the same cleaning.

   $_POST['name'] holds what was typed into <input name="name">.
   The "?? ''" means "if the key is missing, use an empty string",
   which stops PHP from printing a warning when a field is absent.
   trim() removes the spaces a person leaves in front of a name or
   after a mobile number.
   ============================================================ */

/**
 * Read one submitted field and trim the spaces off both ends.
 */
function postField(string $fieldName): string
{
    return trim($_POST[$fieldName] ?? '');
}

/**
 * The value="..." part of a text box: print what was typed if the
 * form came back with an error, otherwise print nothing.
 */
function oldValue(array $old, string $fieldName): string
{
    return safeText($old[$fieldName] ?? '');
}
