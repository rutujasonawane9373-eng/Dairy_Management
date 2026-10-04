# Assignment Mapping - Dairy Management System

This document maps every college practical assignment to the file / feature that demonstrates
it in the project. Use it to show your teacher exactly where each concept is implemented.

Current project phase: Phase 9 - PHP + MySQL Database Connectivity and CRUD (Assignment 13)

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
| 14 | Node.js + Express - server, routing, static files | `node-backend/server.js` (planned) | ⏳ Pending |
| 15 | REST API (Node.js + Express + DB) | `node-backend/routes/` (planned) | ⏳ Pending |
| 16 | Complete project integration | All modules together | ⏳ Pending |

## Project structure (as of Phase 9 — Assignments 1-13)

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
└── docs/
    ├── assignment-mapping.md
    └── viva-notes.md
```

`node-backend/` is not created yet (Assignments 14 and 15).

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

## Assignments 14–16 — not implemented yet

- **Assignment 14 (Node.js + Express)** and **Assignment 15 (REST API)** have not been started.
  There is no `node-backend/` folder in the project, no `package.json` outside `react-app/`,
  and no API of any kind.
- **Assignment 16 (complete integration)** cannot begin until 14 and 15 exist.
- The Assignment 13 CRUD screens read and write the `dairy_management.farmers` table through
  PHP + MySQLi. The Assignment 7 form on `farmers.html` and the Assignment 11 entry form on
  `collection-centre.html` are still checked and stored **only in the browser** — neither has
  an `action` attribute pointing at `php/db-crud/`, so joining those two screens to the database
  is part of the final integration work.
- `frontend/js/main.js` is still the only JavaScript file of the static `frontend/` part; the
  React app is a separate Vite project that does not load it, and no PHP page loads it either.
