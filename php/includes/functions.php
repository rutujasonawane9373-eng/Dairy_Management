<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 12
   PHP STRING FUNCTIONS AND SERVER-SIDE VALIDATION
   File: php/includes/functions.php

   All four PHP pages of this assignment use this one file, in the
   same way that the HTML pages of Assignments 1-7 share ONE
   stylesheet and ONE JavaScript file.

   The file is written in four parts:

     PART 1  Reading the form data out of $_POST
     PART 2  The string functions of the project
     PART 3  Server-side validation (one small rule per field)
     PART 4  Session helpers + htmlspecialchars() for safe printing

   There is NO database in this assignment. A registration is kept
   in the PHP session only and disappears when the browser window is
   closed. Saving records permanently is Assignment 13 (PHP + MySQL).
   ============================================================ */


/* ============================================================
   PART 1 : READING THE FORM DATA
   ------------------------------------------------------------
   When the farmer presses "Register Farmer", the browser sends
   every field of the form to register.php using the POST method.
   PHP puts those values into the built-in array $_POST:

       $_POST['farmerName']   <-- the value of
                                  <input name="farmerName">

   The NAME written in the HTML decides the ARRAY KEY used here.
   That is the only link between the form and the PHP code, which
   is why every input needs a meaningful name attribute.
   ============================================================ */

/**
 * Read one field of the form and clean it.
 *
 * @param string $fieldName the name attribute of the <input>
 * @return string           the value, with extra spaces removed
 */
function postText(string $fieldName): string
{
    /* "?? " means "if this key does not exist, use this instead".
       Without it PHP would print a warning if a field was missing
       from the form, so the page would break. */
    $typedValue = $_POST[$fieldName] ?? '';

    /* trim() removes spaces, tabs and line breaks from BOTH ends of a
       string: "   Ramesh Patil  " becomes "Ramesh Patil".
       This is the most used string function in a real form, because
       people type a space before and after their name or village. */
    return trim($typedValue);
}

/**
 * Convert typed text into a number.
 * (float) is PHP's cast: it turns "18.5" into 18.5 and "abc" into 0.
 */
function toNumber(string $typedValue): float
{
    return (float) $typedValue;
}


/* ============================================================
   PART 2 : THE STRING FUNCTIONS OF THE PROJECT
   ------------------------------------------------------------
   Every function below receives a string and returns a string.
   The rule of the dairy (fat 3.5% and above = 42 Rs/litre) is the
   same rule that Assignment 7 and Assignment 11 already use, so the
   project keeps ONE rate board.
   ============================================================ */

/**
 * Make a farmer's name look neat.
 * "  ramesh   PATIL " -> "Ramesh Patil"
 */
function cleanName(string $name): string
{
    /* strtolower() makes every letter small, then ucwords() makes the
       FIRST letter of every word capital. ucwords() alone is not
       enough, because ucwords('PATIL') returns 'PATIL' unchanged. */
    return ucwords(strtolower($name));
}

/**
 * Make a village name look neat, in the same way.
 */
function cleanVillage(string $village): string
{
    return ucwords(strtolower($village));
}

/**
 * Cut a long text down to $limit characters.
 */
function shortenText(string $text, int $limit): string
{
    /* strlen() returns HOW MANY characters a string has.
       strlen('Ramesh') = 6 */
    if (strlen($text) <= $limit) {
        return $text;
    }

    /* substr($text, 0, $limit) cuts the first $limit characters out of
       the string (the 0 is "start at the first character").
       The three dots are added with the concatenation operator "." */
    return substr($text, 0, $limit) . '...';
}

/**
 * Build the farmer code that the society writes on every receipt,
 * for example "DF-RAM-210".
 *
 * This one function uses three string functions and the
 * concatenation operator, so it is the string-manipulation answer
 * of the viva in a single place.
 */
function makeFarmerCode(string $name, string $mobile): string
{
    /* strtoupper() + substr() = the first three letters of the name,
       in capital letters: "Ramesh Patil" -> "RAM" */
    $letters = strtoupper(substr($name, 0, 3));

    /* str_pad($text, $length, $padString, STR_PAD_LEFT) adds characters
       on the LEFT until the string is $length characters long, so every
       farmer code has the same width: "5" -> "005" */
    $number = str_pad(substr($mobile, -3), 3, '0', STR_PAD_LEFT);

    /* "." is the CONCATENATION operator - it glues strings together.
       "DF-" . "RAM" . "-" . "005" becomes "DF-RAM-005" */
    return 'DF-' . $letters . '-' . $number;
}

/**
 * Remove the spaces and dashes a farmer may type in a mobile number,
 * so "98765 43210" and "98765-43210" both become "9876543210".
 */
function cleanMobileNumber(string $mobile): string
{
    /* str_replace() given an ARRAY replaces every listed character in
       one call. */
    return str_replace([' ', '-', '+'], '', $mobile);
}

/**
 * Show a mobile number as "98765 43210" - easier to read for the
 * staff member who types it into the payment register.
 */
function formatMobileForDisplay(string $mobile): string
{
    /* substr($mobile, -5) counts 5 characters BACKWARDS from the end of
       the string, so it returns the last five digits. */
    return substr($mobile, 0, 5) . ' ' . substr($mobile, -5);
}

/**
 * Hide most of a mobile number: "98765 43210" -> "***** 43210".
 * Personal numbers should not be printed in full on a shared screen.
 */
function maskMobileNumber(string $mobile): string
{
    /* str_repeat('*', 5) writes the same character five times. */
    return str_repeat('*', 5) . ' ' . substr($mobile, -5);
}

/**
 * The rate board rule: fat 3.5% and above is paid 42 Rs/litre,
 * below 3.5% is paid 40 Rs/litre. The same rule is used by the
 * Assignment 7 JavaScript, the Assignment 11 log and the rate board
 * printed on the collection centre page.
 */
function rateForFat(float $fat): float
{
    if ($fat >= 3.5) {
        return 42.0;
    }

    return 40.0;
}

/**
 * Write a rate as money, for example "Rs 42.00 / litre".
 */
function rateText(float $rate): string
{
    /* number_format($number, 2) rounds the number and always keeps two
       decimal places: 42 -> "42.00" */
    return 'Rs ' . number_format($rate, 2) . ' / litre';
}

/**
 * The greeting printed after a successful registration.
 * Three short sentences glued together with the "." operator.
 */
function greetingLine(string $name): string
{
    return 'Welcome, ' . $name . '! Your registration has been accepted.';
}

/**
 * Build one sentence that describes the farmer, using the
 * concatenation operator and sprintf().
 */
function makeSummaryLine(array $profile): string
{
    /* sprintf() builds a string from a template.
       %s  = a text value      %.1f = a number with 1 decimal place
       %.1f%% prints the number and then a real percent sign, because
       % itself must be written as %% inside the template. */
    $firstSentence = sprintf(
        '%s of %s brings %.1f litres a day at %.1f%% fat.',
        $profile['name'],
        $profile['village'],
        $profile['milk'],
        $profile['fat']
    );

    /* The second sentence is added with the "." operator. */
    return $firstSentence
        . ' Paid ' . rateText($profile['rate'])
        . ', about Rs ' . number_format($profile['amount'], 2) . ' a day.';
}


/* ============================================================
   PART 3 : SERVER-SIDE VALIDATION
   ------------------------------------------------------------
   Every function below receives the typed text and returns

       ''              the value is accepted
       'a sentence'    the reason the value was rejected

   These are the SAME six rules as the JavaScript version of
   Assignment 7, written again in PHP. Both are needed:
     - JavaScript checks the value while the farmer types, so he
       gets an instant answer,
     - PHP checks it again on the server, because anybody can send
       a request without using our form (or without JavaScript).
   ============================================================ */

/**
 * Farmer name: required, 3 to 40 characters, letters and spaces only.
 */
function checkFarmerName(string $name): string
{
    if ($name === '') {
        return 'Farmer name is required.';
    }

    $length = strlen($name);

    if ($length < 3) {
        return 'Farmer name must be at least 3 characters long (you typed ' . $length . ').';
    }

    if ($length > 40) {
        return 'Farmer name must not be longer than 40 characters (you typed ' . $length . ').';
    }

    /* preg_match() returns 1 when the pattern is found and 0 when it is
       not. The pattern '/^[A-Za-z .\'-]+$/' means: from the first (^)
       character to the last ($) character, allow only letters, spaces,
       dots, apostrophes and hyphens. */
    if (preg_match('/^[A-Za-z .\'-]+$/', $name) !== 1) {
        return 'Farmer name may contain only letters, spaces, dot, apostrophe and hyphen.';
    }

    return '';
}

/**
 * Mobile number: required, exactly 10 digits, starting with 6, 7, 8 or 9.
 */
function checkMobileNumber(string $mobile): string
{
    if ($mobile === '') {
        return 'Mobile number is required.';
    }

    /* [6-9]  the first digit is 6, 7, 8 or 9
       [0-9]{9}  followed by nine more digits */
    if (preg_match('/^[6-9][0-9]{9}$/', $mobile) !== 1) {
        return 'Mobile number must be exactly 10 digits and must start with 6, 7, 8 or 9.';
    }

    return '';
}

/**
 * Email: OPTIONAL. An empty box is accepted; a filled box must look
 * like an email address.
 */
function checkEmail(string $email): string
{
    if ($email === '') {
        return '';
    }

    /* filter_var() with FILTER_VALIDATE_EMAIL returns the address when
       it is valid and false when it is not. */
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return 'Email address does not look correct (example: name@example.com).';
    }

    return '';
}

/**
 * Village / address: required, 3 to 60 characters.
 */
function checkVillage(string $village): string
{
    if ($village === '') {
        return 'Village / address is required.';
    }

    if (strlen($village) < 3) {
        return 'Village / address must be at least 3 characters long.';
    }

    if (strlen($village) > 60) {
        return 'Village / address must not be longer than 60 characters.';
    }

    return '';
}

/**
 * Average milk per day: required, a number between 0.5 and 100 litres.
 */
function checkMilkQuantity(string $milk): string
{
    if ($milk === '') {
        return 'Average milk per day is required.';
    }

    /* is_numeric() is true for "18.5" and false for "18 litres". */
    if (!is_numeric($milk)) {
        return 'Average milk per day must be a number, for example 18.5.';
    }

    $value = (float) $milk;

    if ($value < 0.5) {
        return 'Average milk per day must be at least 0.5 litres.';
    }

    if ($value > 100) {
        return 'Average milk per day must not be above 100 litres.';
    }

    return '';
}

/**
 * Fat percentage: required, a number between 3 and 8, because that is
 * the range the society pays for.
 */
function checkFatPercentage(string $fat): string
{
    if ($fat === '') {
        return 'Fat percentage is required.';
    }

    if (!is_numeric($fat)) {
        return 'Fat percentage must be a number, for example 4.6.';
    }

    $value = (float) $fat;

    /* || means "OR" - the value is rejected when it is too small OR too big */
    if ($value < 3 || $value > 8) {
        return 'Fat percentage must be between 3% and 8%.';
    }

    return '';
}

/**
 * Run all six rules and return ONLY the fields that failed.
 *
 * @return array field name => the reason it was rejected
 *                      (an empty array means no error at all)
 */
function runValidation(array $values): array
{
    /* This array calls every rule. Each call returns '' or a sentence. */
    $allChecks = [
        'farmerName'    => checkFarmerName($values['farmerName']),
        'mobile'        => checkMobileNumber($values['mobile']),
        'email'         => checkEmail($values['email']),
        'village'       => checkVillage($values['village']),
        'milkQuantity'  => checkMilkQuantity($values['milkQuantity']),
        'fatPercentage' => checkFatPercentage($values['fatPercentage']),
    ];

    /* foreach() walks through the array one pair at a time.
       The empty messages are thrown away, so $errors holds only the
       real problems - that is how the page knows how many fields to
       mark in red. */
    $errors = [];

    foreach ($allChecks as $fieldName => $message) {
        if ($message !== '') {
            $errors[$fieldName] = $message;
        }
    }

    return $errors;
}

/**
 * The label of a field, used by the list of rejected values.
 */
function fieldLabel(string $fieldName): string
{
    $labels = [
        'farmerName'    => 'Farmer Name',
        'mobile'        => 'Mobile Number',
        'email'         => 'Email',
        'village'       => 'Village / Address',
        'milkQuantity'  => 'Average Milk per Day',
        'fatPercentage' => 'Fat Percentage',
    ];

    return $labels[$fieldName] ?? $fieldName;
}

/**
 * Turn the six typed values into ONE farmer record.
 * This is where the string functions are really used.
 */
function buildProfile(array $values): array
{
    $name    = cleanName($values['farmerName']);
    $mobile  = cleanMobileNumber($values['mobile']);
    $village = cleanVillage($values['village']);
    $milk    = toNumber($values['milkQuantity']);
    $fat     = toNumber($values['fatPercentage']);
    $rate    = rateForFat($fat);

    /* Every "=>" line becomes one key => value pair of the array.
       The key is a fixed name, the value is calculated by a function. */
    $profile = [
        'code'           => makeFarmerCode($name, $mobile),
        'name'           => $name,
        'name_length'    => strlen($name),
        'mobile'         => $mobile,
        'mobile_display' => formatMobileForDisplay($mobile),
        'mobile_masked'  => maskMobileNumber($mobile),
        'email'          => strtolower($values['email']),
        'village'        => $village,
        'village_short'  => shortenText($village, 18),
        'milk'           => $milk,
        'fat'            => $fat,
        'rate'           => $rate,
        'amount'         => $milk * $rate,
        'registered_at'  => date('d M Y, h:i A'),
    ];

    /* The summary sentence needs the array above, so it is added now. */
    $profile['summary'] = makeSummaryLine($profile);

    return $profile;
}


/* ============================================================
   PART 4 : SESSION HELPERS AND SAFE PRINTING
   ------------------------------------------------------------
   A session is a small file on the SERVER that belongs to one
   visitor. $_SESSION is that file's content, and it survives every
   change of page - it is lost only when the browser is closed, or
   when logout.php destroys it. That is why the profile page can
   show the farmer that was registered on another page.
   ============================================================ */

/**
 * Has a farmer been registered in this session?
 */
function farmerIsRegistered(): bool
{
    return isset($_SESSION['farmer_profile']);
}

/**
 * How many farmers have been registered in this session?
 * This is the "simple session value" of the assignment - a counter.
 */
function registeredCount(): int
{
    if (isset($_SESSION['registration_count'])) {
        // (int) makes sure a number is returned, never a string.
        return (int) $_SESSION['registration_count'];
    }

    return 0;
}

/**
 * Every farmer registered during this visit.
 */
function registeredFarmers(): array
{
    if (isset($_SESSION['registered_farmers'])) {
        return $_SESSION['registered_farmers'];
    }

    return [];
}

/**
 * Write the farmer into the session. Three values are stored:
 *   farmer_profile     the farmer of the profile page
 *   registered_farmers every farmer of this visit
 *   registration_count the counter
 */
function saveFarmerInSession(array $profile): void
{
    $_SESSION['farmer_profile'] = $profile;

    /* [] at the end of an array means "add one item at the end",
       the same as JavaScript's push(). */
    $_SESSION['registered_farmers'][] = $profile;

    $_SESSION['registration_count'] = registeredCount() + 1;
}

/**
 * Read a one-time message and remove it.
 * The message is shown exactly once, so pressing F5 does not show
 * the same success message again.
 */
function takeFlashMessage(): string
{
    $message = '';

    if (isset($_SESSION['flash_success'])) {
        $message = $_SESSION['flash_success'];
    }

    /* unset() removes one key from the session array. */
    unset($_SESSION['flash_success']);

    return $message;
}

/**
 * Print a typed value safely.
 *
 * htmlspecialchars() replaces the characters < > & " ' with HTML
 * entities, so text typed into the form can never become HTML code
 * (that attack is called XSS). Every value that comes from $_POST
 * MUST be printed through this function.
 */
function safeText(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * A short helper for the value="..." part of an input box:
 * prints the old value after an error, or an empty string.
 */
function oldValue(array $old, string $fieldName): string
{
    return safeText($old[$fieldName] ?? '');
}