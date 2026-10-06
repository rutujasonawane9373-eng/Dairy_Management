// ================================================================
//  DAIRY MANAGEMENT SYSTEM
//  ASSIGNMENT 15 : REST API  -  /api/farmers
//  File: node-backend/routes/farmers.js
//
//  A Router is a small Express application that only knows the
//  routes written inside it. server.js attaches it with
//      app.use('/api/farmers', farmersRouter);
//  so every address below automatically starts with /api/farmers.
//
//  The five REST endpoints:
//
//      GET    /api/farmers        -> list every farmer
//      GET    /api/farmers/:id    -> read one farmer
//      POST   /api/farmers        -> create a farmer
//      PUT    /api/farmers/:id    -> update a farmer
//      DELETE /api/farmers/:id    -> remove a farmer
//
//  REST conventions used here (what a teacher usually asks):
//    - the URL names a THING (farmers), the METHOD says the ACTION
//    - GET is safe and never changes data -> can be repeated
//    - 200 OK, 201 Created, 400 Bad Request, 404 Not Found,
//      409 Conflict, 500 Internal Server Error
//    - request and response bodies are JSON
//
//  Every SQL statement below uses ? placeholders (prepared
//  statements) - never string concatenation - exactly like the
//  MySQLi prepared statements in php/db-crud/db-farmers.php.
// ================================================================

const express = require('express');
const router = express.Router();

const { query } = require('../db');
const dbConfig = require('../db-config');

// The table name is a fixed constant from db-config.js, never user
// input. (A placeholder ? cannot be used for a table name because
// MySQL only accepts values there - so the name must be a constant.)
const TABLE = dbConfig.farmersTable;

// The column list, written once and reused by every SELECT.
const COLUMNS = 'id, name, phone, village, milk_quantity, fat_percentage, created_at';


// ================================================================
//  1. VALIDATION - the same five rules as Assignment 13
//
//  File to compare with: php/db-crud/db-validate.php
//  The HTML form and the PHP pages check the same rules; an API
//  client is a program, not a person with a form, so the server
//  MUST check everything itself.
//
//  validateFarmer() returns:
//      { errors: {}, value: {...} }   errors is EMPTY when OK
// ================================================================

function validateFarmer(body) {
  const errors = {};
  // body may be missing or not an object at all - never trust it.
  const raw = (body && typeof body === 'object') ? body : {};

  // --- name: required, 3-60 characters, letters/spaces/dot/-/' -----
  const name = typeof raw.name === 'string' ? raw.name.trim() : '';
  if (name === '') {
    errors.name = 'Farmer name is required.';
  } else if (name.length < 3) {
    errors.name = 'Farmer name must be at least 3 characters long.';
  } else if (name.length > 60) {
    errors.name = 'Farmer name must not be longer than 60 characters.';
  } else if (!/^[A-Za-z .'-]+$/.test(name)) {
    errors.name = 'Farmer name may contain only letters, spaces, dot, apostrophe and hyphen.';
  }

  // --- phone: required, exactly 10 digits starting 6/7/8/9 --------
  // Spaces, dashes and + are stripped first, like str_replace()
  // does in checkPhone() in db-validate.php.
  const rawPhone = typeof raw.phone === 'string' ? raw.phone : '';
  const phone = rawPhone.replace(/[\s\-+]/g, '');
  if (phone === '') {
    errors.phone = 'Mobile number is required.';
  } else if (!/^[6-9][0-9]{9}$/.test(phone)) {
    errors.phone = 'Mobile number must be exactly 10 digits and must start with 6, 7, 8 or 9.';
  }

  // --- village: required, 3-60 characters --------------------------
  const village = typeof raw.village === 'string' ? raw.village.trim() : '';
  if (village === '') {
    errors.village = 'Village / address is required.';
  } else if (village.length < 3) {
    errors.village = 'Village / address must be at least 3 characters long.';
  } else if (village.length > 60) {
    errors.village = 'Village / address must not be longer than 60 characters.';
  }

  // --- milk_quantity: a NUMBER between 0.5 and 100 -----------------
  // Number() is used (not parseFloat) so that "18abc" also fails.
  // The CHECK constraint in schema.sql demands the same range.
  const milkRaw = raw.milk_quantity;
  const milk = (typeof milkRaw === 'number' || typeof milkRaw === 'string') ? Number(milkRaw) : NaN;
  if (milkRaw === undefined || milkRaw === null || milkRaw === '') {
    errors.milk_quantity = 'Average milk per day is required.';
  } else if (!Number.isFinite(milk)) {
    errors.milk_quantity = 'Average milk per day must be a number, for example 18.5.';
  } else if (milk < 0.5) {
    errors.milk_quantity = 'Average milk per day must be at least 0.5 litres.';
  } else if (milk > 100) {
    errors.milk_quantity = 'Average milk per day must not be above 100 litres.';
  }

  // --- fat_percentage: a NUMBER between 3 and 8 --------------------
  const fatRaw = raw.fat_percentage;
  const fat = (typeof fatRaw === 'number' || typeof fatRaw === 'string') ? Number(fatRaw) : NaN;
  if (fatRaw === undefined || fatRaw === null || fatRaw === '') {
    errors.fat_percentage = 'Fat percentage is required.';
  } else if (!Number.isFinite(fat)) {
    errors.fat_percentage = 'Fat percentage must be a number, for example 4.6.';
  } else if (fat < 3 || fat > 8) {
    errors.fat_percentage = 'Fat percentage must be between 3% and 8%.';
  }

  // The cleaned, type-correct values that will go to MySQL.
  const value = { name, phone, village, milk_quantity: milk, fat_percentage: fat };
  return { errors, value };
}


// ================================================================
//  2. SMALL HELPERS
// ================================================================

// mysql2 returns DECIMAL columns as strings ("18.50"), like the
// MySQLi of Assignment 13. An API should answer with JSON numbers,
// so every row is turned once before it is sent.
function toApiFarmer(row) {
  return {
    id: row.id,
    name: row.name,
    phone: row.phone,
    village: row.village,
    milk_quantity: Number(row.milk_quantity),
    fat_percentage: Number(row.fat_percentage),
    created_at: row.created_at
  };
}

// ":id" in the URL is always text. It must be a positive whole
// number before it can be used in SQL, even though the value will
// still be sent as a ? placeholder. Returns null when invalid.
function parseId(text) {
  const id = Number(text);
  return (Number.isInteger(id) && id > 0) ? id : null;
}

// The body of POST and PUT must be JSON. If express.json() did not
// parse anything, the client probably sent the wrong Content-Type
// or no body at all - answer 400 instead of crashing on undefined.
function hasJsonBody(req, res) {
  if (req.body === undefined || req.body === null || typeof req.body !== 'object' ||
      Array.isArray(req.body) || Object.keys(req.body).length === 0) {
    res.status(400).json({
      error: 'A JSON body is required, for example {"name":"...","phone":"...","village":"...","milk_quantity":12.5,"fat_percentage":4.6}'
    });
    return false;
  }
  return true;
}


// ================================================================
//  3. ENDPOINT 1 - GET /api/farmers
//
//  Reads every farmer, newest first (same ORDER BY as the
//  farmer-list.php page of Assignment 13).
//  Optional search:  GET /api/farmers?search=ramesh
//  Answer: 200 + a JSON array. An empty table is NOT an error - it
//  answers 200 with [].
// ================================================================
router.get('/', async (req, res, next) => {
  try {
    // The search box of Assignment 13 did LIKE %text%. Same idea,
    // with the value passed as a ? placeholder so it cannot inject SQL.
    const search = (typeof req.query.search === 'string') ? req.query.search.trim() : '';

    let rows;
    if (search !== '') {
      const like = '%' + search + '%';
      rows = await query(
        `SELECT ${COLUMNS} FROM ${TABLE}
         WHERE name LIKE ? OR village LIKE ? OR phone LIKE ?
         ORDER BY id DESC`,
        [like, like, like]
      );
    } else {
      rows = await query(`SELECT ${COLUMNS} FROM ${TABLE} ORDER BY id DESC`);
    }

    res.status(200).json(rows.map(toApiFarmer));
  } catch (error) {
    next(error);   // hands the error to the error handler in server.js
  }
});


// ================================================================
//  3b. ENDPOINT 2 - GET /api/farmers/:id
//
//  Answers:
//    200 + the farmer as JSON
//    400 when the id is not a positive whole number  (bad request)
//    404 when no farmer has that id                   (not found)
// ================================================================
router.get('/:id', async (req, res, next) => {
  try {
    const id = parseId(req.params.id);
    if (id === null) {
      return res.status(400).json({ error: 'Id must be a positive whole number, for example 3.' });
    }

    // Prepared statement: the id goes in as data, never as SQL.
    const rows = await query(`SELECT ${COLUMNS} FROM ${TABLE} WHERE id = ? LIMIT 1`, [id]);

    if (rows.length === 0) {
      return res.status(404).json({ error: `No farmer with id ${id}.` });
    }

    res.status(200).json(toApiFarmer(rows[0]));
  } catch (error) {
    next(error);
  }
});


// ================================================================
//  3c. ENDPOINT 3 - POST /api/farmers
//
//  Creates one farmer. The body must be a JSON object with the
//  five fields. Answers:
//    201 Created + the stored farmer + a Location header
//    400 Validation failed  (details says which field and why)
//    409 Conflict when the phone already exists (MySQL 1062)
//    500 anything else
// ================================================================
router.post('/', async (req, res, next) => {
  try {
    if (!hasJsonBody(req, res)) return;

    const { errors, value } = validateFarmer(req.body);
    if (Object.keys(errors).length > 0) {
      return res.status(400).json({ error: 'Validation failed.', details: errors });
    }

    // INSERT with five ? placeholders - the values are sent to MySQL
    // separately from the SQL text (prepared statement).
    const result = await query(
      `INSERT INTO ${TABLE} (name, phone, village, milk_quantity, fat_percentage)
       VALUES (?, ?, ?, ?, ?)`,
      [value.name, value.phone, value.village, value.milk_quantity, value.fat_percentage]
    );

    // Read the row back so the client also gets id and created_at.
    const rows = await query(`SELECT ${COLUMNS} FROM ${TABLE} WHERE id = ? LIMIT 1`, [result.insertId]);

    // 201 is the REST code for "created". Location tells the client
    // where the new resource can be read.
    res.status(201)
       .location(`/api/farmers/${result.insertId}`)
       .json(toApiFarmer(rows[0]));
  } catch (error) {
    // MySQL error 1062 = duplicate key. The only UNIQUE key on
    // farmers is the phone number. Same friendly-message idea as
    // the ER_DUP_ENTRY handling in php/db-crud/db-farmers.php.
    if (error.errno === 1062) {
      return res.status(409).json({
        error: 'That mobile number is already registered to another farmer.'
      });
    }
    next(error);
  }
});


// ================================================================
//  3d. ENDPOINT 4 - PUT /api/farmers/:id
//
//  Updates one farmer (a full update - all five fields are sent).
//  Answers:
//    200 + the farmer as it is AFTER the update
//    400 bad id or validation failed
//    404 no farmer with that id
//    409 the phone belongs to a different farmer
// ================================================================
router.put('/:id', async (req, res, next) => {
  try {
    const id = parseId(req.params.id);
    if (id === null) {
      return res.status(400).json({ error: 'Id must be a positive whole number, for example 3.' });
    }
    if (!hasJsonBody(req, res)) return;

    const { errors, value } = validateFarmer(req.body);
    if (Object.keys(errors).length > 0) {
      return res.status(400).json({ error: 'Validation failed.', details: errors });
    }

    // Read first: a missing farmer must answer 404, not 200 with
    // "0 rows affected" (the same two-step idea as farmer-edit.php).
    const existing = await query(`SELECT id FROM ${TABLE} WHERE id = ? LIMIT 1`, [id]);
    if (existing.length === 0) {
      return res.status(404).json({ error: `No farmer with id ${id}.` });
    }

    await query(
      `UPDATE ${TABLE}
       SET name = ?, phone = ?, village = ?, milk_quantity = ?, fat_percentage = ?
       WHERE id = ?`,
      [value.name, value.phone, value.village, value.milk_quantity, value.fat_percentage, id]
    );

    // Send back the stored row, so the client sees what MySQL kept.
    const rows = await query(`SELECT ${COLUMNS} FROM ${TABLE} WHERE id = ? LIMIT 1`, [id]);
    res.status(200).json(toApiFarmer(rows[0]));
  } catch (error) {
    if (error.errno === 1062) {
      return res.status(409).json({
        error: 'That mobile number is already registered to another farmer.'
      });
    }
    next(error);
  }
});


// ================================================================
//  3e. ENDPOINT 5 - DELETE /api/farmers/:id
//
//  Removes one farmer. Answers:
//    200 + {"message": ...}   when the row was really deleted
//    400 bad id
//    404 no farmer with that id  (nothing to delete)
// ================================================================
router.delete('/:id', async (req, res, next) => {
  try {
    const id = parseId(req.params.id);
    if (id === null) {
      return res.status(400).json({ error: 'Id must be a positive whole number, for example 3.' });
    }

    const result = await query(`DELETE FROM ${TABLE} WHERE id = ?`, [id]);

    // affectedRows === 0 means no row had that id.
    if (result.affectedRows === 0) {
      return res.status(404).json({ error: `No farmer with id ${id}.` });
    }

    res.status(200).json({ message: `Farmer ${id} was deleted.` });
  } catch (error) {
    next(error);
  }
});


module.exports = router;
