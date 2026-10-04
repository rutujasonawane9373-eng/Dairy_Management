<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   SERVER-SIDE VALIDATION
   File: php/db-crud/db-validate.php

   WHY VALIDATE IN PHP AT ALL?
   The HTML form already has the same rules written on it
   (required, pattern="[6-9][0-9]{9}", min, max). But an HTML
   rule is only a SUGGESTION to the browser:

     - anybody can turn JavaScript off,
     - anybody can type the address of farmer-create.php into the
       browser and send the form without ever opening ours,
     - a POST can be written by a script, not by a person.

   So PHP repeats every rule. The browser check is for the
   farmer's convenience; the PHP check is the one that decides.

   THE RULE OF THIS FILE
   Each function receives one typed value and returns

        ''            -> the value is accepted
        'a sentence'  -> the value is rejected, and this is why

   It never touches the database and never prints anything, which
   makes every rule easy to test on its own.
   ============================================================ */


/* ============================================================
   PART 1 : NAME
   ============================================================ */

/**
 * Name: not empty, 3 to 60 characters, letters and spaces only.
 */
function checkName(string $name): string
{
    if ($name === '') {
        return 'Farmer name is required.';
    }

    if (strlen($name) < 3) {
        return 'Farmer name must be at least 3 characters long.';
    }

    if ($name > 60) {
        /* In PHP a plain ">" between two strings compares them
           alphabetically, so this also catches a 200-character
           value that strlen() would happily accept.
           strlen() is used as well because it is the honest way
           to express "60 characters". */
        return 'Farmer name must not be longer than 60 characters.';
    }

    if (preg_match('/^[A-Za-z .\'-]+$/', $name) !== 1) {
        return 'Farmer name may contain only letters, spaces, dot, apostrophe and hyphen.';
    }

    return '';
}


/* ============================================================
   PART 2 : PHONE
   ------------------------------------------------------------
   The phone is the one field with a UNIQUE key in the table, so
   it is checked twice: here, for its SHAPE, and by MySQL, for its
   UNIQUENESS. The two checks catch different problems - the
   first gives a friendly message, the second is the guarantee.
   ============================================================ */

/**
 * Phone: not empty, exactly 10 digits, starting with 6, 7, 8 or 9.
 */
function checkPhone(string $phone): string
{
    if ($phone === '') {
        return 'Mobile number is required.';
    }

    /* Spaces and dashes typed by mistake are removed first, so
       "98765 43210" is accepted and stored as "9876543210". */
    $cleanedPhone = str_replace([' ', '-', '+'], '', $phone);

    if (preg_match('/^[6-9][0-9]{9}$/', $cleanedPhone) !== 1) {
        return 'Mobile number must be exactly 10 digits and must start with 6, 7, 8 or 9.';
    }

    return '';
}


/* ============================================================
   PART 3 : VILLAGE
   ============================================================ */

/**
 * Village / address: not empty, 3 to 60 characters.
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


/* ============================================================
   PART 4 : MILK QUANTITY
   ------------------------------------------------------------
   is_numeric() is the correct test here, not a bare (float)
   cast. (float) "abc" quietly becomes 0, so the value would be
   rejected for the WRONG reason ("below 0.5") and the farmer
   would never learn that "abc" is not a number at all.
   is_numeric() answers the real question first: is this a
   number written with digits?
   ============================================================ */

/**
 * Average milk per day: a number between 0.5 and 100 litres.
 */
function checkMilkQuantity(string $milk): string
{
    if ($milk === '') {
        return 'Average milk per day is required.';
    }

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


/* ============================================================
   PART 5 : FAT PERCENTAGE
   ============================================================ */

/**
 * Fat: a number between 3 and 8, because that is the range the
 * society pays for. The same range is written as a CHECK
 * constraint in database/schema.sql, so even a direct SQL insert
 * from another program cannot break it.
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

    if ($value < 3 || $value > 8) {
        return 'Fat percentage must be between 3% and 8%.';
    }

    return '';
}


/* ============================================================
   PART 6 : RUN ALL FIVE RULES
   ------------------------------------------------------------
   runFarmerValidation() is the single entry point used by
   farmer-create.php and farmer-edit.php. It returns ONLY the
   fields that failed, so the page can mark exactly those boxes in
   red and print the number of mistakes.

   An empty array means "everything was accepted".
   ============================================================ */

/**
 * Run every rule and return only the rejected fields.
 *
 * @param array $values the five trimmed values from the form
 * @return array        field name => the reason it was rejected
 */
function runFarmerValidation(array $values): array
{
    /* This array calls all five rules. Each call gives back '' or
       a sentence. */
    $allChecks = [
        'name'          => checkName($values['name']),
        'phone'         => checkPhone($values['phone']),
        'village'       => checkVillage($values['village']),
        'milk_quantity' => checkMilkQuantity($values['milk_quantity']),
        'fat_percentage' => checkFatPercentage($values['fat_percentage']),
    ];

    /* foreach() walks through the array one pair at a time. Only
       the non-empty sentences are kept. */
    $errors = [];

    foreach ($allChecks as $fieldName => $message) {
        if ($message !== '') {
            $errors[$fieldName] = $message;
        }
    }

    return $errors;
}


/**
 * The label printed above a rejected field.
 */
function fieldLabel(string $fieldName): string
{
    $labels = [
        'name'           => 'Farmer Name',
        'phone'          => 'Mobile Number',
        'village'        => 'Village / Address',
        'milk_quantity'  => 'Average Milk per Day',
        'fat_percentage' => 'Fat Percentage',
    ];

    return $labels[$fieldName] ?? $fieldName;
}


/* ============================================================
   PART 7 : BUILD THE ROW THAT WILL BE SENT TO MySQL
   ------------------------------------------------------------
   The typed strings are turned into the exact types the columns
   expect:

       name, phone, village  -> VARCHAR, so they stay strings
       milk_quantity,
       fat_percentage        -> DECIMAL, so they become floats

   The (float) cast matters. Without it MySQL would receive the
   STRING "18.5" for a DECIMAL column; MySQL is kind enough to
   convert it, but doing the conversion in PHP keeps the value and
   the table in agreement, and stops "18abc" from ever reaching
   the server.
   ============================================================ */

/**
 * Turn the five typed values into the array the CRUD functions
 * expect.
 */
function buildFarmerValues(array $typed): array
{
    return [
        'name'           => $typed['name'],
        'phone'          => str_replace([' ', '-', '+'], '', $typed['phone']),
        'village'        => $typed['village'],
        'milk_quantity'  => (float) $typed['milk_quantity'],
        'fat_percentage' => (float) $typed['fat_percentage'],
    ];
}
