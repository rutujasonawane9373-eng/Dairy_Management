# Assignment Mapping - Dairy Management System

This document maps every college practical assignment to the file / feature that demonstrates
it in the project. Use it to show your teacher exactly where each concept is implemented.

Current project phase: Phase 12 - Final integration (Assignment 16)

## Assignment → File/Feature

| # | Assignment | File / Feature | Status |
|---|------------|----------------|--------|
| 1 | Basic HTML (headings, paragraphs, ordered/unordered lists, images) | `frontend/pages/about.html` | ✅ Done |
| 2 | Semantic HTML tags (header, nav, main, section, article, aside, footer) | `frontend/index.html` | ✅ Done |
| 3 | CSS - inline, internal, external | External: `frontend/css/style.css`; Internal: `<style>` in `frontend/index.html`; Inline: `style=""` in `frontend/index.html` & `frontend/pages/about.html` | ✅ Done |
| 4 | Responsive design - Flexbox, Grid, Media Queries | `frontend/pages/dashboard.html` + `frontend/css/style.css` (Flexbox nav & summary, Grid stat cards, 3 media-query layouts) | ✅ Done |
| 5 | CSS positions and other properties | `frontend/pages/collection-centre.html` + `frontend/css/style.css` (ASSIGNMENT 5 block: static / relative / absolute / fixed / sticky positions, z-index, top-right-bottom-left, width-height, margin, padding, border, border-radius, box-shadow, overflow, opacity) | ✅ Done |
| 6 | JavaScript - basic events + array functions | External file: `frontend/js/main.js`; linked from `frontend/pages/dashboard.html` with `<script src="../js/main.js"></script>` at the end of `<body>`; UI = the "Today's Collection Register" section | ✅ Done |
| 7 | JavaScript frontend functionality + form validation | `frontend/pages/farmers.html` (the Farmer Registration form) + `frontend/js/main.js` (section 6) | ✅ Done |
| 8 | React - components, JSX | `react-app/` - Vite-based React SPA with functional components (Header, Dashboard, FarmerCard, MilkCollectionCard, Footer) and JSX | ✅ Done |
| 9 | React - props, state, hooks, events | `react-app/src/App.jsx` - 6 `useState` hooks (selected farmer, milk quantity, search text, show details, paid farmers, high-collection threshold), `onClick` / `onChange` event handlers, conditional rendering for the badges, details block and "no farmer found" message | ✅ Done |
| 10 | Fetch API + JSON | `react-app/src/ApiFarmerList.jsx` - `useEffect()` + `fetch()` + `response.json()` + `useState()`, records from `https://jsonplaceholder.typicode.com/users` drawn with `map()` and `key={farmer.id}`, loading message, error message + Retry. Rendered by `react-app/src/App.jsx:575` | ✅ Done |
| 11 | DOM manipulation + event handling | `frontend/pages/collection-centre.html` (the "Add a Collection Entry" form + the tools and summary of "Today's Collection Log") + `frontend/js/main.js` section 7 | ✅ Done |
| 12 | PHP - forms, validation, strings, sessions | `php/index.php`, `php/register.php`, `php/profile.php`, `php/logout.php` + `php/includes/{header,footer,functions}.php` | ✅ Done |
| 13 | PHP + MySQL - CRUD | `php/db-crud/` (db-config, db-connect, db-farmers, db-validate, db-helpers, db-header, db-footer) + `php/db-crud/{index,farmer-create,farmer-list,farmer-edit,farmer-delete}.php` + `database/schema.sql` | ✅ Done |
| 14 | Node.js + Express - server, routing, static files | `node-backend/server.js` (`GET /`, `GET /about`, `express.static('public')`, 404 page) + `node-backend/package.json` + static files `node-backend/public/{index.html,style.css,milk-can.svg}` | ✅ Done |
| 15 | REST API (Node.js + Express + DB) | `node-backend/routes/farmers.js` (GET, GET/:id, POST, PUT/:id, DELETE/:id on `/api/farmers`) + `node-backend/db.js` (mysql2 pool, prepared statements) + `node-backend/db-config.js` (gitignored MySQL settings, like A13's `db-config.php`) + `express.json()` and the router mount in `node-backend/server.js` | ✅ Done |
| 16 | Complete project integration | Hub section "Project Modules (Assignment 16)" in `frontend/index.html` (links all four parts) + section 3 of `node-backend/server.js` (`/frontend` and `/react` static mounts, home-page links, startup log). Detail: see the "Assignment 16 detail" section below | ✅ Done |

## Project structure (as of Phase 12 — Assignments 1-16)

```
Dairy_Management/
├── AGENTS.md
├── frontend/
│   ├── index.html
│   ├── pages/
│   │   ├── about.html
│   │   ├── dashboard.html       Assignment 6 (loads js/main.js)
│   │   ├── collection-centre.html  Assignment 5 + Assignment 11
│   │   │                         (loads js/main.js)
│   │   └── farmers.html         Assignment 7 (farmer registration form)
│   ├── css/
│   │   └── style.css            Assignments 3, 4, 5, 6, 7, 11, 12 and 13
│   ├── js/
│   │   └── main.js              Assignment 6 + Assignment 7 + Assignment 11
│   └── assets/
│       ├── cow.svg
│       ├── farm.svg
│       └── milk-can.svg
├── react-app/                 Vite + React single page application
│   ├── package.json
│   ├── vite.config.js
│   └── src/
│       ├── main.jsx           entry point - createRoot(...).render(<App />)
│       ├── App.jsx            Assignment 8 + Assignment 9 (+ renders Assignment 10)
│       ├── App.css            styles of the React page, incl. the Assignment 10 block
│       └── ApiFarmerList.jsx  Assignment 10 (fetch + JSON)
├── php/
│   ├── index.php              Assignment 12 (PHP home)
│   ├── register.php           Assignment 12 (form + $_POST + validation)
│   ├── profile.php            Assignment 12 (reads $_SESSION)
│   ├── logout.php             Assignment 12 (session_destroy)
│   ├── includes/              Assignment 12 shared files
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── functions.php
│   └── db-crud/               Assignment 13 (PHP + MySQL CRUD)
│       ├── db-config.php      host, user, password, database, port, charset
│       ├── db-connect.php     dbConnect() - the MySQLi connection + error page
│       ├── db-farmers.php     the four CRUD operations, all prepared statements
│       ├── db-validate.php    five server-side validation rules
│       ├── db-helpers.php     safeText(), flash messages, the rate board
│       ├── db-header.php      shared <head>, <header>, <nav>, database strip
│       ├── db-footer.php      shared </main>, <footer>
│       ├── index.php          connection check + map of the four operations
│       ├── farmer-create.php  CREATE -> INSERT
│       ├── farmer-list.php    READ   -> SELECT (+ search)
│       ├── farmer-edit.php    UPDATE -> SELECT one, then UPDATE
│       └── farmer-delete.php  DELETE -> two-step confirmation, then DELETE
├── database/
│   └── schema.sql             Assignment 13: CREATE DATABASE + CREATE TABLE
│                             + 6 sample farmers
├── node-backend/              Assignments 14 + 15: Express server + REST API
│   ├── package.json           "npm start" -> "node server.js"; deps: express, mysql2
│   ├── server.js              Express app - A14 routes/static/404 + A15 API mount
│   ├── db-config.js           Assignment 15: MySQL settings (GITIGNORED - has the
│   │                          password, like php/db-crud/db-config.php)
│   ├── db.js                  Assignment 15: mysql2 connection pool + query() helper
│   ├── routes/
│   │   └── farmers.js         Assignment 15: the five /api/farmers endpoints
│   └── public/                files served by express.static()
│       ├── index.html         a STATIC page (reachable at /index.html)
│       ├── style.css          a STATIC stylesheet (reachable at /style.css)
│       └── milk-can.svg       a STATIC image (reachable at /milk-can.svg)
└── docs/
    ├── assignment-mapping.md
    └── viva-notes.md
```

`node-backend/routes/farmers.js` is **Assignment 15** - a JSON REST API for the same
`dairy_management.farmers` table that Assignment 13 reads and writes through PHP.

There is still **one** JavaScript file for the whole `frontend/` part - `frontend/js/main.js` -
and it is now loaded by **three** pages: `dashboard.html` (Assignment 6), `farmers.html`
(Assignment 7) and `collection-centre.html` (Assignment 11). Every part of the script returns
at once when the elements it needs are not on the page, so one file can serve all three.

The five Assignment 13 pages load **no JavaScript at all**; their tables are drawn by
`foreach` loops in PHP.

## Assignment 13 detail — PHP + MySQL Database Connectivity and CRUD

Folder: `php/db-crud/`, plus `database/schema.sql`. Assignment 13 was built **alongside**
Assignment 12 rather than inside it, so `php/includes/` and the four Assignment 12 pages were
not modified in any way.

### How to run it

```
cd C:\...\Dairy_Management
C:\xampp\php\php.exe -S localhost:8000
```

then open `http://localhost:8000/php/db-crud/`. The server must be started from the **project
root**, because the pages link up two levels to `frontend/css/style.css`.

The database must be created once with `database/schema.sql`, and the root password written
into `DB_PASS` in `php/db-crud/db-config.php`.

### 1. Database connection — `php/db-crud/db-connect.php`

| Part | Code | Purpose |
|---|---|---|
| Settings | `db-config.php` | `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT`, `DB_CHARSET`, `FARMERS_TABLE` — the only file that holds the password |
| Open it | `$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);` | the whole of "database connectivity" in one line |
| Character set | `$connection->set_charset(DB_CHARSET);` | must be `utf8mb4` on both sides, or Indian names are stored as `????` |
| Error handling | `try / catch (mysqli_sql_exception $problem)` | PHP 8.1+ throws instead of only setting `connect_error` |
| Error page | `dbShowError()` | prints a styled page with the real reason, then `exit` |
| Close it | `dbDisconnect()` | ends the call politely |

### 2. The table — `database/schema.sql`

Database `dairy_management`, one table `farmers`:

| Column | Type | Why |
|---|---|---|
| `id` | `INT AUTO_INCREMENT PRIMARY KEY` | MySQL numbers the farmers and never repeats a number |
| `name` | `VARCHAR(60) NOT NULL` | a name is text, never a number |
| `phone` | `VARCHAR(15) NOT NULL UNIQUE` | text, so a leading zero survives; `UNIQUE` stops a double registration |
| `village` | `VARCHAR(60) NOT NULL` | the address |
| `milk_quantity` | `DECIMAL(6,2) NOT NULL` | exact decimal, unlike `FLOAT` |
| `fat_percentage` | `DECIMAL(3,1) NOT NULL` | 3% to 8% |
| `created_at` | `TIMESTAMP DEFAULT CURRENT_TIMESTAMP` | written by MySQL, so the `INSERT` never sends a date |

Plus `UNIQUE KEY uq_farmers_phone (phone)` and two `CHECK` constraints for milk and fat.
6 sample farmers are inserted by the same file.

### 3. The four CRUD operations — `php/db-crud/db-farmers.php`

| CRUD | SQL | Function | Page | Type letters |
|---|---|---|---|---|
| Create | `INSERT` | `dbInsertFarmer()` | `farmer-create.php` | `sssdd` |
| Read | `SELECT` | `dbSelectFarmers()` | `farmer-list.php` | `sss` |
| Read one | `SELECT ... WHERE id = ?` | `dbSelectFarmerById()` | `farmer-edit.php`, `farmer-delete.php` | `i` |
| Update | `UPDATE` | `dbUpdateFarmer()` | `farmer-edit.php` | `sssddi` |
| Delete | `DELETE` | `dbDeleteFarmer()` | `farmer-delete.php` | `i` |

`dbFarmerSummary()` adds the four summary cards with `COUNT()`, `SUM()` and `AVG()`. It uses
`query()` on purpose — its SQL has no `?` and no typed text, so there is nothing to protect.

### 4. Prepared statements — the same three steps in all five functions

```php
$statement = $connection->prepare($sql);          // 1. SQL once, with ? marks
$statement->bind_param('sssdd', ...);             // 2. the types, then the values
$statement->execute();                            // 3. run it
```

Type letters: `s` string, `i` integer, `d` double. They must appear in the **same order** as
the `?` marks. `bind_param()` sends the values **separately** from the SQL text, so no typed
value can ever become part of a command — that is SQL injection being prevented.

### 5. Validation and error handling

| Concern | Where | How |
|---|---|---|
| Five field rules | `db-validate.php` | `checkName()`, `checkPhone()`, `checkVillage()`, `checkMilkQuantity()`, `checkFatPercentage()`, run by `runFarmerValidation()` |
| Validation before any SQL | `farmer-create.php`, `farmer-edit.php` | `count($errors) === 0` gates the write, so a wrong value never reaches MySQL |
| Duplicate phone | `dbInsertFarmer()`, `dbUpdateFarmer()` | MySQL error **1062** is turned into a sentence instead of a stack trace |
| Connection lost / no database | `db-connect.php` | `dbShowError()` writes a readable page listing the four things to check |
| Missing id, deleted row, 0 changed rows | the CRUD functions | `affected_rows` is checked and reported honestly |
| Delete safety | `farmer-delete.php` | two-step confirmation; the farmer's name must be typed correctly (`strcasecmp()`) |
| SQL not prepared, no user input | `dbFarmerSummary()` | `query()` used deliberately, with the reason written in a comment |
| Printing a value | `db-helpers.php` | `safeText()` = `htmlspecialchars()`, so a stored value can never become HTML |

### 6. The two different kinds of danger — the point to make first in the viva

| Danger | What it does | Stopped by |
|---|---|---|
| **SQL injection** | typed text becomes part of the SQL command | prepared statements (`db-farmers.php`) |
| **XSS** | typed text becomes HTML code on the page | `htmlspecialchars()` via `safeText()` (`db-helpers.php`) |

A complete project needs both. Assignment 13 demonstrates both.

### 7. New CSS

`frontend/css/style.css` ends with an `ASSIGNMENT 13` block (~225 lines): `.db-code`,
`.db-value`, `.db-message` (+ `.db-ok` / `.db-error`), `.db-error-box`, `.db-danger-box`,
`.db-search-form`, `.db-search-row`, `.db-action-link` (+ `.db-action-delete`), `.btn-danger`,
`.db-confirm-row` and a `max-width: 600px` rule. Every page-level rule is scoped to
`body.page-db`, which only the five A13 pages carry. **No rule above that block was changed**,
so Assignments 1-12 look exactly the same. The file was only appended to — `git diff` shows
insertions plus the old "no newline at end of file" marker.

## Assignment 11 detail - DOM manipulation and event handling

Page: `frontend/pages/collection-centre.html`. Two working parts of the ordinary
collection-centre screen are driven by the DOM, not by a separate demo box:

1. the **"Add a Collection Entry"** form (the weighbridge entry of one can), and
2. the **tools and summary of "Today's Collection Log"** (search, status filter, five
   buttons, three numbers and a progress bar).

All the code is `frontend/js/main.js` section 7 (the file header lists it from line 957).
The page loads the same script as before, with
`<script src="../js/main.js"></script>` at the end of `<body>`.

### DOM manipulation - what is used where

| What it does | Method | Where in `main.js` | Real use in the page |
|---|---|---|---|
| Find one element by its id | `document.getElementById()` | `setTextById()` (1076), `readLogEntryForm()` (1500), `applyLogFilter()` (1229), `updateLogSummary()` (1124) | finds the log body, the form boxes, the summary numbers, the progress bar and the five buttons |
| Find the first match | `querySelector()` | `markSelectedAsPaid()` (1308), `removeSelectedRow()` (1334) | finds the one row that has the class `selected` |
| Find every match | `querySelectorAll()` | `logRows()` (1014), `readLogEntry()` (1022), `clearLogSelection()` (1106), `highlightLargestEntry()` (1274), `removeAddedRows()` (1360), `refreshPendingClasses()` (1161) | every log row, the six cells of one row, every selected / peak / added row |
| Write text | `.textContent =` | `setTextById()` (1076), `createLogEntryRow()` (1449), `markSelectedAsPaid()` (1308), `setEntryMessage()` (1517) | the status strip, the three summary numbers, the progress text, every cell of a new row, one changed status cell |
| Change a class | `classList.add()` / `.remove()` | `selectLogRow()` (1185), `refreshPendingClasses()` (1161), `highlightLargestEntry()` (1274), `markSelectedAsPaid()` (1308), `resetLogView()` (1377) | `selected` on one row, `is-pending` on unpaid rows, `peak-row` on the biggest can, `ok` / `error` on the message box |
| Change a style | `.style.display`, `.style.width`, `.style.fontWeight` | `applyLogFilter()` (1229), `updateLogSummary()` (1124), `highlightLargestEntry()` (1274) | hides the rows that do not match, sets the width of the progress bar, bolds the biggest can |
| Create an element | `document.createElement()` | `createLogEntryRow()` (1449) | one `<tr>` and six `<td>` for every new entry |
| Put an element on the page | `appendChild()` | `createLogEntryRow()` (1449), `addLogEntry()` (1483) | cell into row, row into `<tbody id="collection-log-body">` |
| Delete an element | `.remove()` | `removeSelectedRow()` (1334), `removeAddedRows()` (1360) | deletes the selected row, deletes the rows this session added |
| Mark an element | `setAttribute()` | `createLogEntryRow()` (1449) | `data-added="yes"` - the mark that lets "Remove Added Rows" spare the 19 rows written in the HTML |

### Events

| Event | Element | What it does |
|---|---|---|
| `DOMContentLoaded` | the document | `startApp()` -> also calls `startCollectionLog()` (1551), which draws the summary for the first time and connects every event below |
| `submit` | `<form id="log-entry-form">` | `event.preventDefault()`, checks the four boxes, then `createLogEntryRow()` + `appendChild()` add the row and the summary is rewritten |
| `input` | `#log-search` | on every keystroke `applyLogFilter()` hides or shows the rows |
| `input` | the four entry boxes | a box that is already showing an error is re-checked while typing |
| `change` | `#log-status-filter` | shows only the Paid or only the Pending entries |
| `change` | `#log-status` | read when the form is submitted (it decides the Status cell of the new row) |
| `click` | every `<tr>` of the log | `selectLogRow()` - takes the `selected` class off the old row, puts it on the clicked one and describes that can |
| `click` | `#log-highlight-largest` | `reduce()` finds the biggest can in view; the class moves to its row |
| `click` | `#log-mark-paid` | the Status cell text is replaced with "Paid" and `is-pending` is removed |
| `click` | `#log-remove-selected` | the selected row is deleted with `.remove()` |
| `click` | `#log-remove-added` | every `tr[data-added="yes"]` is deleted; the 19 HTML rows stay |
| `click` | `#log-reset-view` | the search box and the dropdown are emptied and every class is taken off the rows |
| `reset` | `<form id="log-entry-form">` | the four error messages are removed (also fires from `form.reset()`) |

### Reuse instead of new code

- The three entry rules reuse the Assignment 7 validators `validateFarmerName()`,
  `validateMilkQuantity()` and `validateFatPercentage()`, and the rate is worked out by the
  Assignment 7 rate-board rule `rateForFat()` - only `validateSnf()` is new.
- The helpers `checkField()`, `clearFieldError()`, `fieldValue()` and `totalLitres()` are
  shared with Assignment 7 and Assignment 6, so a rule is written once and reused.
- The page needs almost no new CSS: the form reuses the Assignment 7 `.farmer-form`,
  `.form-grid`, `.form-row`, `.form-buttons` and `.btn-light`; the toolbar reuses the
  Assignment 6 `.register-tools` and `.register-status`; the summary reuses `.flex-row`,
  `.flex-item` and `.total-value`; the progress bar reuses the Assignment 5 `.tank-gauge` /
  `.tank-fill`; the selected row reuses `.log-window tbody tr.selected`.

### New CSS

`frontend/css/style.css` ends with an `ASSIGNMENT 11` block (about 80 lines, four rules and
one media query): `.form-row select`, `.log-window tbody tr.is-pending td:last-child`,
`.log-window tbody tr.peak-row`, `.log-tools button` (+ `.log-tools button.btn-light`), and
a `max-width: 600px` rule that makes the five buttons full width on a phone. No rule above
that block was changed, so Assignments 1-7 look exactly the same.

## Assignment 7 detail — JavaScript frontend functionality and form validation

Page: `frontend/pages/farmers.html` — the **Farmer Registration** form, the normal working
form of the society. A new milk producer is registered here; the farmers registered in the
current visit are listed in the "Farmers Registered in This Session" table under it. The page
fills the `pages/farmers.html` link that the menu on `index.html` and `dashboard.html`
already had as a placeholder.

All logic is in `frontend/js/main.js`, section 6 (still the only JavaScript file in the
project). There is no server, no PHP, no database and no `fetch()` call.

### Form fields

| Field | `id` / `name` | Type | HTML5 attributes | JavaScript rule |
|---|---|---|---|---|
| Farmer Name | `farmer-name` / `farmerName` | `text` | `required minlength="3" maxlength="40" pattern="[A-Za-z][A-Za-z .'-]{2,}"` | not empty; at least 3 characters; only letters, spaces, `.`, `'`, `-`; must start with a letter |
| Mobile Number | `farmer-mobile` / `mobile` | `tel` | `required maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric"` | not empty; digits only (spaces ignored); exactly 10 digits; must start with 6, 7, 8 or 9 |
| Email | `farmer-email` / `email` | `email` | `maxlength="60"` (optional) | empty is accepted; if filled it must match the email pattern |
| Village / Address | `farmer-village` / `village` | `text` | `required minlength="3" maxlength="60"` | not empty; at least 3 characters |
| Average Milk per Day | `milk-quantity` / `milkQuantity` | `number` | `required min="0.5" max="100" step="0.5"` | not empty; must be a number; greater than 0; not above 100 litres |
| Fat Percentage | `fat-percentage` / `fatPercentage` | `number` | `required min="3" max="8" step="0.1"` | not empty; must be a number; between 3% and 8% |

The form carries `novalidate`, so the browser's own pop-up bubbles are switched **off** and
the messages written by JavaScript next to each field are the ones the farmer sees. The HTML5
attributes are still declared on every input, and the JavaScript repeats the same rules so
that the messages can be explained in the project's own words.

### Events

| Event | Element | What it does |
|---|---|---|
| `submit` | `<form id="farmer-form">` | `event.preventDefault()` (nothing is sent — no server yet), then `checkWholeForm()`; on failure it writes the number of wrong fields, calls `focusFirstError()` and stops; on success it calls `registerFarmer()`, `form.reset()` and writes the green success message |
| `blur` | each of the six inputs | checks that one field and shows or removes its message |
| `input` | each of the six inputs | re-checks a field that is already showing an error, so the message disappears as soon as the value becomes correct |
| `reset` | `<form id="farmer-form">` | `clearAllFieldErrors()` — removes the red borders, the messages and `aria-invalid`; fires both from the "Clear Form" button and from `form.reset()` |

### Functions

**Validation logic (no page access — receives a value, returns a message)**

| Function | Rule it checks |
|---|---|
| `validateFarmerName()` | not empty, 3+ characters, letters/spaces only |
| `validateMobile()` | 10 digits starting with 6-9 |
| `validateEmail()` | correct email shape when it is filled (optional field) |
| `validateVillage()` | not empty, 3+ characters |
| `validateMilkQuantity()` | numeric and greater than 0 |
| `validateFatPercentage()` | numeric and between 3 and 8 |
| `rateForFat()` | the rate board rule — fat 3.5% and above → 42 Rs/litre, below → 40 Rs/litre |

**Interface functions (these change the page)**

| Function | What it does |
|---|---|
| `checkField()` | runs one rule and shows or clears its message — the bridge between logic and page |
| `checkWholeForm()` | runs all six rules, returns how many failed |
| `showFieldError()` / `clearFieldError()` | add/remove `.field-error`, `aria-invalid` and the message text |
| `focusFirstError()` | puts the cursor in the first rejected box |
| `setFormStatus()` | writes the one message box above the form with the `ok` / `error` class |
| `readFormValues()` | collects the checked values into one object (the row that Assignment 13 will store) |
| `registerFarmer()` | `push()` the farmer into `registeredFarmers` and redraw the table |
| `renderRegisteredFarmers()` | draws the table with `forEach()` and updates the count line with `reduce()` |
| `startFarmerForm()` | connects the four events; returns immediately if the form is not on the page |

### Success behaviour

On a valid submission the farmer's record is pushed into the `registeredFarmers` array, one
row is added to the table below the form, the count line is recalculated with `reduce()`
(litres a day between all registered farmers) and the message box turns green with the
farmer's name, village, litres, fat, rate and amount. The form is then emptied with
`form.reset()`. Nothing is sent anywhere and nothing is stored after the page is closed,
because Assignments 12 and 13 are not written yet.

### New CSS

`frontend/css/style.css` ends with an `ASSIGNMENT 7` block: `.farmer-form`, `.form-grid`
(a two-column CSS Grid that becomes one column under 600px), `.form-row`, `.field-hint`,
`input.field-error` (error state), `.error-message`, `.form-message.ok` / `.form-message.error`
(success and failure states of the message box), `.form-buttons` and `.btn-light`. No rule
above that block was changed, so Assignments 1-6 look exactly the same.

## Assignment 6 detail — JavaScript events and array functions

Page: `frontend/pages/dashboard.html` — the "Today's Collection Register" section is the normal
working list of the shift. It starts empty in the HTML and is drawn by
`frontend/js/main.js`. There is **no inline JavaScript** anywhere in the project (no `onclick=`
attributes); all behaviour is in the external file.

Data: a plain JavaScript array `collections` of 8 objects, each with
`farmer`, `liters`, `fat`, `snf`, `rate` and `shift`.

### Events

| Event | HTML element that triggers it | What it does |
|---|---|---|
| `DOMContentLoaded` | the document itself | runs `startApp()`, which connects all the other events and draws the register the first time |
| `click` | `#refresh-totals` (`<button class="btn">`) | recalculates the list and totals and writes a "Totals recalculated at …" message |
| `click` | `.stats-grid .stat-card` (the four stat cards) | selects the card (adds the `selected` class); clicking again clears the selection |
| `mouseover` | `.stats-grid .stat-card` | writes the meaning of that card into the status strip |
| `mouseout` | `.stats-grid .stat-card` | puts the standard summary message back |
| `click` | `#shift-summary .flex-item` (Morning / Evening / This Month) | filters the register by that shift, using the block's `data-shift` value; clicking again clears the filter |
| `click` | each `<tr>` written inside `#collection-body` | selects the row and describes that farmer's collection in the status strip |
| `input` | `#farmer-search` (`<input type="search">`) | on every keystroke calls `find()` and reports the matching farmer |
| `change` | `#collection-filter` (`<select>`) | shows only the collections of 15 L / 20 L and above |

Every one of these events writes its result into the same status strip,
`#register-status`, so there is only one place to read what happened.

### Array functions

| Function | Where in `main.js` | What it does in this project |
|---|---|---|
| `forEach()` | `showRecords()` | draws one `<tr>` for every collection record, and attaches a click listener to each row |
| `map()` | `paymentAmounts()` | builds a **new** array of payment amounts (`liters * rate`) from the records |
| `filter()` | `recordsAtLeast()` | keeps only the records at or above the chosen litres |
| `filter()` | `refreshRegister()` | keeps only the records of the clicked shift |
| `filter()` | `showTotals()` | counts the low-fat cans (fat below 3.5%), which are paid at the lower rate |
| `find()` | `findFarmer()` | returns the first record whose farmer name contains the typed text, or `undefined` |
| `reduce()` | `totalLitres()` | adds up the litres (starts from `0`) |
| `reduce()` | `totalAmount()` | adds up the array of amounts that `map()` created |
| `reduce()` | `averageFat()` | adds up the fat values and divides by the number of records |

### Safety

Every element is fetched with `document.getElementById()` / `document.querySelectorAll()` and is
used only after checking that it exists, so `main.js` can be loaded on any page of the project
without producing an error. The status strip is written with `textContent`, never with
`innerHTML`, so typed text can never be inserted into the page as HTML.

## Assignment 5 detail — CSS Positions and other properties

Page: `frontend/pages/collection-centre.html` — the day-to-day working screen of the
collection centre (tank status cards, today's collection log, shift summary, chilling
unit picture, rate board). The positioning rules are part of the normal UI, not a
separate demo section. All rules are in the "ASSIGNMENT 5" section of
`frontend/css/style.css`.

| CSS property | Class / selector used | Where it appears |
|---|---|---|
| `position: static` | `.centre-note` | Shift information strips above the cards, log and summary |
| `position: relative` | `.tank-card`, `.photo-frame` | The four status cards (parent of each badge), the chilling-unit picture frame |
| `position: absolute` | `.status-badge`, `.photo-caption` | FULL / OK / TESTING / OPEN pills inside the cards; caption over the picture |
| `position: fixed` | `.rate-ticker` | "Today's Milk Rate" board pinned to the screen corner (z-index 100) |
| `position: sticky` | `nav`, `.log-window thead th` | Main menu on **every** page; column titles inside the scrollable collection log |
| `z-index` | 2 / 3 / 50 / 100 | Sticky table header → badges → sticky menu → fixed rate board |
| `top/right/bottom/left` | `.status-badge`, `.photo-caption`, `.rate-ticker`, `nav`, `.log-window thead th` | Badge corner, caption edge, rate board corner, sticky offsets |
| `width` / `height` | `.tank-gauge`, `.tank-fill`, `.gauge-92/74/58/35`, `.photo-frame`, `.rate-ticker`, `.log-window` | Gauge bar 20px, fill widths 92/74/58/35%, picture frame 260×220, rate board 290px, log max-height 230px |
| `margin` / `padding` | `.centre-note`, `.tank-card`, `.status-badge`, `.log-window`, `.photo-frame`, `body.page-centre footer` | Spacing around and inside the boxes |
| `border` / `border-radius` | `.centre-note`, `.status-badge`, `.tank-gauge`, `.photo-frame`, `.photo-caption`, `.rate-ticker` | Gold accent strip on notes, pill badges, rounded cards and frame |
| `box-shadow` | `nav`, `.tank-card`, `.status-badge`, `.photo-frame`, `.rate-ticker` | Soft lift under the sticky menu, cards, badges, picture frame, rate board |
| `overflow` | `.tank-card`, `.photo-frame`, `.rate-ticker` (hidden), `.log-window` (auto) | Keeps card/badge text and the picture inside their boxes; own scrollbar for the log |
| `opacity` | `.tank-fill` (0.85), `.photo-caption` (`rgba(75,46,14,0.7)`) | Translucent tank fill; see-through caption overlay |

Notes:
- The sticky menu is turned off in the `max-width: 600px` media query so a tall stacked
  menu does not fill the phone screen.
- The fixed rate board becomes a full-width bottom strip on phones.

## Assignment 10 detail — Fetch API and JSON

Component: `react-app/src/ApiFarmerList.jsx`, rendered by `react-app/src/App.jsx:575` as the
last section of `<main>` ("Farmer Information from API"). Assignments 8 and 9 keep working
unchanged — `App.jsx` only gained one import and one line of JSX.

### The whole flow in one line

```
API URL -> fetch() -> HTTP response -> response.json() -> JSON data -> useState() -> .map() -> UI
```

### Step by step

| Step | Code in `ApiFarmerList.jsx` | What it does |
|---|---|---|
| API URL | `const API_URL = 'https://jsonplaceholder.typicode.com/users'` (line 36) | free public test API, read-only, no key, answers with a JSON array of 10 user objects |
| `fetch()` | `const response = await fetch(API_URL)` (line 49) | browser's built-in HTTP client; returns a Promise that resolves later with a **response**, not with the data |
| status check | `if (!response.ok) throw new Error(...)` (lines 56-58) | `fetch()` only rejects on a dead network; a 404/500 arrives as a normal response, so `response.ok` (true for 200-299) is tested by hand |
| `response.json()` | `const data = await response.json()` (line 65) | the body arrives as **text**; `response.json()` parses it into real JavaScript data (an array of objects) and is itself a Promise, so it needs `await` |
| `useState()` | `useState([])`, `useState(true)`, `useState('')` (lines 103, 108, 113) | three states: `apiFarmers` (the data), `loading` (true while the request runs), `error` (friendly message, `''` when fine) |
| `useEffect()` | `useEffect(() => { loadFarmerData() }, [])` (lines 152-158) | runs the request **once**, when the component loads; the empty dependency array `[]` is why a state change does not re-fetch |
| loading | `{loading && <p className="api-message">Loading farmer data...</p>}` (line 173) | the message shows while the request is in flight |
| error handling | `try / catch / finally` in `loadFarmerData()` (lines 119-137) | `catch` writes the real error to `console.error` and the friendly text `"Unable to load farmer data. Please try again."` to the page; `finally` always clears `loading`; the **Retry** button re-runs the same fetch |
| `map()` + `key` | `apiFarmers.map((farmer) => <ApiFarmerCard key={farmer.id} farmer={farmer} />)` (lines 193-195) | one card per record; `key={farmer.id}` is the unique identity React needs to update a list correctly |

### Fields displayed

`ApiFarmerCard` reads `name`, `username`, `email`, `address.city` (nested object — it shows how
nested JSON is used) and `id` from each record.

### New CSS

`react-app/src/App.css` ends with an `Assignment 10` block: `.api-section`, `.api-note` (+ `code`),
`.api-message`, `.api-message-box`, `.api-error`, `.api-farmer-card .card-text`. No earlier rule was
changed.

### Not used

No Axios, no PHP, no MySQL, no Node.js/Express, no local JSON file — only the browser's built-in
`fetch()` against the public API above.

## Assignment 14 detail — Node.js + Express web server

Folder: `node-backend/`. Nothing in `frontend/`, `react-app/`, `php/` or `database/` was
touched to build it, so Assignments 1-13 are unchanged.

### How to run it

```
cd C:\...\Dairy_Management\node-backend
npm install     (only the first time - it creates node_modules/)
npm start
```

then open `http://localhost:3000`. Stop the server with `Ctrl + C`.

### Files

| File | Job |
|---|---|
| `package.json` | the project file. `"start": "node server.js"` and one dependency, `express` |
| `server.js` | the whole server — 4 numbered parts, explained below |
| `public/index.html` | a **static** page, reached at `/index.html` |
| `public/style.css` | a **static** stylesheet, reached at `/style.css` |
| `public/milk-can.svg` | a **static** image, reached at `/milk-can.svg` (a copy of `frontend/assets/milk-can.svg`) |
| `.gitignore` | keeps `node_modules/` and `db-config.js` (the MySQL password) out of git |
| `routes/farmers.js` | **Assignment 15** — the five REST endpoints (see the A15 section below) |

### 1. The four parts of `server.js`

| Part | Code | What it does |
|---|---|---|
| Setup | `require('express')`, `express()`, `const PORT = process.env.PORT \|\| 3000` | creates the app; the port can be changed from outside with `PORT=3100 npm start` |
| Routes | `app.get('/')`, `app.get('/about')` | two GET routes, each answers with `res.send()` and an HTML page written inside `server.js` |
| Static files | `app.use(express.static(PUBLIC_FOLDER))` | any file that exists in `public/` is sent as-is — no code runs |
| 404 page | `app.use((req, res) => res.status(404).send(...))` | runs only if nothing above answered; prints the address that was asked for |
| Start | `app.listen(PORT, () => console.log(...))` | keeps the program alive and prints the addresses to visit |

`PUBLIC_FOLDER` is built with `path.join(__dirname, 'public')`. `__dirname` is the folder of
`server.js`, so the path is right on Windows, Mac and Linux.

### 2. The one thing that matters — route order

Express checks its handlers **from top to bottom** and uses the **first one that answers**.
So `app.get('/')` is written **before** `express.static()`:

```
GET /            ->  the route in server.js answers        (home page)
GET /about       ->  the route in server.js answers        (about page)
GET /style.css   ->  no route matches, express.static() reads public/style.css
GET /milk-can.svg->  no route matches, express.static() reads public/milk-can.svg
GET /index.html  ->  no route matches, express.static() reads public/index.html
GET /oops        ->  nothing matches, the 404 page answers
```

If the order were reversed, `express.static()` would answer `/` with `public/index.html`
(because a folder's `index.html` is served automatically) and `app.get('/')` would never run.
That was the one real bug found while building this assignment.

### 3. Static file URLs are short

The folder name is **not** part of the address. A file at `public/style.css` is requested as
`/style.css`, never `/public/style.css`.

### 4. Safety

The 404 page prints back the address the visitor typed. That text goes through `escapeHtml()`
before it is placed in the HTML, so it can never become a tag — the same idea as `safeText()`
in `php/db-crud/db-helpers.php` (Assignment 13).

### What was deliberately left out of Assignment 14 — and added by 15

`server.js` as Assignment 14 shipped had no `res.json()`, no `express.json()`, no
`POST`/`PUT`/`DELETE` and no database connection — Assignment 14 only proved that the server
runs, that it routes, and that it serves files. **Assignment 15 added exactly those four
things** (`express.json()`, the `/api/farmers` router, the MySQL pool and the JSON error
handler) without changing any of the A14 behaviour: `GET /`, `GET /about`, the static files
and the HTML 404 page still answer exactly as before.

## Assignment 15 detail — REST API (Node.js + Express + MySQL)

Folder: `node-backend/`. Same server as Assignment 14, one new dependency (`mysql2`) and three
new files. Nothing in `frontend/`, `react-app/`, `php/` or `database/` was touched, so
Assignments 1-14 are unchanged.

### How to run it

```
cd C:\...\Dairy_Management\node-backend
npm install        (first time only - downloads express + mysql2)
npm start
```

MySQL must be running (the same `dairy_management` database that Assignment 13 uses).
Put your MySQL password in the environment variable `DB_PASS`, or type it once into the
local (gitignored) `db-config.js`. Then try:

```
GET    http://localhost:3000/api/farmers
GET    http://localhost:3000/api/farmers/1
GET    http://localhost:3000/api/farmers?search=ramesh
POST   http://localhost:3000/api/farmers          (JSON body)
PUT    http://localhost:3000/api/farmers/1         (JSON body)
DELETE http://localhost:3000/api/farmers/1
```

### Files

| File | Job |
|---|---|
| `routes/farmers.js` | the five endpoints + the five validation rules, all in one `Router` |
| `db.js` | `mysql2.createPool()` connection pool + `query(sql, params)` — the only place that talks to MySQL |
| `db-config.js` | MySQL settings (host, user, password, database, port, table) — **gitignored**, exactly like `php/db-crud/db-config.php` |
| `server.js` | `express.json()`, mounts the router at `/api/farmers`, JSON 404 for `/api/*`, JSON error handler |
| `package.json` | dependencies: `express`, `mysql2` |
| `.gitignore` | `node_modules/` + `db-config.js` (so the password is never committed) |

### 1. The five endpoints

| Method | URL | Does | Success | Errors |
|---|---|---|---|---|
| GET | `/api/farmers` | list all (optional `?search=`) | 200 + JSON array | 500 |
| GET | `/api/farmers/:id` | read one | 200 + JSON object | 400 bad id, 404 no row |
| POST | `/api/farmers` | create | **201** + the new farmer + `Location` header | 400 validation, 409 duplicate phone |
| PUT | `/api/farmers/:id` | update (full) | 200 + the farmer after update | 400, 404, 409 |
| DELETE | `/api/farmers/:id` | delete | 200 + `{"message": ...}` | 400 bad id, 404 no row |
| — | any other `/api/...` | — | — | 404 + JSON `Unknown API endpoint` |

### 2. Prepared statements — where and why

Every SQL statement in `routes/farmers.js` sends `?` placeholders and passes the values as a
separate array:

```js
await query('SELECT ... FROM farmers WHERE id = ? LIMIT 1', [id]);
await query('INSERT INTO farmers (name, phone, ...) VALUES (?, ?, ...)', [...]);
```

`query()` (in `db.js`) calls `pool.execute(sql, params)` from `mysql2`, which lets MySQL
compile the statement **before** the values arrive, so a value like `' OR '1'='1` is compared
as text and can never become SQL. Same guarantee as the MySQLi prepared statements of
Assignment 13 (`php/db-crud/db-farmers.php`). The table name is the one thing that cannot be
a placeholder — it is a fixed constant (`farmers`) taken from `db-config.js`, never user input.

### 3. Validation — the same five rules as Assignment 13

`validateFarmer()` in `routes/farmers.js` repeats the rules of `php/db-crud/db-validate.php`:
name 3-60 letters/spaces/dot/-/', phone exactly 10 digits starting 6-9 (spaces/dashes/`+`
stripped first), village 3-60, `milk_quantity` 0.5-100, `fat_percentage` 3-8. Failures answer
**400** with a `details` object naming each bad field, e.g.:

```json
{ "error": "Validation failed.",
  "details": { "phone": "Mobile number must be exactly 10 digits and must start with 6, 7, 8 or 9." } }
```

Uniqueness of the phone is left to MySQL (the `uq_farmers_phone` unique key); the resulting
error code 1062 is caught and answered with **409 Conflict**.

### 4. JSON in, JSON out

`app.use(express.json())` in `server.js` turns a request with
`Content-Type: application/json` into `req.body`; every answer uses `res.status(...).json(...)`.
A missing/malformed body is **400**, an unexpected failure (MySQL down, ...) is **500** from
the error-handling middleware — an API client never receives an HTML page.

### 5. Route order still matters

The order in `server.js` is now: `express.json()` → A14 HTML routes → **A15 `/api/farmers`
router** → JSON 404 for `/api/*` → `express.static()` → HTML 404 → error handler. The API is
mounted before the static folder so `/api/...` never gets an HTML answer, and the A14 routes
are untouched, so Assignment 14 still works exactly as documented above.

### What is deliberately NOT here — that is Assignment 16

No authentication, no pagination, no HTTPS, no frontend page calling the API yet — the
Assignment 7 form and the Assignment 11 form still store data only in the browser. Joining
the pages, the PHP screens and this API is the final integration (Assignment 16).

## Assignment 16 detail — Complete project integration

Assignment 16 joins the four parts that Assignments 1-15 had built separately, **without
rewriting any of them**. Two small changes were enough: one hub section in the home page, and
two static mounts in the existing Express server. No file of `frontend/css/`, `frontend/js/`,
`react-app/src/`, `php/` or `database/` was edited.

### What was already connected (verified, not changed)

| Connection | Where it lives |
|---|---|
| One stylesheet for the HTML pages **and** the PHP pages | `php/includes/header.php` links `../frontend/css/style.css` |
| PHP pages link back to the static site | the `<nav>` of `php/includes/header.php` (`../frontend/*.html`) |
| One database for Assignment 13 **and** Assignment 15 | `database/schema.sql` → `dairy_management.farmers` |
| MySQL passwords kept out of Git | root `.gitignore` (`php/db-crud/db-config.php`) + `node-backend/.gitignore` (`db-config.js`, `node_modules/`) |
| One script for Assignments 6, 7 and 11 | `frontend/js/main.js`, every part guarded by a `null` check |

### What was disconnected, and the fix

| Disconnected | Fix in Assignment 16 |
|---|---|
| The static site could not reach the React / PHP / Node modules | new `<section>` "Project Modules (Assignment 16 - Complete Integration)" at the end of `<main>` in `frontend/index.html` — four `<article>` blocks (one per module, each with the command that starts it and a live link) and one `<aside>` about the shared database and the two gitignored config files |
| The Express server only knew `node-backend/public/` | section 3 of `node-backend/server.js` now mounts `../frontend` at `/frontend` and `../react-app/dist` at `/react`, both **before** `express.static(PUBLIC_FOLDER)` so the route order of Assignment 14 is preserved |
| The Node home page did not mention the other modules | two list items added to the `Try these` list of `GET /` (`/frontend/index.html`, `/react/`) plus the existing `/api/farmers` link; the startup log gained an `Integration (Assignment 16)` block |

### The two mounts (node-backend/server.js, section 3)

```js
app.use('/frontend', express.static(path.join(__dirname, '..', 'frontend')));
app.use('/react',    express.static(path.join(__dirname, '..', 'react-app', 'dist')));
```

| URL | Served by | Assignments |
|---|---|---|
| `/frontend/index.html`, `/frontend/pages/*.html`, `/frontend/css/style.css`, `/frontend/js/main.js` | the `/frontend` mount | 1-7, 11 |
| `/react/` | the `/react` mount (build it once with `npm run build` in `react-app/`) | 8-10 |
| `/`, `/about`, `/index.html`, `/style.css`, `/milk-can.svg`, HTML 404 | unchanged Assignment 14 code | 14 |
| `/api/farmers`, `/api/farmers/:id`, JSON 404, JSON error handler | unchanged Assignment 15 code | 15 |
| `http://localhost:8000/php/...` | the PHP server — Node.js cannot execute `.php`, so PHP is **not** mounted, only linked from the hub section | 12, 13 |

Why the existing relative links keep working: a page reached at
`/frontend/pages/dashboard.html` links `../css/style.css`, which the browser resolves to
`/frontend/css/style.css` — inside the same mount. Nothing had to be rewritten.

### How to run the whole project

```
# 1. static site + Node server + REST API (one process)
cd node-backend
npm start                       -> http://localhost:3000/frontend/index.html
                                  http://localhost:3000/api/farmers

# 2. PHP (Assignment 12) and PHP + MySQL CRUD (Assignment 13) - from the project ROOT
php -S localhost:8000           -> http://localhost:8000/php/db-crud/

# 3. React dev server (or skip it and use the /react/ build above)
cd react-app
npm run dev                     -> http://localhost:5173/
```

Database (once): run `database/schema.sql`, then write the MySQL password into
`php/db-crud/db-config.php` **and** `node-backend/db-config.js` (both gitignored).

### What Assignment 16 deliberately did NOT do

- **The Assignment 7 form and the Assignment 11 entry form still store data in the browser
  only.** Pointing them at `/api/farmers` with `fetch()` (or at the PHP CRUD) needs a decision
  about where the data should live — that is the next step, not this one.
- `frontend/pages/milk.html` is still linked from the menus but does not exist.
- No new framework, no new dependency, no schema change, no authentication, no CORS.
- Nothing was committed or pushed.

### Checks run for Assignment 16

- `node --check` on `server.js`, `routes/farmers.js`, `db.js` — all pass.
- `npm run build` in `react-app/` — passes (18 modules, `dist/` regenerated).
- `git check-ignore` confirms `php/db-crud/db-config.php`, `node-backend/db-config.js`,
  `node_modules/` and `react-app/dist` are all ignored, so no password can be committed.
