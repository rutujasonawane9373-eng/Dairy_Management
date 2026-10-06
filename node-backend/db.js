// ================================================================
//  DAIRY MANAGEMENT SYSTEM
//  ASSIGNMENT 15 : REST API  -  DATABASE CONNECTION
//  File: node-backend/db.js
//
//  The Node.js version of php/db-crud/db-connect.php (Assignment 13).
//
//  WHY mysql2?
//    Node.js has no built-in MySQL support (PHP ships with MySQLi).
//    "mysql2" is the npm package that speaks the MySQL protocol, it
//    supports Promises (async/await) and PREPARED STATEMENTS.
//
//  WHY A POOL AND NOT ONE CONNECTION?
//    A pool keeps several connections open and hands one to each
//    request. If two API calls arrive at the same moment, both can
//    talk to MySQL without waiting for the other to finish.
//    PHP does not need this because PHP opens and closes a
//    connection for every page load; a Node server stays running.
//
//  HOW TO INSTALL (only the first time, inside node-backend/):
//      npm install mysql2
// ================================================================

const mysql = require('mysql2/promise');
const dbConfig = require('./db-config');


// createPool() does not connect immediately - it prepares a queue of
// connections that are opened the first time somebody runs a query.
const pool = mysql.createPool({
  host:            dbConfig.host,
  user:            dbConfig.user,
  password:        dbConfig.password,
  database:        dbConfig.database,
  port:            dbConfig.port,
  charset:         dbConfig.charset,
  waitForConnections: true,   // if all connections are busy, wait in line
  connectionLimit:     10,    // at most 10 open connections at once
  queueLimit:          0     // 0 = never refuse a request, just queue it
});


// ================================================================
//  query(sql, params) - the ONE place that talks to MySQL
//
//  pool.execute() sends a PREPARED STATEMENT:
//
//      1. the SQL with  ?  placeholders is sent to MySQL first
//      2. MySQL compiles it into a plan
//      3. ONLY THEN are the values sent, separately, as data
//
//  Because the values can never be read as SQL, a value like
//      "' OR '1'='1"
//  is stored and compared as a plain string - it can never change
//  the command. This is the same protection the prepared statements
//  of Assignment 13 give (see php/db-crud/db-farmers.php).
//
//  Returns just the rows, so a route can say:
//      const rows = await query('SELECT ... WHERE id = ?', [id]);
//
//  On MySQL error the Promise rejects, and the route's
//  catch (error) { next(error); } sends a 500 to the client.
// ================================================================
async function query(sql, params = []) {
  const [rows] = await pool.execute(sql, params);
  return rows;
}


module.exports = { pool, query };
