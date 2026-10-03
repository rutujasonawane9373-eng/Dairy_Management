# Assignment Mapping - Dairy Management System

This document maps every college practical assignment to the file / feature that demonstrates
it in the project. Use it to show your teacher exactly where each concept is implemented.

Current project phase: Phase 6 - React (Assignment 8)

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
| 9 | React - props, state, hooks, events | `react-app/` (planned) | ⏳ Pending |
| 10 | Fetch API + JSON | `frontend/js/api.js` (planned) | ⏳ Pending |
| 11 | DOM manipulation + events | `frontend/js/main.js` (planned) | ⏳ Pending |
| 12 | PHP - forms, validation, strings, sessions | `php/auth/` (planned) | ⏳ Pending |
| 13 | PHP + MySQL - CRUD | `php/farmer/` (planned) | ⏳ Pending |
| 14 | Node.js + Express - server, routing, static files | `node-backend/server.js` (planned) | ⏳ Pending |
| 15 | REST API (Node.js + Express + DB) | `node-backend/routes/` (planned) | ⏳ Pending |
| 16 | Complete project integration | All modules together | ⏳ Pending |

## Project structure (as of Phase 5 — Assignments 6 and 7)

```
Dairy_Management/
├── AGENTS.md
├── frontend/
│   ├── index.html
│   ├── pages/
│   │   ├── about.html
│   │   ├── dashboard.html
│   │   ├── collection-centre.html
│   │   └── farmers.html       Assignment 7 (farmer registration form)
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js            Assignment 6 + Assignment 7
│   └── assets/
│       ├── cow.svg
│       ├── farm.svg
│       └── milk-can.svg
├── docs/
│   ├── assignment-mapping.md
│   └── viva-notes.md
└── later-phase folders are not created yet
```

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

## Assignments 8 and later — not implemented yet

- **Assignment 8 and onwards have not been started.** There is no `react-app/`, `php/`,
  `node-backend/` or `database/` folder, no React component, no `fetch()` / API call, no
  server and no SQL anywhere in the project.
- The form on `farmers.html` is checked and stored **only in the browser**: there is no
  `action` attribute and no PHP page behind it.
- The only JavaScript file is still `frontend/js/main.js`, now loaded by two pages
  (`dashboard.html` for Assignment 6 and `farmers.html` for Assignment 7).