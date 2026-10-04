-- =============================================================
-- DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
-- PHP + MySQL : DATABASE CONNECTION AND CRUD
-- File: database/schema.sql
--
-- WHAT THIS FILE DOES
--   1. Creates the database  dairy_management
--   2. Creates ONE table inside it:  farmers
--   3. Puts 6 sample farmers in it, so the list page is not empty
--
-- HOW TO RUN IT
--   Open a terminal (Command Prompt or PowerShell) and type:
--
--       mysql -u root -p < database\schema.sql
--
--   You will be asked for the MySQL root password, then type it.
--   (Do NOT write the password inside this file.)
--
--   If the "mysql" command is not found, use the full path:
--
--       "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" -u root -p < database\schema.sql
--
--   A phpMyAdmin alternative: open phpMyAdmin in the browser,
--   click the "Import" tab, choose this file and press "Go".
--
-- SAFE TO RUN MORE THAN ONCE - every statement uses IF NOT EXISTS
-- or "DROP TABLE IF EXISTS", so running it again simply rebuilds
-- the table with the 6 sample rows.
-- =============================================================


-- =============================================================
-- PART 1 : CREATE THE DATABASE
-- -------------------------------------------------------------
-- CREATE DATABASE IF NOT EXISTS  make the  database
--   IF NOT EXISTS              do not fail if it is already there
--   CHARACTER SET utf8mb4      store every character of every
--                              language, including Marathi names
--   COLLATE ..._unicode_ci     compare text in a case-insensitive
--                              way, so "patil" finds "Patil"
-- =============================================================

CREATE DATABASE IF NOT EXISTS dairy_management
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- USE tells MySQL which of the existing databases the next
-- statements belong to, so every table name below can be short.
USE dairy_management;


-- =============================================================
-- PART 2 : CREATE THE "farmers" TABLE
-- -------------------------------------------------------------
-- One row of this table = one registered milk producer.
--
-- EVERY FIELD AND WHY IT WAS CHOSEN
--
--   id               INT AUTO_INCREMENT PRIMARY KEY
--                    MySQL numbers the farmers itself (1, 2, 3...)
--                    and never repeats a number, even after a
--                    farmer is deleted. AUTO_INCREMENT is the
--                    usual "surrogate key" of a table.
--
--   name             VARCHAR(60) NOT NULL
--                    Text, up to 60 characters. NOT NULL means the
--                    field can never be left empty.
--                    (VARCHAR is used for names, never INT -
--                    a name is not a number.)
--
--   phone            VARCHAR(15) NOT NULL UNIQUE
--                    The mobile number is kept as TEXT on purpose.
--                    If it were an INT, the leading zero of a
--                    number like 0987654321 would disappear and
--                    +91 could never be stored. UNIQUE means the
--                    same farmer cannot be registered twice.
--
--   village          VARCHAR(60) NOT NULL
--                    The village / address of the farmer.
--
--   milk_quantity    DECIMAL(6,2) NOT NULL
--                    Litres of milk on an average day.
--                    DECIMAL(6,2) allows up to 9999.99, so 18.50
--                    fits and 18.5 is stored exactly.
--                    DECIMAL is used for money and measurements -
--                    FLOAT would give answers like 18.49999999.
--
--   fat_percentage   DECIMAL(3,1) NOT NULL
--                    Fat 3% to 9.9%. The society pays between 3%
--                    and 8%, which the CHECK rule below enforces.
--
--   created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
--                    MySQL writes the date and time by itself when
--                    the row is inserted. PHP never has to send it.
--
--   Two extra safety rules at the end:
--     - the phone number must be different from every other one
--     - the milk and fat numbers must stay inside the real range
--       These are MySQL's CHECK constraints. They are a LAST line of
--       defence: the PHP validation in db-validate.php rejects a bad
--       value first, so the user gets a friendly message instead of
--       a database error.
-- =============================================================

DROP TABLE IF EXISTS farmers;

CREATE TABLE farmers (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(60)  NOT NULL,
    phone          VARCHAR(15)  NOT NULL,
    village        VARCHAR(60)  NOT NULL,
    milk_quantity  DECIMAL(6,2) NOT NULL,
    fat_percentage DECIMAL(3,1) NOT NULL,
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_farmers_phone (phone),

    CONSTRAINT chk_farmers_milk CHECK (milk_quantity > 0 AND milk_quantity <= 100),
    CONSTRAINT chk_farmers_fat  CHECK (fat_percentage >= 3 AND fat_percentage <= 8)
) ENGINE = InnoDB;

-- A COMMENT is stored with the column itself and is shown by
-- phpMyAdmin and by DESCRIBE farmers;. It costs nothing.
ALTER TABLE farmers
    MODIFY COLUMN name           VARCHAR(60)  NOT NULL COMMENT 'Full name of the milk producer',
    MODIFY COLUMN phone          VARCHAR(15)  NOT NULL COMMENT '10 digit mobile number, no country code',
    MODIFY COLUMN village        VARCHAR(60)  NOT NULL COMMENT 'Village or address of the farmer',
    MODIFY COLUMN milk_quantity  DECIMAL(6,2) NOT NULL COMMENT 'Average litres of milk per day',
    MODIFY COLUMN fat_percentage DECIMAL(3,1) NOT NULL COMMENT 'Fat percentage of the milk, 3 to 8';


-- =============================================================
-- PART 3 : SIX SAMPLE FARMERS
-- -------------------------------------------------------------
-- These rows are only here so that farmer-list.php has something
-- to show the moment the project is opened. They are ordinary
-- INSERT statements - the CRUD pages use PREPARED statements,
-- because there the values come from a form typed by a farmer.
--
-- The five milk and fat numbers follow the rate board used
-- everywhere else in this project:
--     fat >= 3.5  ->  42 Rs / litre
--     fat <  3.5  ->  40 Rs / litre
-- =============================================================

INSERT INTO farmers (name, phone, village, milk_quantity, fat_percentage) VALUES
    ('Ramesh Patil',   '9876543210', 'Wadgaon',           18.50, 4.6),
    ('Sunita Deshmukh', '9876543211', 'Shirpur',           12.00, 4.2),
    ('Iqbal Khan',      '9876543212', 'Nandgaon',           8.50, 3.4),
    ('Lata Deshpande',  '9876543213', 'Wadgaon',           22.00, 5.0),
    ('Ganesh Jadhav',   '9876543214', 'Chalisgaon',         6.00, 3.8),
    ('Meena Bhosale',   '9876543215', 'Shirpur',           15.25, 4.8);


-- =============================================================
-- PART 4 : HOW TO CHECK THAT IT WORKED
-- -------------------------------------------------------------
--   mysql -u root -p
--   USE dairy_management;
--   SHOW TABLES;          -> should print exactly one line: farmers
--   DESCRIBE farmers;     -> prints the 7 columns and their types
--   SELECT * FROM farmers;
--
-- `DESCRIBE farmers;` and `SHOW CREATE TABLE farmers;` are the two
-- commands to remember for the viva.
-- =============================================================
