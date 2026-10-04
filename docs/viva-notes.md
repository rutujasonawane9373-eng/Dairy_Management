# Dairy Management System â€” Viva Notes

> Study and revision document for the Dairy Management System college project.
> Everything in this file is taken from the **actual current code** in this repository.
> File names, line numbers, selectors and class names are real â€” verify with the file path shown.
> This document will be updated after every future assignment.
> Last updated after **Assignment 11** (Phase 8 - DOM Manipulation and Event Handling, inside the `frontend/` pages). Assignments 12-16 are not built yet.

---

## 1. Project Overview

### 1.1 Purpose of the project

The Dairy Management System digitises the daily work of a dairy cooperative society. A
cooperative collects milk from farmers every morning and evening, tests the fat and SNF
(non-fat solids) of each sample, decides a rate per litre from the fat content, and pays the
farmer once a fortnight.

The project turns that paper process into a set of web screens:

- a home page that introduces the system and shows the day's overview,
- an About page that explains the process,
- a dashboard with the key statistics,
- a collection-centre working screen with tank status, today's collection log, shift summary
  and a live rate board.

The theme (colours, images, wording) is consistent across all pages â€” cream/milk background,
butter gold, dark chocolate brown. That is deliberate so the project looks like one application.

### 1.2 Current technology used

| Layer | Technology | Status |
|---|---|---|
| Page structure | HTML5 (semantic tags) | Used â€” Assignments 1 & 2 |
| Styling | CSS3 (Flexbox, Grid, Media Queries, positions) | Used â€” Assignments 3, 4, 5 |
| Behaviour | JavaScript (events, array functions, DOM) | Used â€” **Assignment 6** |
| Images | Hand-written inline SVG | Used |
| App framework | React (Vite) | **Used** â€” Assignments 8, 9 and 10 in `react-app/` |
| Server / database | PHP, MySQL, Node.js + Express | **Not used yet** â€” later phases |

There is **one** JavaScript file: `frontend/js/main.js`. It is loaded by three pages â€”
`frontend/pages/dashboard.html` (Assignment 6), `frontend/pages/farmers.html` (Assignment 7)
and `frontend/pages/collection-centre.html` (Assignment 11). There is **no inline JavaScript** anywhere in the project â€”
no `onclick="..."` or `onchange="..."` attributes exist in any HTML file, and no
`<script>` block is written inside a page. All behaviour lives in that one external file.

The pages are opened directly in a browser. There is no build step, no framework and no
`package.json`.

### 1.3 Current project structure (actual, verified)

```
Dairy_Management/
â”œâ”€â”€ AGENTS.md                       project rules + phase plan
â”œâ”€â”€ docs/
â”‚   â”œâ”€â”€ assignment-mapping.md       which file proves which assignment
â”‚   â””â”€â”€ viva-notes.md               THIS file
â””â”€â”€ frontend/
    â”œâ”€â”€ index.html                  Assignment 2 (semantic HTML) + Assignment 3
    â”œâ”€â”€ pages/
    â”‚   â”œâ”€â”€ about.html              Assignment 1 (basic HTML) + 3
    â”‚   â”œâ”€â”€ dashboard.html          Assignment 4 (Flexbox / Grid / Media Queries)
    â”‚   â”‚                           + Assignment 6 (loads js/main.js)
    â”‚   â”œâ”€â”€ collection-centre.html  Assignment 5 (CSS positions)
    â”‚                               + Assignment 11 (loads js/main.js)
    â”‚   â””â”€â”€ farmers.html            Assignment 7 (registration form + validation)
    â”‚                               + loads js/main.js
    â”œâ”€â”€ css/
    â”‚   â””â”€â”€ style.css               Assignments 3, 4, 5, 6, 7 and 11 â€” the ONLY stylesheet
    â”œâ”€â”€ js/
    â”‚   â””â”€â”€ main.js                 Assignments 6, 7 and 11 â€” the ONLY JavaScript file
    â””â”€â”€ assets/
        â”œâ”€â”€ cow.svg
        â”œâ”€â”€ farm.svg
        â””â”€â”€ milk-can.svg
```

Folders that do **not** exist yet and must not be mentioned as if they do (note: `react-app/` DOES exist â€” Assignments 8, 9 and 10):
`react-app/`, `php/`, `node-backend/`, `database/`.

### 1.4 How the frontend files are connected

**One stylesheet serves every page.** All five HTML files load `frontend/css/style.css`
with a `<link>` tag. The path differs because of where the HTML file sits:

| HTML file | `<link>` line | Path used | Why |
|---|---|---|---|
| `frontend/index.html` | 27 | `css/style.css` | file is already in `frontend/` |
| `frontend/pages/about.html` | 29 | `../css/style.css` | `..` goes up from `pages/` to `frontend/` |
| `frontend/pages/dashboard.html` | 31 | `../css/style.css` | same |
| `frontend/pages/collection-centre.html` | 38 | `../css/style.css` | same |
| `frontend/pages/farmers.html` | 41 | `../css/style.css` | same |

The same `../` idea applies to page links and images:

- `index.html` links with `pages/about.html` (it sits one level up),
- pages inside `pages/` link with `../index.html` and `../pages/about.html`,
- images come from `../assets/cow.svg` etc.

**Navigation between pages** (`<nav>` in each HTML file):

- `index.html` â†’ About, Dashboard, Collection Centre, plus **Farmers** (page exists since
  Assignment 7) and **Milk Collection** (still a placeholder),
- `about.html` â†’ Home, About (active), Dashboard, Collection Centre,
- `dashboard.html` â†’ Home, About, Dashboard (active), Collection Centre, plus Farmers and
  Milk Collection,
- `collection-centre.html` â†’ Home, About, Dashboard, Collection Centre (active),
- `farmers.html` â†’ Home, About, Dashboard, Collection Centre, **Farmers (active)**, Milk Collection.

Each page marks its own menu link with `class="active"` so `style.css` line 99
(`nav a.active`) can highlight it in gold.

**Assets** are only used in two places: the three images on `about.html` (lines 104â€“106) and
the milk can picture inside `.photo-frame` on `collection-centre.html` (line 216).

**JavaScript** is the third kind of connection. `frontend/pages/dashboard.html`, `frontend/pages/farmers.html` and
`frontend/pages/collection-centre.html` link `frontend/js/main.js` as the **last element before `</body>`**
(`dashboard.html` line 230, `farmers.html` line 250, `collection-centre.html` line 439):

```html
<script src="../js/main.js"></script>
```

Both files are inside `frontend/pages/`, so `../js/` goes up to `frontend/` and then into
`js/`. This is the same `..` rule used by the CSS and image paths â€” the browser resolves all of
them relative to the HTML file's own folder. The other three pages do not load the script at all,
so they behave exactly as before. Loading the script on a page that lacks some of its elements is
still safe, because every element it looks for is checked before use â€” see section A6.6 for the
Assignment 6 elements and `startFarmerForm()` (section A7.8) for the Assignment 7 form, which
returns immediately when `#farmer-form` is not on the page.

---

## 2. Assignment 1 â€” Basic HTML

**File: `frontend/pages/about.html`** â€” this is the file whose header comment (line 4) declares
`ASSIGNMENT 1 : Basic HTML demonstration`.

### 2.1 Page skeleton (lines 1, 20â€“31)

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Dairy | Dairy Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
```

- `<!DOCTYPE html>` (line 1) â€” tells the browser "use modern HTML5 standards". Without it,
  browsers switch to an old compatibility mode and some CSS behaves differently.
- `<html lang="en">` â€” the language of the page. Screen readers use it to choose a voice.
- `<meta charset="UTF-8">` â€” character encoding. Must be the first item in `<head>`.
- `<meta name="viewport" ...>` â€” makes the CSS pixel width match the real screen, which is
  what allows the media queries in Assignment 4/5 to work on a phone.
- `<title>` â€” the text shown on the browser tab. The pattern used on every page is
  `Page Name | Dairy Management System`.

### 2.2 Headings (lines 47, 64, 80, 95)

```html
<h1>About Dairy</h1>
<h2>How Milk Collection Works</h2>
<h2>Services for Farmers</h2>
<h2>Our Dairy in Pictures</h2>
```

Headings create the document outline. `<h1>` is the page title and appears once; `<h2>` starts
each new topic. Sizes are set by the browser by default, and `style.css` restyles them where
needed (`header h1` line 43 sets `font-size: 34px`).

> **Know this honestly:** the comment above line 79 in `about.html` says "h3 is a sub-section
> heading", but the tag on line 80 is `<h2>`, and the file header comment (line 7) claims
> "Headings (h1, h2, h3)". There is **no `<h3>` on this page**. If the examiner asks, say the
> page uses `h1` and `h2` only.

### 2.3 Paragraphs (lines 50, 57, 66, 82, 109)

```html
<p>
    Dairy farming is an important part of rural India's economy. Small farmers
    depend on daily milk collection for a steady income. ...
</p>
```

`<p>` is a block element: it starts on a new line and the browser adds space above and below
it. The source code wraps one sentence across several lines for readability â€” HTML collapses
those line breaks into single spaces, so the browser shows one continuous paragraph.

### 2.4 Ordered list â€” `<ol>` (lines 70â€“77)

```html
<ol>
    <li>Farmer brings the milk to the collection centre in a can.</li>
    <li>The milk is weighed and the quantity (litres) is recorded.</li>
    <li>The milk sample is checked for fat and SNF percentages.</li>
    <li>The rate (Rs per litre) is decided based on fat content.</li>
    <li>Amount = Milk Quantity x Rate is calculated.</li>
    <li>Every fortnight, the total payment is given to the farmer.</li>
</ol>
```

`<ol>` = **ordered list**. The browser draws the numbers `1. 2. 3.` itself â€” the numbers are
never typed in the HTML. Use `<ol>` when the **order matters**, which is exactly the case here:
these are the six steps of the milk collection process.

### 2.5 Unordered list â€” `<ul>` (lines 86â€“92)

```html
<ul>
    <li>Daily milk pickup at the collection centre</li>
    <li>Fat and SNF quality testing for every collection</li>
    <li>Fortnightly payment based on fat and quantity</li>
    <li>Free cattle feed advisory and veterinary support</li>
    <li>Monthly milk production report for every farmer</li>
</ul>
```

`<ul>` = **unordered list**, drawn with bullets. These five farmer services have no sequence,
so a bulleted list is the correct choice. **That single difference â€” order vs no order â€” is the
whole reason `<ol>` and `<ul>` both exist**, and it is the classic viva question.

### 2.6 Images (lines 104â€“106)

```html
<img src="../assets/cow.svg" alt="Dairy cow illustration" width="160" height="145"
     style="border-radius: 12px; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);">
<img src="../assets/farm.svg" alt="Dairy farm illustration" width="160" height="145" ...>
<img src="../assets/milk-can.svg" alt="Milk can illustration" width="130" height="160" ...>
```

Attributes used and why:

| Attribute | Meaning | In my project |
|---|---|---|
| `src` | path to the image file | `../assets/cow.svg` â€” `..` goes up from `pages/` to `frontend/`, then into `assets/` |
| `alt` | text shown if the image fails, and read aloud by screen readers | all three images have meaningful text |
| `width` | display width in pixels | `160` for cow and farm, `130` for the milk can |
| `height` | display height in pixels | `145`, `145`, `160` |
| `style` | inline CSS (Assignment 3) | rounded corners + soft shadow on each image |

`<img>` is a **void element** â€” it has no closing `</img>` tag and cannot hold children.

The images are **SVG** files written by hand (shapes such as `<ellipse>`, `<circle>`, `<path>`,
`<rect>` inside an `<svg>` tag). SVG stays sharp at any size, which is also why
`collection-centre.html` can stretch `milk-can.svg` to `width: 100%; height: 100%` inside the
picture frame without it blurring.

### 2.7 Links (lines 38â€“41, 116)

```html
<li><a href="../index.html">Home</a></li>
<li><a href="../pages/about.html" class="active">About</a></li>
...
<p><a class="btn" href="../index.html">Back to Home</a></p>
```

`<a href="...">` makes the text clickable. `href` holds the destination. Two classes appear:
`class="active"` (highlight the current page, styled by `style.css` line 99) and `class="btn"`
(turn the link into a button, styled by `style.css` line 155).

### 2.8 Entities used in the project

| Entity | Shows as | Used in |
|---|---|---|
| `&amp;` | & | `index.html:60` ("fat & SNF") |
| `&copy;` | Â© | footer of every page |
| `&nbsp;` | space that never breaks | footers, `index.html` collection records, `.centre-note` |
| `&bull;` | â€¢ | `collection-centre.html` tank cards |
| `&deg;` | Â° | `collection-centre.html` ("4Â°C") |
| `&rarr;` | â†’ | rate board on `collection-centre.html` |
| `&#8377;` | â‚¹ (Rupee) | `dashboard.html:84`, `collection-centre.html:108` |

### 2.9 How this page works

Open `frontend/pages/about.html` in Chrome â†’ the browser reads the `<head>` â†’ downloads
`../css/style.css` â†’ builds the page from the body tags â†’ applies the stylesheet â†’ the page
appears with the cream background, gold menu bar and white content. The menu works because each
link is a normal relative path back into the project.

---

## 3. Assignment 2 â€” Semantic HTML

**File: `frontend/index.html`** â€” its header comment (line 4) declares
`ASSIGNMENT 2 : Semantic HTML tags demonstration`.

Semantic tags describe the **meaning** of a piece of content, not how it should look. That
meaning helps screen-reader users, search engines and anyone reading the source.

### 3.1 `<header>` â€” lines 58â€“61 (index.html)

```html
<header>
    <h1>Dairy Management System</h1>
    <p>Your daily partner for milk collection, fat &amp; SNF tracking and farmer payments.</p>
</header>
```

- **Meaning:** the introductory banner of the page â€” title and tagline.
- **Why:** it becomes the `banner` landmark, so assistive technology can jump past it, and it
  groups the site name with its description.
- **Where:** `index.html:58`, `dashboard.html:36`, `collection-centre.html:45`.
  **Not on `about.html`.**

### 3.2 `<nav>` â€” lines 68â€“77

```html
<nav>
    <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="pages/about.html">About</a></li>
        <li><a href="pages/dashboard.html">Dashboard</a></li>
        <li><a href="pages/collection-centre.html">Collection Centre</a></li>
        <li><a href="pages/farmers.html">Farmers</a></li>
        <li><a href="pages/milk.html">Milk Collection</a></li>
    </ul>
</nav>
```

- **Meaning:** the block of main navigation links.
- **Why:** it becomes the `navigation` landmark; a keyboard or screen-reader user can jump
  straight to the menu instead of reading the whole page.
- **Where:** all four pages. `index.html` uses `pages/about.html`; the pages inside `pages/`
  use `../index.html`.
- **Honest note:** `pages/farmers.html` and `pages/milk.html` **do not exist yet**, so those two
  links currently give a "file not found" error. They are placeholders for future phases.

### 3.3 `<main>` â€” lines 84â€“190

```html
<main>
    <section>...</section>
    <section>...</section>
    <section>...</section>
</main>
```

- **Meaning:** the main, unique content of the page.
- **Why:** there must be **exactly one `<main>` per page**. It enables the standard
  "skip to main content" accessibility shortcut, and `style.css` line 105 uses it to centre and
  limit the page width (`max-width: 1100px; margin: 20px auto;`).
- **Where:** `index.html`, `dashboard.html`, `collection-centre.html`. **Not on `about.html`.**

### 3.4 `<section>` â€” lines 91, 120, 153

Three sections on the home page: the welcome block, "Today's Overview", and
"Latest Milk Collections".

- **Meaning:** a thematic group of related content, normally with its own heading.
- **Why:** it breaks a long page into readable chunks instead of one wall of text.
- **Where:** every page. `collection-centre.html` has four sections: Centre Status, Today's
  Collection Log, Shift Summary, Chilling Unit.
- **Styled by:** `style.css` lines 112 (`section h2`) and 120 (`section`) â€” each section becomes
  a white rounded card with a soft shadow.

### 3.5 `<article>` â€” lines 125, 130, 135, 142, 157, 163, 169

```html
<article>
    <h3>Total Farmers</h3>
    <p>125 registered milk producers in the village area.</p>
</article>
```

- **Meaning:** a **self-contained piece of content** that would still make sense if it were
  quoted or shared on its own. The comment at `index.html` lines 123â€“124 states the reasoning
  directly: each stat card is independent, so it is an `<article>`.
- **Why:** a single farmer's collection record (lines 157â€“161) is also independent â€” that is
  the correct use, not a `<section>`.
- **Where:** stat cards and collection records on `index.html`; the four `.stat-card` articles
  on `dashboard.html`; the four `.tank-card` articles on `collection-centre.html`.
- **Styled by:** `style.css` line 130 â€” cream card with a 6px gold `border-left` accent.

### 3.6 `<aside>` â€” lines 180â€“187

```html
<aside>
    <h3>Notice Board</h3>
    <ul>
        <li>Milk rate for today: 42 Rs/litre (fat above 3.5%).</li>
        <li>Payment distribution for last week on Friday 26 September.</li>
        <li>New farmer registration opens every Monday at 9:00 AM.</li>
    </ul>
</aside>
```

- **Meaning:** content related to the surroundings but **not** the main focus â€” notices, tips,
  reminders, advertisements.
- **Why:** it becomes the `complementary` landmark, so it is skipped when reading only the
  main content.
- **Where:** `index.html:180` (inside the "Latest Milk Collections" section, so it complements
  that section), `dashboard.html:129` and `collection-centre.html:222` (directly inside `<main>`,
  so it complements the whole page). Both placements are valid.
- **Styled by:** the **internal** `<style>` block inside `index.html` lines 38â€“49, which gives it
  a cream background and a `2px dashed` gold border. **There is no `aside` rule in `style.css`**,
  so the asides on the dashboard and collection-centre pages currently look unstyled. Good
  viva answer if asked about it.

### 3.7 `<footer>` â€” lines 196â€“199

```html
<footer>
    <p>Contact: Dairy Cooperative Society Office, Main Road, Village &nbsp;|&nbsp; Phone: 98765 43210</p>
    <p>&copy; 2026 Dairy Management System - College Web Development Project</p>
</footer>
```

- **Meaning:** closing information â€” contact details and copyright.
- **Why:** the `contentinfo` landmark; contact info belongs at the end, not repeated in the
  middle of the page.
- **Where:** `index.html`, `dashboard.html`, `collection-centre.html`. **Not on `about.html`.**
- **Special case:** on `collection-centre.html` the footer gets extra bottom padding
  (`style.css` line 531) so the fixed rate board never covers it.

### 3.8 Other tags actually used in the project

| Tag | Example (file:line) | Why it is there |
|---|---|---|
| `<span>` | `collection-centre.html:82` `<span class="status-badge">FULL</span>` | An inline container. CSS turns it into a positioned pill badge; text alone cannot be positioned usefully. |
| `<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>` | `collection-centre.html:133â€“165` | Real tabular data â€” Farmer / Quantity / Fat % / SNF % / Rate / Status. `<thead>` is required for the sticky header row in Assignment 5. |
| `<div>` | `dashboard.html:68` `<div class="stats-grid">` | A plain container used only to give CSS a box to lay out. Used when there is no semantic meaning. |
| `<code>` | `index.html:105` | Marks `docs/assignment-mapping.md` as code, not ordinary text. |
| `<meta name="description">` | `index.html:19`, `dashboard.html:26`, `collection-centre.html:33` | Describes the page for search engines. |
| `class="active"` | `about.html:39`, `dashboard.html:48`, `collection-centre.html:58` | Marks the link of the page you are on. |
| `class="page-centre"` | `collection-centre.html:43` on `<body>` | A body-level hook so one extra CSS rule applies to that page only. |

---

## 4. Assignment 3 â€” CSS

**Only stylesheet: `frontend/css/style.css` (584 lines).** It is divided into labelled blocks:
base styling (lines 24â€“199), the Assignment 4 block (201â€“321) and the Assignment 5 block
(323â€“584). The block comments are a ready-made revision index.

### 4.1 The three ways CSS is attached (the core of this assignment)

**(a) External CSS â€” one shared file.**
`index.html` line 27 and the other three pages:

```html
<link rel="stylesheet" href="css/style.css">
```

`rel="stylesheet"` means "this linked resource is a stylesheet". Because it is external, editing
`style.css` restyles **every** page at once with no HTML change.

**(b) Internal CSS â€” inside one page only.**
`index.html` lines 36â€“50:

```html
<style>
    aside {
        background-color: #fff6dd;
        border: 2px dashed #d9a441;
        border-radius: 10px;
        padding: 15px 20px;
        margin-top: 20px;
    }
    aside ul li {
        margin-bottom: 8px;
    }
</style>
```

This applies to `index.html` **only**, because it sits in that page's `<head>`. The other pages
have no internal `<style>` block, which is exactly why their asides look different.

**(c) Inline CSS â€” on one element only.**

```html
<!-- index.html:95 -->
<h2 style="color: #7a4a21;">Welcome to the Dairy Management System</h2>

<!-- index.html:142 -->
<article style="background-color: #fde8d7; border: 2px solid #d98c5f;">

<!-- about.html:104 -->
<img src="../assets/cow.svg" ... style="border-radius: 12px; box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);">
```

**Priority order (also written in the comment at `style.css` lines 12â€“15):**

```
External  ->  Internal  ->  Inline      (lowest  ->  highest)
```

### 4.2 Selectors actually used in `style.css`

| Kind | Examples (line numbers) | What it targets |
|---|---|---|
| Element / type | `body` (25), `header` (35), `nav` (59), `main` (105), `section` (120), `article` (130), `footer` (189), `img` (181), `a` (171) | every tag with that name |
| Descendant | `header h1` (43), `nav ul` (68), `nav li` (78), `nav a` (83), `section h2` (112), `article h3` (140), `.flex-item h3` (275), `.log-window th` (444), `.rate-ticker h3` (517), `body.page-centre footer` (531) | a tag **inside** another tag |
| Class | `.btn` (155), `.stats-grid` (220), `.stat-card` (228), `.flex-row` (256), `.flex-item` (266), `.tank-card` (368), `.status-badge` (376), `.tank-gauge` (404), `.tank-fill` (413), `.gauge-92` (421), `.log-window` (429), `.photo-frame` (465), `.photo-caption` (486), `.rate-ticker` (502), `.centre-note` (354) | any element carrying that class |
| Two classes | `.status-badge.ok` (392), `.status-badge.info` (397) | badge with an extra state class |
| Class + class | `.stats-grid .stat-card` (228) | a stat card that is inside the grid |
| Universal | `*` (211) | every element in the project |
| Two selectors at once | `.log-window th, .log-window td` (444â€“445) | both header and data cells |
| By id | **none â€” no `id` is used anywhere in the project** | â€” |

### 4.3 Important rules explained line by line

**`body` (25â€“31)** â€” defaults for the whole page:

```css
body {
    margin: 0;                      /* remove Chrome's 8px white edge */
    padding: 0;
    background-color: #fdf6ec;      /* soft cream "milk" background */
    font-family: Arial, Helvetica, sans-serif;
    color: #3f2f1e;                 /* dark brown text */
}
```

- `font-family` with three names is a **fallback stack**: use Arial, else Helvetica, else
  whatever sans-serif the system has.
- Because these are set on `body`, and most properties are inherited by children, every other
  element inherits this font and text colour automatically.

**`header` (35â€“40)**:

```css
header {
    background-color: #e8b84b;          /* warm "butter" gold */
    text-align: center;
    padding: 25px 10px;                 /* 2-value: top/bottom, then left/right */
    border-bottom: 4px solid #c98a2d;   /* only the bottom edge */
}
```

- `padding: 25px 10px` is shorthand â€” **space inside** the border. The gold background extends
  into the padding, which is why the text is not glued to the edge.
- `border-bottom` = width `4px`, style `solid`, colour `#c98a2d`.

**`header h1` (43â€“47) and `header p` (50â€“54)** â€” `margin: 0` removes the browser's default
heading gap; `font-size: 34px` overrides the default size.

**`main` (105â€“109)** â€” the centring trick used on three pages:

```css
main {
    max-width: 1100px;   /* never wider than this */
    margin: 20px auto;   /* left/right auto = centre */
    padding: 0 15px;
}
```

`auto` on the left and right margins centres a block that has a `max-width`. This is the
standard way to centre a page column.

**`section` (120â€“127)** â€” every section becomes a white card:

```css
section {
    background-color: #ffffff;
    border: 1px solid #e0cda8;
    border-radius: 10px;                            /* rounded corners */
    padding: 20px 25px;
    margin-bottom: 20px;                            /* space OUTSIDE */
    box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.08);   /* soft lift */
}
```

**`article` (130â€“137)** â€” the inner stat card:

```css
article {
    background-color: #fdf8ee;
    border: 1px solid #ddc9a5;
    border-left: 6px solid #e8b84b;   /* thick gold stripe on the left */
    border-radius: 8px;
    padding: 12px 18px;
    margin: 12px 0;
}
```

`border-left` comes **after** `border` in the file, so it overrides only the left side. This
"gold accent stripe" look is repeated in `.flex-item`, `.tank-card`, `.stats-grid .stat-card`
and `.centre-note`.

**`.btn` (155â€“168)** â€” turns a link into a button:

```css
.btn {
    display: inline-block;        /* needed so padding works on a link */
    background-color: #6b4226;
    color: #ffffff;
    padding: 10px 22px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}
```

`display: inline-block` matters: a plain `<a>` is inline, and `padding` / `height` do not
behave properly on inline elements. Used on `index.html:111`, `about.html:116` and
`collection-centre.html:232`.

**Hover effects â€” three real ones:**

```css
nav a:hover   { background-color: #e8b84b; color: #4b2e0e; }   /* line 92  */
.btn:hover    { background-color: #c98a2d; }                   /* line 166 */
a:hover       { color: #6b4226; }                              /* line 175 */
```

`:hover` is a **pseudo-class**: it applies only while the mouse pointer is over the element.
`nav a:hover` gives a gold pill behind the menu link; `.btn:hover` lightens the button.

**`img` (181â€“186)** â€” a gold frame on every image:

```css
img {
    border: 3px solid #e8b84b;
    border-radius: 10px;
    background-color: #ffffff;
    margin: 5px;
}
```

`.photo-frame img` (478â€“484) later resets `border: 0; border-radius: 0; margin: 0` so the
picture inside the frame has no frame of its own.

**`footer` (189â€“199)** â€” dark brown `#4b2e0e` with cream text `#f5e6c8`.

**`* { box-sizing: border-box; }` (211â€“213)** â€” by default CSS treats `width` as the **content
width only**, so `width: 260px` plus `padding: 20px` becomes a 300px box and grid/flex items
overflow their space. `border-box` makes `width` include padding and border. This single line
stops the whole layout from overflowing.

### 4.4 Colour palette used in the project

| Colour | Where |
|---|---|
| `#fdf6ec` | page background (milk cream) |
| `#e8b84b`, `#c98a2d` | gold accents (header, borders, buttons on hover) |
| `#6b4226`, `#4b2e0e`, `#5c3a12` | dark browns (nav bar, footer, title) |
| `#3f2f1e`, `#5b4a36`, `#7a6428` | body and secondary text |
| `#ffffff`, `#fdf8ee`, `#fff6dd`, `#fffdf8`, `#f4e9d4`, `#f5e6c8` | card and panel backgrounds |
| `#b03a2e` red, `#1e7e34` green, `#1f6fa8` blue | status badge states |
| `#a05f14` | normal link colour |

### 4.5 Typography used

- `font-family: Arial, Helvetica, sans-serif;` â€” one family for the entire project (`body`).
- Sizes: `34px` (site title), `42px` â†’ `32px` on mobile (`.stat-number`), `16px` (header tagline,
  `.rate-ticker h3`), `14px` (`.centre-note`, `.rate-ticker`, `.stat-label` uses `14px`),
  `13px` (`.log-window` cells, `.photo-caption`), `11px` (`.status-badge`).
- `font-weight: bold` on nav links, `.btn`, `.stat-number`, `.status-badge`.
- `text-align: center` on `header`, `footer` and `.stats-grid .stat-card`.
- `letter-spacing: 0.5px` on `.status-badge` (376).

---

## 5. Assignment 4 â€” Responsive Design

**Demo page: `frontend/pages/dashboard.html`.** All rules live in `style.css` lines 201â€“321.
The same rules are reused on `collection-centre.html`.

Responsive design means **one HTML page** changes shape to suit the screen. No separate mobile
page is needed.

### 5.1 Flexbox

**Where it is used:**
1. the navigation menu (`nav ul`, lines 68â€“80) â€” on all four pages;
2. the summary strips (`.flex-row` / `.flex-item`, lines 256â€“283) â€” the three shift blocks on
   the dashboard and on the collection-centre page.

**Code (68â€“80):**

```css
nav ul {
    display: flex;            /* turn the <ul> into a flex container */
    flex-wrap: wrap;          /* drop to a new line instead of squashing */
    justify-content: center;  /* centre along the main (horizontal) axis */
    list-style: none;
    margin: 0;
    padding: 0;
}
nav li {
    margin: 4px 15px;         /* spacing, because no gap is used here */
}
```

HTML it works on (`dashboard.html` lines 44â€“53):

```html
<nav>
    <ul>
        <li><a href="../index.html">Home</a></li>
        <li><a href="../pages/about.html">About</a></li>
        <li><a href="../pages/dashboard.html" class="active">Dashboard</a></li>
        <li><a href="../pages/collection-centre.html">Collection Centre</a></li>
    </ul>
</nav>
```

**What it does visually:** the `<ul>` is the **flex container** and each `<li>` is a **flex
item**, so the menu links line up in one horizontal row instead of stacking vertically with
bullets. `justify-content: center` centres that row on the dark brown bar.

**Code (256â€“273):**

```css
.flex-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;            /* space between the items */
    margin-top: 15px;
}
.flex-item {
    flex: 1 1 200px;      /* grow | shrink | base width */
    background-color: #fdf8ee;
    border: 1px solid #ddc9a5;
    border-left: 6px solid #c98a2d;
    border-radius: 8px;
    padding: 16px 18px;
}
```

HTML (`dashboard.html` lines 105â€“125):

```html
<div class="flex-row">
    <div class="flex-item">
        <h3>Morning Shift</h3>
        <p>650 litres from 62 farmers</p>
        <p>Average fat 4.1% | SNF 8.7%</p>
    </div>
    <div class="flex-item">...</div>
    <div class="flex-item">...</div>
</div>
```

**`flex: 1 1 200px`** means:
- `flex-grow: 1` â€” every item may grow by the same amount,
- `flex-shrink: 1` â€” items may shrink if the row gets tight,
- `flex-basis: 200px` â€” each item starts by asking for 200px.

Result: the three blocks always end up **equal width with no width set in the HTML**, and all
the same height, because `align-items` is not declared and therefore defaults to `stretch`.

**Why Flexbox was used here:** the menu and the shift blocks are **one-dimensional** â€” a single
row of similar items. That is exactly what Flexbox is for.

### 5.2 CSS Grid

**Where it is used:** `.stats-grid` â€” the four stat cards on `dashboard.html` (lines 68â€“94) and
the four tank/equipment cards on `collection-centre.html` (lines 79â€“113).

**Code (220â€“236):**

```css
.stats-grid {
    display: grid;                          /* activate CSS Grid */
    grid-template-columns: repeat(4, 1fr);  /* four equal columns */
    gap: 20px;
    margin-top: 15px;
}
.stats-grid .stat-card {
    margin: 0;
    background-color: #fdf8ee;
    border: 1px solid #ddc9a5;
    border-left: 6px solid #e8b84b;
    border-radius: 8px;
    padding: 18px;
    text-align: center;
}
```

HTML (`dashboard.html` lines 68â€“94):

```html
<div class="stats-grid">
    <article class="stat-card">
        <h3>Total Farmers</h3>
        <p class="stat-number">125</p>
        <p class="stat-label">registered milk producers</p>
    </article>
    <article class="stat-card">
        <h3>Today's Milk Collection</h3>
        <p class="stat-number">1,050 L</p>
        <p class="stat-label">from 98 farmers</p>
    </article>
    <article class="stat-card">...Pending Payments...</article>
    <article class="stat-card">...Average Fat...</article>
</div>
```

Supporting classes (239â€“251):

```css
.stat-number { font-size: 42px; font-weight: bold; color: #6b4226; margin: 6px 0; }
.stat-label  { margin: 0; font-size: 14px; color: #7a6428; }
```

- `display: grid` makes `.stats-grid` a **grid container**; the four `<article class="stat-card">`
  are **grid items**.
- `grid-template-columns: repeat(4, 1fr)` means **four equal columns**. `1fr` = one fraction of
  the available width; `repeat(4, ...)` is shorthand for `1fr 1fr 1fr 1fr`.
- `gap: 20px` puts 20px between rows **and** between columns.
- **Auto-placement:** the browser fills cells left to right, top to bottom automatically. No
  card is ever positioned by hand â€” that is why adding a fifth card needs no CSS change.
- `.stat-number` (42px bold) makes the big value, `.stat-label` (14px) the small description
  under it.

**Why Grid was used here:** the stat cards form a **two-dimensional** block (rows *and*
columns), and the number of columns has to change with the screen. That is Grid's strength.

### 5.3 Media queries â€” all four in the project

| # | `style.css` lines | Condition | What it changes |
|---|---|---|---|
| 1 | 292â€“297 | `@media (max-width: 900px)` | `.stats-grid` â†’ `repeat(2, 1fr)` |
| 2 | 300â€“321 | `@media (max-width: 600px)` | `.stats-grid` â†’ `1fr`; `.flex-row` â†’ `flex-direction: column`; `nav ul` â†’ `flex-direction: column` + `gap: 6px`; `.stat-number` â†’ `32px` |
| 3 | 538â€“546 | `@media (max-width: 900px)` | `.photo-frame` â†’ `width: 100%`; `.tank-card` â†’ `padding-top: 30px` |
| 4 | 549â€“583 | `@media (max-width: 600px)` | `nav` â†’ `position: static`; `.status-badge` smaller; `.tank-card` â†’ `padding-top: 28px`; `.rate-ticker` â†’ full-width bottom strip; `.log-window` â†’ `max-height: 200px`; footer â†’ `padding-bottom: 150px` |

**Code (292â€“321):**

```css
/* TABLET layout: screen width is 900px or less */
@media (max-width: 900px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);   /* 4 cards -> 2 per row */
    }
}

/* MOBILE layout: screen width is 600px or less */
@media (max-width: 600px) {
    .stats-grid { grid-template-columns: 1fr; }        /* stack 1 per row */
    .flex-row  { flex-direction: column; }             /* stack the blocks */
    nav ul      { flex-direction: column; gap: 6px; }  /* stack the menu */
    .stat-number{ font-size: 32px; }
}
```

**What `max-width` means:** "apply these rules **only if** the screen is *at most* this wide."
It reacts to the **window size**, not to a device name â€” so dragging the Chrome window is a
valid test. These rules come **after** the base rules in the file, so for equal specificity the
later rule wins.

**How the layout changes on my project (test it by dragging the window):**

1. **Desktop (~1400px)** â€” no media query applies. `dashboard.html` shows **4 stat cards in one
   row**, **3 summary blocks side by side**, and a horizontal menu.
2. **Tablet (~850px)** â€” query 1 fires: the stat cards become **2 Ã— 2**. The three flex blocks
   still fit in a row. On `collection-centre.html` the picture frame grows from a fixed 260px to
   the full available width.
3. **Mobile (~500px)** â€” queries 2 and 4 fire: the stat cards become **one per row**, the three
   summary blocks stack **vertically**, the menu links stack one per line, the big numbers shrink
   from 42px to 32px, the sticky menu stops sticking, the fixed rate board becomes a full-width
   bottom strip, and the collection log window shrinks from 230px to 200px tall.

---

## 6. Assignment 5 â€” CSS Positions

**Demo page: `frontend/pages/collection-centre.html`.** All rules live in `style.css` lines
323â€“584, under the comment header `ASSIGNMENT 5 : CSS POSITIONS AND OTHER CSS PROPERTIES`.

This page is a real working screen (tank status, collection log, shift summary, equipment,
rate board) â€” the positioning is used as a user would use it, not as a separate demo box.

### 6.1 The five position values used in this project

| Value | Selector (line in `style.css`) | Where it appears |
|---|---|---|
| `static` | `.centre-note` (355) | the three shift-information strips |
| `relative` | `.tank-card` (369), `.photo-frame` (466) | the four status cards, the picture frame |
| `absolute` | `.status-badge` (377), `.photo-caption` (487) | FULL / OK / TESTING / OPEN pills, the picture caption |
| `fixed` | `.rate-ticker` (503) | the "Today's Milk Rate" board |
| `sticky` | `nav` (345), `.log-window thead th` (455) | the menu on every page, the log's column titles |

### 6.2 `position: static` â€” `.centre-note` (lines 354â€“363)

```css
.centre-note {
    position: static;
    margin: 12px 0 4px 0;
    padding: 10px 14px;
    border-left: 5px solid #c98a2d;
    border-radius: 8px;
    background-color: #fff6dd;
    color: #5b4a36;
    font-size: 14px;
}
```

Used at `collection-centre.html` lines 74, 127 and 177:

```html
<p class="centre-note">
    Duty supervisor: Ramesh Patil &nbsp;|&nbsp; Morning shift &nbsp;|&nbsp;
    Board updated 21 September 2026, 8:30 AM
</p>
```

- **What it does:** the strip sits in the normal page flow, exactly where the HTML puts it, and
  never moves. `static` is the **default value** of `position` â€” it is written here to
  demonstrate the concept.
- **Why:** to show the default and to make the five-position comparison complete.
- **If I removed `position: static`:** **nothing changes at all.** There is no positioned
  parent, so it is already its own reference. **That is the correct honest answer in the viva.**
- **Positioned relative to:** nothing. A static element ignores `top`, `right`, `bottom`, `left`.

### 6.3 `position: relative` â€” `.tank-card` (368â€“373) and `.photo-frame` (465â€“476)

```css
.tank-card {
    position: relative;
    overflow: hidden;      /* keep the badge and text inside */
    padding-top: 26px;     /* room for the badge */
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.14);
}
```

```html
<article class="stat-card tank-card">           <!-- collection-centre.html:81 -->
    <span class="status-badge">FULL</span>
    <h3>Chilling Tank A</h3>
    <p class="stat-number">920 L</p>
    <p class="stat-label">92% of capacity &bull; holding at 4&deg;C</p>
    <div class="tank-gauge"><div class="tank-fill gauge-92"></div></div>
</article>
```

- **What it does:** the card stays in the normal flow (it still pushes the next card down), but
  it becomes the **containing block** for any `position: absolute` element inside it.
- **Why:** so the badge stays pinned to the card's own top-right corner even when the card
  changes size at each breakpoint.
- **If removed:** the badge would lose its positioned ancestor and jump to the next positioned
  ancestor (ultimately the page), so all four badges would pile up in the top-right of the page
  instead of sitting on their cards.
- **Positioned relative to:** its normal place in the document flow â€” `relative` does **not**
  remove the element from the layout.

```css
.photo-frame {
    position: relative;
    width: 260px;
    max-width: 100%;
    height: 220px;
    margin: 16px 0;
    border: 2px solid #c98a2d;
    border-radius: 12px;
    overflow: hidden;      /* clip the image to the frame */
    background-color: #ffffff;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.18);
}
```

```html
<div class="photo-frame">                       <!-- collection-centre.html:215 -->
    <img src="../assets/milk-can.svg" alt="Milk can ready for weighing at the collection centre">
    <p class="photo-caption">Milk can - washed, weighed and ready for the morning shift.</p>
</div>
```

- A fixed 260 Ã— 220 frame. `max-width: 100%` stops it overflowing a narrow screen.
- `overflow: hidden` clips the SVG to the frame so the rounded corners look clean.
- `position: relative` is what anchors the caption.

### 6.4 `position: absolute` â€” `.status-badge` (376â€“389) and `.photo-caption` (486â€“497)

```css
.status-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 3;
    padding: 3px 10px;
    border-radius: 999px;      /* full round "pill" */
    background-color: #b03a2e;
    color: #ffffff;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}
.status-badge.ok   { background-color: #1e7e34; }   /* green  */
.status-badge.info { background-color: #1f6fa8; }   /* blue   */
```

- `border-radius: 999px` on a small box produces a full **pill**.
- Four badges in use: `FULL` (red, line 82), `OK` (green, line 90), `TESTING` (blue, line 98),
  `OPEN` (green, line 106).
- **Positioned relative to:** the nearest ancestor with a `position` other than `static`, which
  is `.tank-card`.
- **If `position: absolute` were removed:** the badge would return to the normal flow and become
  the first inline box of the card, sitting before the `<h3>` and pushing the heading down
  instead of floating in the corner.
- `z-index: 3` keeps the badge painted above the card's own text.

```css.photo-caption {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    margin: 0;                        /* flush to the frame edges */
    padding: 8px 12px;
    background-color: rgba(75, 46, 14, 0.7);   /* see-through brown */
    color: #f5e6c8;
    font-size: 13px;
    border-top: 2px solid #e8b84b;
}
```

- `left: 0; bottom: 0` puts it at the bottom-left corner of `.photo-frame`; `width: 100%` makes
  it span the frame's full width â€” percentages on an absolutely positioned element resolve
  against the containing block, which is the relative parent.
- `rgba(75, 46, 14, 0.7)` = dark brown at **70 % transparency**, so the picture shows faintly
  through the caption.
- **If removed:** the caption would no longer overlay the picture; it would sit in the flow
  after the image inside a fixed-height frame and be clipped away by `overflow: hidden`.

### 6.5 `position: fixed` â€” `.rate-ticker` (502â€“515)

```css
.rate-ticker {
    position: fixed;
    left: 20px;
    bottom: 20px;
    z-index: 100;                 /* above every other element */
    width: 290px;
    padding: 12px 16px;
    border: 3px solid #6b4226;
    border-radius: 12px;
    background-color: #fff6dd;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    font-size: 14px;
}
```

The HTML is placed **after `</footer>`**, as the last child of `<body>`
(`collection-centre.html` lines 244â€“249):

```html
<div class="rate-ticker">
    <h3>Today's Milk Rate</h3>
    <p>Fat 3.5% and above &rarr; 42 Rs / litre</p>
    <p>Fat below 3.5% &rarr; 40 Rs / litre</p>
    <p>Last updated: 21 September 2026, 8:30 AM</p>
</div>
```

- **What it does:** the board is removed from the flow and pinned to the **viewport**. It stays
  in the bottom-left corner of the *screen* while the long collection log scrolls.
- **Why:** a rate board that a collection-centre operator must be able to read at all times.
  Losing it while scrolling would lose information.
- **Positioned relative to:** the **browser window (viewport)** â€” not any ancestor. No
  `position: relative` exists anywhere in its parent chain.
- **If removed:** the board would scroll away at the bottom of the page like any other block,
  and `left` / `bottom` would be ignored.
- **The scroll problem and its real fix:**

```css
body.page-centre footer {
    padding-bottom: 170px;      /* style.css line 531 */
}
```

with `<body class="page-centre">` at `collection-centre.html:43`. A fixed box covers whatever is
underneath it, so this page reserves 170px of empty space below the footer text. **Other pages
do not have the `page-centre` class, so they are unaffected** â€” that is exactly why the rule is
scoped to `body.page-centre`.

- **Mobile version (lines 568â€“575):** `left: 0; right: 0; bottom: 0; width: auto;`
  `border-radius: 14px 14px 0 0;` turns the floating card into a full-width bottom strip with
  only the top corners rounded.

### 6.6 `position: sticky` â€” `nav` (344â€“349) and `.log-window thead th` (452â€“460)

```css
nav {
    position: sticky;
    top: 0;
    z-index: 50;                     /* above cards, gauges and badges */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
}
```

- **What it does:** behaves like `relative` while scrolling, but the moment the menu would go
  above `top: 0` it **sticks** to the top of the screen and the page scrolls underneath it.
  Scroll back up and it returns to its normal place.
- **Why:** the menu is reachable from anywhere. Because the rule is in the **shared** stylesheet,
  it applies to `index.html`, `about.html`, `dashboard.html` and `collection-centre.html` â€” the
  comment at line 326 says exactly that ("the sticky navigation which now appears on every page").
- **If removed:** the dark menu bar would scroll off the top of the long collection-centre page.
- **`z-index: 50`** keeps it above the cards, gauges and badges while it is stuck.
- **Phone exception (lines 552â€“554):** `nav { position: static; }` â€” on â‰¤600px the menu becomes
  a tall vertical stack (set by the media query at line 312) that would fill half the screen, so
  on phones it scrolls away normally instead of sticking.

```css
.log-window thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background-color: #6b4226;
    color: #ffffff;
}
```

```html
<div class="log-window">                             <!-- collection-centre.html:132 -->
  <table>
    <thead>
      <tr><th>Farmer</th><th>Quantity (L)</th><th>Fat %</th>
          <th>SNF %</th><th>Rate (Rs)</th><th>Status</th></tr>
    </thead>
    <tbody>
      <tr><td>Ramesh Patil</td><td>18.5</td><td>4.6</td><td>8.9</td><td>44</td><td>Paid</td></tr>
      ... 19 rows in total ...
    </tbody>
  </table>
</div>
```

- **What it does:** inside the scrollable log, the column titles stay pinned while the 19 farmer
  rows scroll past.
- **Why:** without it you lose track of what a number like `8.5` means halfway down the list.
- **Positioned relative to:** the nearest **scrolling ancestor**, which is `.log-window`
  (because of `overflow: auto`) â€” **not** the page.
- **Why the `background-color` is compulsory here:** a sticky element must be opaque, otherwise
  the rows would show through the header text.
- **If removed:** the header row would scroll away with the first few rows.

### 6.7 relative parent â†’ absolute child

The single most important relationship in this assignment. There are **two exact chains** in my
project:

```
<article class="stat-card tank-card">      position: relative   <-- PARENT
    <span class="status-badge ok">         position: absolute   <-- CHILD
</article>

<div class="photo-frame">                  position: relative   <-- PARENT
    <img src="../assets/milk-can.svg">     static
    <p class="photo-caption">              position: absolute   <-- CHILD
</div>
```

How it works, in four rules:

1. `position: relative` on the parent makes **that parent** the containing block for every
   absolutely positioned descendant. Remove it and the child jumps to the *next* positioned
   ancestor, or to the page.
2. The parent **keeps its place in the flow** â€” `relative` does not remove it from layout. The
   absolute child **is** removed from the flow, which is why the badge does not push the card's
   `<h3>` downwards.
3. The child is measured from the parent's **padding box** (just inside the border). So
   `top: 8px` on `.status-badge` means 8px below the card's inner top edge, and `right: 8px`
   means 8px in from its right edge.
4. `position: fixed` is the exception â€” its containing block is always the **viewport**. No
   ancestor can change that here, because no `transform` is used anywhere in `style.css` (a
   `transform` on an ancestor *would* capture fixed descendants).

**Live demonstration (do this in Chrome):** delete `position: relative` from `.tank-card` and
watch all four badges fly to the top-right corner of the page. Put it back and delete
`position: absolute` from `.status-badge` and watch each badge drop into the text flow above its
heading.

### 6.8 fixed vs sticky

| Point | `position: sticky` â€” `nav`, `.log-window thead th` | `position: fixed` â€” `.rate-ticker` |
|---|---|---|
| Measured against | the nearest **scrolling ancestor** (the page, or `.log-window`) | always the **viewport / screen** |
| Respects the parent box | **Yes** â€” it cannot leave its parent; it stops sticking at the parent's bottom edge | **No** â€” it ignores the document flow completely |
| Removed from the flow? | **No** â€” it keeps its space, nothing else is affected | **Yes** â€” it leaves a hole where it was in the HTML |
| Returns to normal place on scrolling back? | **Yes**, once you scroll above the trigger point | **Never** â€” pinned for the whole visit |
| Trigger | `top: 0` â€” the element must physically reach that offset before it sticks | `left` / `bottom` say where it lives permanently |
| In my project | the menu follows you down the page and stops at the end of the document; the table header follows only the log's own scrollbar | the rate board never moves, even when the footer is at the top of the screen |

**One-sentence exam answer:** *sticky scrolls until it "catches" its `top` offset and then holds,
still inside its parent; fixed is nailed to the screen from the moment the page loads and ignores
the document entirely.*

### 6.9 The offset properties

`top`, `right`, `bottom`, `left` do nothing on a `static` element and control the offset on
`relative`, `absolute`, `fixed` and `sticky`.

| Selector | Values (line) | Effect |
|---|---|---|
| `nav` | `top: 0` (346) | the sticky trigger |
| `.status-badge` | `top: 8px; right: 8px` (378â€“379) | badge in the card's top-right corner |
| `.photo-caption` | `left: 0; bottom: 0` (488â€“489) | caption across the frame's bottom |
| `.rate-ticker` | `left: 20px; bottom: 20px` (504â€“505) â†’ mobile `left: 0; right: 0; bottom: 0` | screen corner â†’ full-width bottom strip |
| `.log-window thead th` | `top: 0` (456) | sticky column titles |

### 6.10 `z-index`

A **stacking order** number. Higher numbers paint on top. It only works on positioned elements
(and flex/grid items), and only elements inside the same stacking context compete.

The project's scale, from `style.css`:

| Value | Element | Line |
|---|---|---|
| `2` | `.log-window thead th` â€” sticky table header | 457 |
| `3` | `.status-badge` â€” badge above card text | 380 |
| `50` | `nav` â€” sticky menu above everything except the rate board | 347 |
| `100` | `.rate-ticker` â€” rate board above every other element | 506 |

**Live demonstration:** change `.rate-ticker`'s `z-index` from `100` to `1` and scroll â€” the
board slides **behind** the sticky menu and behind the cards.

### 6.11 `overflow`

Three distinct uses in this project:

| Value | Selectors (line) | Why |
|---|---|---|
| `hidden` | `.tank-card` (370), `.photo-frame` (473), `.rate-ticker` (513) | clips content to the box so nothing spills outside the rounded corners |
| `auto` | `.log-window` (431) | shows a scrollbar **only when needed**, so a 19-row table does not make the page endless |
| *(not declared)* | `body` / `html` | deliberately â€” see the warning below |

```css
.log-window {
    max-height: 230px;   /* line 430 â€” this is what forces the scrollbar */
    overflow: auto;
    ...
}
```

**Important warning to remember:** `overflow: hidden` on `body` or on any ancestor would
**silently break** `position: sticky` for everything inside it. This project avoids that â€” that
is why the sticky menu works.

### 6.12 `opacity`

Used once, on the tank fill bar (line 417):

```css
.tank-fill {
    height: 100%;
    background-color: #e8b84b;
    border-radius: 5px;
    opacity: 0.85;      /* slightly translucent */
}
```

The gold bar is 15 % transparent, so the pale gauge background shows through a little.

**Compare with the caption:** `.photo-caption` uses `rgba(75, 46, 14, 0.7)` instead. Both create
transparency, but `opacity` affects the element **and all its children**, while an `rgba()` colour
affects **only that one colour**.

### 6.13 `width` and `height`

```css
.tank-gauge { height: 20px; }              /* 404 â€” a fixed 20px tall bar */
.tank-fill  { height: 100%; }              /* 413 â€” fills the bar vertically */

.gauge-92 { width: 92%; }                  /* 421 */
.gauge-74 { width: 74%; }                  /* 422 */
.gauge-58 { width: 58%; }                  /* 423 */
.gauge-35 { width: 35%; }                  /* 424 */

.photo-frame { width: 260px; max-width: 100%; height: 220px; }   /* 466â€“469 */
.rate-ticker { width: 290px; }             /* 507  -> mobile: width: auto */
.log-window  { max-height: 230px; }        /* 430  -> mobile: 200px */
```

- The `.gauge-*` classes are the cleverest idea in this assignment: **the fill level is data
  written as a width percentage in a class name.** No JavaScript is involved.
- `max-width: 100%` on `.photo-frame` lets a fixed width shrink when the screen is narrow â€”
  this is the manual version of what media queries do automatically.
- On mobile `.rate-ticker` gets `left: 0; right: 0; width: auto`, so the two offsets stretch it
  full width.
- Remember `* { box-sizing: border-box; }` (line 211) â€” all these widths **include** padding and
  border, which is what stops the grid and flex items from overflowing.

### 6.14 `margin` vs `padding`

- **`margin`** = space **outside** the border, between elements. Vertical margins between
  siblings collapse. `margin: 20px auto` centres a block.
- **`padding`** = space **inside** the border. The background colour extends into it, and
  padding never collapses.

Actual examples:

```css
.centre-note  { margin: 12px 0 4px 0; padding: 10px 14px; }  /* both, on the same box */
.photo-caption{ margin: 0; padding: 8px 12px; }              /* margin:0 makes it flush */
main          { margin: 20px auto; padding: 0 15px; }         /* auto = centring */
section       { padding: 20px 25px; margin-bottom: 20px; }    /* only bottom margin, so sections stack cleanly */
```

### 6.15 `border`

Shorthand order is **width â†’ style â†’ colour**.

| Example (line) | Meaning |
|---|---|
| `border: 1px solid #e0cda8` (122) | thin light border on sections |
| `border-left: 6px solid #e8b84b` (133) | thick gold stripe on the left of cards |
| `border-bottom: 4px solid #c98a2d` (39) | darker strip under the header |
| `border: 2px dashed #d9a441` (`index.html` 40) | the only **dashed** border in the project |
| `border-top: 2px solid #e8b84b` (496) | gold line on top of the photo caption |
| `border-bottom: 1px solid #eadfc8` (447) | row separators in the collection log |
| `border: 0` (481) | removes the global `img` frame inside `.photo-frame` |

### 6.16 `border-radius`

| Value | Selector | Result |
|---|---|---|
| `10px` | `section` (123), `img` (183) | gently rounded card / frame |
| `8px` | `article` (134), `.btn` (160), `.centre-note` (359), `.tank-gauge` (408), `.rate-ticker` (510) | standard card corners |
| `6px` | `nav a` (88), `.tank-fill` (416) | small soft corners |
| `12px` | `.photo-frame` (472), `.rate-ticker` (510) | noticeably rounded |
| `999px` | `.status-badge` (382) | a full **pill** |
| `14px 14px 0 0` | mobile `.rate-ticker` (573) | 4-value shorthand â€” per-corner: top-left, top-right, bottom-right, bottom-left |

### 6.17 `box-shadow`

Format: `offset-x offset-y blur-radius colour`.

| Example (line) | Effect |
|---|---|
| `2px 2px 6px rgba(0,0,0,0.08)` (126) | very soft lift under each section |
| `0 3px 8px rgba(0,0,0,0.14)` (372) | tank cards stand off the page |
| `0 2px 6px rgba(0,0,0,0.25)` (348) | the sticky menu appears to float above the content |
| `3px 3px 10px rgba(0,0,0,0.18)` (475) | the picture frame |
| `0 8px 20px rgba(0,0,0,0.3)` (512) | the strongest shadow â€” makes the rate board look closest to the viewer |
| `2px 2px 8px rgba(0,0,0,0.2)` (`about.html` 104) | inline shadow on each About image |

`0 0` x/y offsets give a shadow straight behind the box (used as a glow); positive values push
it down and to the right, which matches the light direction of the cream page.

---

## 7. How the Current Project Works

Traced using `frontend/pages/collection-centre.html`, because it is the page that uses all five
positions at once.

**Step 1 â€” You open the file in Chrome.**
The path becomes a `file:///C:/.../pages/collection-centre.html` URL. Nothing is running on a
server; the browser reads files straight from the disk.

**Step 2 â€” Chrome parses `<head>` top to bottom (lines 30â€“39).**
- Line 31 `<meta charset="UTF-8">` fixes the character encoding.
- Line 32 `<meta name="viewport" ...>` makes the CSS width equal the real screen width. Without
  it, a phone would pretend to be 980px wide and **none** of the media queries would ever fire.
- Line 38 `<link rel="stylesheet" href="../css/style.css">` is **render-blocking**: Chrome
  downloads the CSS *before* painting anything, because it needs every style before it can lay
  the page out. If the file were missing the page would still show, just unstyled.

**Step 3 â€” Chrome builds the DOM tree (the page's skeleton).**
`body.page-centre` â†’ `header` â†’ `nav > ul > li > a` â†’ `main` â†’ four `section`s (each holding
`.centre-note`, `.stats-grid`, `.log-window > table`, `.flex-row` or `.photo-frame`) â†’ `aside`
â†’ `.rate-ticker` â†’ `footer`. At this point the elements **exist but have no size, colour or
position**.

**Step 4 â€” The cascade decides which CSS wins for each element.**
- Most elements match one or two rules with no conflict.
- `<span class="status-badge ok">` (line 90) matches **both** `.status-badge` and
  `.status-badge.ok`. `.status-badge.ok` has two class selectors, so it wins for
  `background-color` â†’ the badge is **green**, not red.
- `<article style="background-color:#fde8d7; border: 2px solid #d98c5f;">` on `index.html:142`
  â€” the inline style beats the stylesheet, so that one card is highlighted.
- `nav` is matched by three rules (59, 68/83, 344). They set different properties, so all three
  survive.

**Step 5 â€” Flexbox and Grid lay out the boxes.**
- `.stats-grid` (line 79) â†’ `display: grid` + `repeat(4, 1fr)` â†’ the width is measured, split
  into four equal columns minus the 20px gaps, and each `<article>` is auto-placed into the next
  cell.
- `.flex-row` (line 182) â†’ `display: flex` â†’ the three `.flex-item`s each ask for
  `flex: 1 1 200px`, free space is shared equally, so all three end up the same width; the
  default `align-items: stretch` makes them the same height.
- `nav ul` â†’ `display: flex; justify-content: center` â†’ the four links sit in one centred row.

**Step 6 â€” Positioning is applied.**
- `.tank-card` (relative) becomes the anchor; each `.status-badge` (absolute,
  `top: 8px; right: 8px`) is placed against its own card and lifted out of the flow, so the
  `<h3>` is **not** pushed down.
- `.photo-frame` (relative) anchors `.photo-caption` (absolute, `left: 0; bottom: 0;
  width: 100%`); `overflow: hidden` clips the milk-can SVG to the rounded frame.
- `.log-window` gets `max-height: 230px; overflow: auto` â†’ because the table is taller, a
  **scrollbar appears inside that box**; its `thead th` cells are `sticky; top: 0` and stick to
  **that** scrollbar, not to the page.
- `nav` is `sticky; top: 0` â†’ it will catch at the top of the viewport when you scroll past it.
- `.rate-ticker` is `fixed; left: 20px; bottom: 20px; z-index: 100` â†’ it is already in the
  bottom-left corner of the screen before you scroll a single pixel.
- `body.page-centre footer { padding-bottom: 170px; }` reserves empty space so the fixed board
  never hides the footer text.

**Step 7 â€” Media queries are checked last.**
Chrome compares the window width to each `@media` condition. At 1366px nothing fires (desktop).
At 850px: `.stats-grid` â†’ 2 columns and `.photo-frame` â†’ full width. At 500px: grid â†’ 1 column,
`.flex-row` and the menu stack vertically, `nav` â†’ `position: static`, `.rate-ticker` â†’ full-width
bottom strip, `.log-window` â†’ 200px, footer padding â†’ 150px. Everything changes **instantly,
with no page reload**.

**Step 8 â€” Paint order.**
`z-index` decides who covers whom: `.log-window thead th` (2) â†’ `.status-badge` (3) â†’ sticky
`nav` (50) â†’ `.rate-ticker` (100).

**The result:** a styled, responsive working screen built entirely from HTML + CSS, with **zero
JavaScript** â€” which is exactly the point of Assignments 1 to 5.

---

# Assignment 6 â€” JavaScript Events and Array Functions

**Files involved:** `frontend/js/main.js` (all the code) and
`frontend/pages/dashboard.html` (the interface it drives). A small new block of CSS at the end
of `frontend/css/style.css` styles the new controls.

**Nothing in Assignments 1â€“5 was changed.** `style.css` gained 94 lines and no existing line was
touched. In `dashboard.html` the only changes to existing markup are two added attributes
(`id="shift-summary"` and `data-shift` on the shift blocks) and an updated comment â€” no text,
no class and no structure was removed, so the Flexbox / Grid / Media Query demonstration on that
page works exactly as before.

---

## A6.1 How the external JavaScript file is linked

### Where the script tag is

`frontend/pages/dashboard.html`, as the **last element before `</body>`** (line 230):

```html
    <!-- ============================================================
         ASSIGNMENT 6 : EXTERNAL JAVASCRIPT FILE
         The script tag is placed at the END of <body> so that every
         element main.js looks for is already loaded when the script
         runs. ".." goes up from pages/ to frontend/, then into js/.
         There is no inline JavaScript anywhere in this project.
    ============================================================ -->
    <script src="../js/main.js"></script>

</body>
```

### Why exactly here â€” the viva answer

Three reasons, all worth saying:

1. **The path is relative to the HTML file, not to the project root.** `dashboard.html` sits in
   `frontend/pages/`, so `..` moves up to `frontend/` and `js/main.js` then finds
   `frontend/js/main.js`. This is the same `..` rule already used for `../css/style.css` and
   `../assets/cow.svg`.
2. **It is at the end of `<body>` so the HTML is already parsed.** When the script runs, the
   `<select>`, the `<input>`, the button, the empty `<tbody>` and the stat cards all exist, so
   `document.getElementById()` can find them on the first try. A `<script>` in the `<head>`
   would run *before* the body existed, and every lookup would return `null`.
3. **Using a `src` attribute keeps it external.** All the JavaScript is in `main.js`, so the page
   contains no JavaScript at all â€” the same separation the project already uses for CSS
   (`<link rel="stylesheet">` in `<head>` instead of styles in the page).

`DOMContentLoaded` (line 416) is used as a second safety net:

```javascript
document.addEventListener("DOMContentLoaded", startApp);
```

This event fires once the browser has finished reading the whole page. It is the standard, most
reliable moment to start looking for elements.

### There is no inline JavaScript

Check it yourself: search the project for `onclick=`, `onchange=`, `oninput=`, `onmouseover=`
or `javascript:` â€” there are **no matches** in any HTML file. No `onclick="refreshRegister()"`
attribute exists anywhere. Every event is connected inside `main.js` with
`addEventListener()`.

---

## A6.2 Where the interface lives

The section is called **"Today's Collection Register"** in `dashboard.html`. It is the normal
working list of the shift, not a JavaScript demo box. In the HTML it is deliberately **empty** â€”
only the controls and the table frame exist:

```html
<section>
    <h2>Today's Collection Register</h2>

    <div class="register-tools">
        <label for="collection-filter">Show collections:</label>
        <select id="collection-filter">
            <option value="all">All collections</option>
            <option value="15">15 litres and above</option>
            <option value="20">20 litres and above</option>
        </select>

        <label for="farmer-search">Find a farmer:</label>
        <input type="search" id="farmer-search" placeholder="Type part of a name">

        <button type="button" class="btn" id="refresh-totals">Refresh Totals</button>
    </div>

    <p class="register-status" id="register-status">Loading today's register...</p>

    <div class="log-window">
        <table>
            <thead>
                <tr>
                    <th>Farmer</th><th>Quantity (L)</th><th>Fat %</th>
                    <th>Rate (Rs)</th><th>Amount (Rs)</th>
                </tr>
            </thead>
            <tbody id="collection-body"></tbody>   <!-- forEach() fills this -->
        </table>
    </div>

    <h3>Totals for the list above</h3>
    <div class="flex-row" id="register-totals"></div>   <!-- map()/reduce() fill this -->
</section>
```

Two smart reuses of existing CSS:

- The register table sits inside **`.log-window`** â€” the Assignment 5 class. So it already has
  the rounded border, its **own scrollbar** (`max-height: 230px; overflow: auto`) and the
  **sticky header row**, with **no new CSS at all**.
- The totals blocks are `.flex-item` inside `.flex-row` â€” the Assignment 4 classes. So they
  automatically reflow on smaller screens.

The three shift blocks above the register carry a `data-shift` attribute:

```html
<div class="flex-item" data-shift="Morning"> ... Morning Shift ... </div>
<div class="flex-item" data-shift="Evening"> ... Evening Shift ... </div>
<div class="flex-item" data-shift="All">     ... This Month ...     </div>
```

**Why a `data-shift` attribute:** the heading reads "Morning Shift" but the records store
`"Morning"`. Reading the shift from the heading text produced zero matching rows (this was a
real bug found while testing). A `data-*` attribute keeps the machine value in the HTML and the
friendly wording in the heading, which is the normal way to do it.

---

## A6.3 The data â€” the array that everything works on

`main.js` lines 52â€“63. Eight collection records, each an object with six properties:

```javascript
const collections = [
    { farmer: "Ramesh Patil",   liters: 18.5, fat: 4.6, snf: 8.9, rate: 44, shift: "Morning" },
    { farmer: "Sunita Jadhav",  liters: 12.0, fat: 3.8, snf: 8.2, rate: 40, shift: "Morning" },
    { farmer: "Vilas More",     liters: 22.5, fat: 4.9, snf: 9.1, rate: 45, shift: "Evening" },
    { farmer: "Anita Deshmukh", liters: 15.0, fat: 4.2, snf: 8.6, rate: 42, shift: "Morning" },
    { farmer: "Ganesh Pawar",   liters:  9.5, fat: 3.9, snf: 8.4, rate: 40, shift: "Evening" },
    { farmer: "Meena Kulkarni", liters: 20.0, fat: 4.7, snf: 9.0, rate: 44, shift: "Morning" },
    { farmer: "Shalini Joshi",  liters: 17.5, fat: 4.4, snf: 8.8, rate: 43, shift: "Evening" },
    { farmer: "Nitin Dhage",    liters: 24.0, fat: 4.9, snf: 9.3, rate: 45, shift: "Morning" }
];
```

The farmer names are the same people who appear in the collection-centre log, so the project
reads as one application. In a later phase this array will be replaced by data coming from the
database â€” the events and the array functions will not change.

Two variables remember what the user is currently looking at (lines 65â€“66):

```javascript
let currentRecords = collections;   /* the records now on screen  */
let currentShift = "All";           /* the clicked shift filter   */
```

---

## A6.4 The events used, and what each one does

All the event connections live inside `startApp()` (lines 289â€“414), so they are set up in one
readable place.

| # | Event | Line | HTML element that triggers it | What it does |
|---|---|---|---|---|
| 1 | `DOMContentLoaded` | 416 | the document | runs `startApp()`, connects everything else and draws the register the first time |
| 2 | `change` | 296 | `<select id="collection-filter">` | reads the chosen value and calls `refreshRegister()`, which uses `filter()` to show only the collections of 15 L / 20 L and above |
| 3 | `input` | 305 | `<input id="farmer-search">` | on **every keystroke** calls `findFarmer()`, which uses `find()`; writes the matching farmer's details into the status strip, or says nobody matched |
| 4 | `click` | 322 | `<button id="refresh-totals">` | re-runs the whole register and writes "Totals recalculated at 10:42:15 from 8 register entries" using `new Date().toLocaleTimeString()` |
| 5 | `mouseover` | 343 | the four `.stats-grid .stat-card` | writes that card's heading and label into the status strip, explaining what the card means |
| 6 | `mouseout` | 348 | the same cards | puts the standard summary message back |
| 7 | `click` | 353 | the same cards | adds the `selected` class to that card and removes it from the others; clicking the same card again clears the selection |
| 8 | `click` | 383 | `#shift-summary .flex-item` (3 blocks) | sets `currentShift` from the block's `data-shift`, then `filter()` keeps only that shift's records; clicking the same block again clears the filter |
| 9 | `click` | 122 | each `<tr>` drawn in `<tbody id="collection-body">` | adds `selected` to that row and writes the farmer's full collection summary into the status strip |

### Code for the four most important ones

**`change` â€” the filter dropdown** (lines 293â€“297):

```javascript
    const filterSelect = document.getElementById("collection-filter");

    if (filterSelect) {
        filterSelect.addEventListener("change", refreshRegister);
    }
```

Note that the listener is the function name `refreshRegister` **without brackets** â€” that passes
the function itself. Writing `refreshRegister()` would run it immediately instead of waiting
for the event.

**`input` â€” the farmer search box** (lines 302â€“317):

```javascript
    const searchBox = document.getElementById("farmer-search");

    if (searchBox) {
        searchBox.addEventListener("input", function () {
            const record = findFarmer(searchBox.value);

            if (record) {
                setStatus("Found: " + record.farmer + ", " +
                          record.liters.toFixed(1) + " litres, " +
                          record.fat.toFixed(1) + "% fat, " + record.shift + " shift.");
            } else {
                setStatus('No farmer found matching "' + searchBox.value + '".');
            }
        });
    }
```

`input` fires on every keystroke, which is why the search feels immediate. (`change` would only
fire when focus leaves the box.) This is **not** form validation â€” nothing is rejected, the box
simply searches.

**`mouseover` + `mouseout` + `click` on the stat cards** (lines 332â€“376):

```javascript
    const statCards = document.querySelectorAll(".stats-grid .stat-card");

    if (statCards.length > 0) {
        statCards.forEach(function (card) {
            const heading = card.querySelector("h3");
            const label = card.querySelector(".stat-label");
            const cardName = heading ? heading.textContent : "";
            const cardHint = (label && label.textContent) || "";
            const fullHint = cardName + " - " + cardHint;

            /* hovering explains the card ... */
            card.addEventListener("mouseover", function () {
                setStatus(fullHint);
            });

            /* ... and leaving it puts the summary back */
            card.addEventListener("mouseout", function () {
                setStatus(summaryText());
            });

            /* clicking selects the card; clicking again clears it */
            card.addEventListener("click", function () {
                const wasSelected = card.classList.contains("selected");

                statCards.forEach(function (otherCard) {
                    otherCard.classList.remove("selected");
                });

                if (wasSelected) {
                    setStatus("Selection cleared. " + summaryText());
                } else {
                    card.classList.add("selected");
                    setStatus("Selected: " + fullHint);
                }
            });
        });
    }
```

**`click` on a row, added from inside `forEach()`** (lines 117â€“125):

```javascript
        const row = document.createElement("tr");
        row.innerHTML = "..." ;
        tableBody.appendChild(row);

        /* click event on every row that is drawn */
        row.addEventListener("click", function () {
            selectRow(row, record);
        });
```

The listener is attached **as each row is created**, so every row gets its own handler with its
own `record` value attached (this is called a *closure*). Because `forEach()` redraws all the
rows every time the filter changes, the new rows get fresh listeners too â€” nothing is lost.

---

## A6.5 The array functions used, and what each one does here

Each function has exactly one job. This is the table to revise from.

| Function | Function in `main.js` | Line | What it does in this project | Returns |
|---|---|---|---|---|
| `forEach()` | `showRecords()` | 100 | draws one `<tr>` per record into `#collection-body`, and attaches a click listener to each row | nothing (`undefined`) |
| `map()` | `paymentAmounts()` | 139 | builds a new array of payment amounts: `record.liters * record.rate` | a **new** array of 8 numbers |
| `filter()` | `recordsAtLeast()` | 130 | keeps only records with `liters >= minimumLitres` | a **new** array |
| `filter()` | inside `refreshRegister()` | 270 | keeps only records whose `shift` equals the clicked shift | a **new** array |
| `filter()` | inside `showTotals()` | 220 | counts the low-fat cans â€” fat below 3.5%, which are paid at the lower 40 Rs rate | a **new** array |
| `find()` | `findFarmer()` | 147 | returns the **first** record whose farmer name contains the typed text | one record, or `undefined` |
| `reduce()` | `totalLitres()` | 162 | adds up all the litres, starting the total at `0` | one number |
| `reduce()` | `totalAmount()` | 170 | adds up the array of amounts that `map()` created | one number |
| `reduce()` | `averageFat()` | 178 | adds up the fat values and divides by `recordList.length` | one number |

### The code

**`forEach()` â€” draw the rows (lines 100â€“128):**

```javascript
function showRecords(recordList) {
    const tableBody = document.getElementById("collection-body");

    if (!tableBody) {
        return;
    }

    tableBody.innerHTML = "";            /* clear the old rows first */

    recordList.forEach(function (record) {
        const row = document.createElement("tr");

        row.innerHTML =
            "<td>" + record.farmer + "</td>" +
            "<td>" + record.liters.toFixed(1) + "</td>" +
            "<td>" + record.fat.toFixed(1) + "%</td>" +
            "<td>" + record.rate + "</td>" +
            "<td>" + toMoney(record.liters * record.rate) + "</td>";

        tableBody.appendChild(row);

        row.addEventListener("click", function () {
            selectRow(row, record);
        });
    });
}
```

`forEach()` visits every item but **returns nothing**. It is used here for its side effect â€”
creating a row in the page.

**`map()` â€” build the amounts array (lines 139â€“143):**

```javascript
function paymentAmounts(recordList) {
    return recordList.map(function (record) {
        return record.liters * record.rate;
    });
}
```

`map()` returns a **new array of the same length**, with one new value for each old value. The
original `collections` array is not changed. For the eight records this produces
`[814, 480, 1012.5, 630, 380, 880, 752.5, 1080]`.

**`filter()` â€” keep what we want (lines 130â€“134):**

```javascript
function recordsAtLeast(minimumLitres) {
    return collections.filter(function (record) {
        return record.liters >= minimumLitres;
    });
}
```

`filter()` also returns a **new array**, containing only the records where the test is `true`.
Choosing "15 litres and above" gives 6 records totalling 117.5 L; "20 litres and above" gives
3 records. `filter()` never changes the original array.

**`find()` â€” look up one farmer (lines 147â€“158):**

```javascript
function findFarmer(nameTyped) {
    const wanted = nameTyped.trim().toLowerCase();

    if (wanted === "") {
        return undefined;
    }

    return collections.find(function (record) {
        return record.farmer.toLowerCase().indexOf(wanted) !== -1;
    });
}
```

- `.trim()` removes accidental spaces, `.toLowerCase()` makes the search case-insensitive.
- `indexOf(...) !== -1` means "the text was found somewhere inside the name", so typing
  `"nitin"` matches `"Nitin Dhage"`.
- `find()` **stops at the first match** and returns that single object â€” not an array.
- If nobody matches, `find()` returns `undefined`, which is why the code checks
  `if (record) { ... } else { ... }`.

**`reduce()` â€” three different sums (lines 162â€“191):**

```javascript
function totalLitres(recordList) {
    return recordList.reduce(function (total, record) {
        return total + record.liters;
    }, 0);
}

function totalAmount(amounts) {
    return amounts.reduce(function (total, amount) {
        return total + amount;
    }, 0);
}

function averageFat(recordList) {
    if (recordList.length === 0) {
        return 0;
    }

    const fatTotal = recordList.reduce(function (total, record) {
        return total + record.fat;
    }, 0);

    return fatTotal / recordList.length;
}
```

`reduce()` carries **one running value** through the array. The `0` at the end is the **initial
value** of that running total â€” without it the first addition would start from `undefined` and
the result would be `NaN`. The empty-array check in `averageFat()` stops a division by zero.

### `map()` and `reduce()` working together

This is the classic pair, and it is used in `showTotals()` (lines 213â€“214):

```javascript
    const money = totalAmount(paymentAmounts(recordList));     /* map + reduce */
```

Read it inside out: `map()` first makes the amounts array, then `reduce()` adds it up. You could
do it in one `reduce()`, but splitting the two steps keeps each function doing one job.

### The results the page shows with all 8 records

| Block | How it is calculated | Value |
|---|---|---|
| Litres in this view | `reduce()` over `liters` | 139.0 L |
| Payment in this view | `map()` then `reduce()` | â‚¹ 6029.00 |
| Average fat | `reduce()` Ã· `length` | 4.42% |
| Low-fat cans | `filter()` length | 0 cans (all records are 3.8% or above) |

The values change as soon as the filter or the shift changes, because
`refreshRegister()` recomputes them every time.

---

## A6.6 Error safety â€” how the code avoids errors

**1. Every element is checked before it is used.** The pattern is used consistently:

```javascript
    const searchBox = document.getElementById("farmer-search");

    if (searchBox) {
        searchBox.addEventListener("input", function () { ... });
    }
```

The same check guards the `<select>`, the button, `#register-status`, `#collection-body`,
`#register-totals` and the `querySelectorAll` results (`if (statCards.length > 0)`). This is
why `main.js` can be added to any page later without that page breaking: if the element is not
there, the code simply skips that part.

**2. `refreshRegister()` tolerates a missing control:**

```javascript
    const filterSelect = document.getElementById("collection-filter");
    let recordList = collections;

    if (filterSelect && filterSelect.value !== "all") { ... }
```

**3. Typed text is never put into the page as HTML.** `setStatus()` uses `textContent`:

```javascript
function setStatus(message) {
    const statusBox = document.getElementById("register-status");

    if (!statusBox) {
        return;
    }

    statusBox.textContent = message;
}
```

`textContent` always shows plain text. Had `innerHTML` been used, typing `<b>` into the search
box would have been interpreted as a tag. The row HTML in `showRecords()` is safe because it is
built only from our own `collections` data, never from user input.

**4. Empty results are handled.** `averageFat()` returns `0` for an empty list instead of
dividing by zero, and `findFarmer()` returns `undefined` for an empty box.

---

## A6.7 JavaScript concepts used in `main.js`

1. `const` and `let` â€” `collections` never changes so it is `const`; `currentShift` and
   `currentRecords` change, so they are `let`.
2. **Objects** â€” each record is `{ farmer: ..., liters: ..., shift: ... }`.
3. **Arrays of objects** and dot access such as `record.liters`.
4. **Array functions**: `forEach()`, `map()`, `filter()`, `find()`, `reduce()`.
5. **Non-mutating array functions** â€” `map()`, `filter()`, `find()` and `reduce()` all return
   something new; the original array is never changed.
6. The `reduce()` **accumulator** and its **initial value**.
7. **Functions** â€” `function name() { }`, functions that `return` a value, and functions used
   as event handlers.
8. **Arrow-free callbacks** â€” plain `function (record) { }` is used inside the array functions.
9. `document.getElementById()` and `document.querySelectorAll()`.
10. **DOM manipulation**: `document.createElement()`, `innerHTML`, `textContent`,
    `appendChild()`, `classList.add()` / `remove()` / `contains()`.
11. **Events**: `addEventListener()`, and the `click`, `mouseover`, `mouseout`, `input`,
    `change` and `DOMContentLoaded` types.
12. `getAttribute()` for reading `data-shift`.
13. **String methods**: `trim()`, `toLowerCase()`, `indexOf()`, and joining with `+`.
14. **Number methods**: `toFixed(2)` and `toFixed(1)`; `Number()` to convert a string.
15. `new Date().toLocaleTimeString()` for the recalculation time.
16. **Closures** â€” each row's click handler remembers its own `record`.

---

## A6.8 Viva questions and answers â€” Assignment 6

**1. How is the JavaScript file connected to the HTML page?**
`dashboard.html` has `<script src="../js/main.js"></script>` as the last line before `</body>`.
The `src` path is relative to the HTML file: `dashboard.html` is in `frontend/pages/`, so `..`
goes up to `frontend/` and `js/main.js` finds `frontend/js/main.js`. It is at the end of the body
so the HTML is already loaded when the script runs.

**2. Why put the script tag at the end of `<body>` instead of in the `<head>`?**
A script in the `<head>` runs *before* the body exists, so `document.getElementById()` would
return `null` for every element. At the end of the body all the elements already exist. I also
listen for `DOMContentLoaded` as a second safety net.

**3. Do you use any inline JavaScript?**
No. There is no `onclick`, `onchange` or `oninput` attribute in any HTML file, and no `<script>`
block written inside a page. Everything is in the external file `main.js` and connected with
`addEventListener()`. This is easier to maintain and the page stays readable.

**4. Which events have you used?**
`DOMContentLoaded` to start the app, `change` on the litres dropdown, `input` on the farmer
search box, `click` on the Refresh Totals button, on the stat cards, on the shift blocks and on
each table row, and `mouseover` / `mouseout` on the stat cards.

**5. What is the difference between `input` and `change`?**
`input` fires on every keystroke, so the search feels instant. `change` on a `<select>` fires
once when a different option is chosen, because a dropdown has nothing to type.

**6. Which array functions did you use and what for?**
`forEach()` to draw one table row per record; `map()` to build a new array of payment amounts;
`filter()` to keep only the records of 15 L and above, only the clicked shift, or only the
low-fat cans; `find()` to look up one farmer from the search box; `reduce()` to add up the
litres, the money and the fat values.

**7. What is the difference between `map()` and `filter()`?**
`map()` returns a **new array of the same length** with each value changed â€” here each record
becomes its payment amount. `filter()` returns a **new array that may be shorter or empty**,
containing only the records that pass the test. Neither one changes the original array.

**8. What does `reduce()` do, and what is the `0` at the end for?**
It carries one running value through the array and returns it at the end. The `0` is the
**initial value** of that running total. Without it, `total + record.liters` would start from
`undefined` and the answer would be `NaN`.

**9. What is the difference between `find()` and `filter()`?**
`find()` returns the **first single matching item** (or `undefined`), and stops looking as soon
as it finds one. `filter()` returns **all** matching items as a new array. I use `find()` for
the farmer search because one farmer is enough, and `filter()` for the litres and shift filters
because many records can match.

**10. Why does your code check `if (element)` before using it?**
Because `getElementById()` returns `null` when the element is not on the page. If the code used
a missing element it would throw `TypeError: Cannot read properties of null` and the whole
script would stop. The checks let the same file work on pages where the register is not present.

**11. How do you give a row a click event when the row is created by JavaScript?**
The `click` listener is attached inside the `forEach()` loop, right after the row is created.
Each handler keeps its own `record` value. Since `forEach()` redraws all the rows every time the
filter changes, the new rows get new listeners automatically.

**12. What is `classList.add()` and `classList.remove()` used for?**
They add and remove a CSS class name on an element. I use them to swap the `selected` class on a
stat card, a shift block or a table row, and the CSS rule `.log-window tbody tr.selected` does
the highlighting. That way JavaScript only changes a class name and never touches the colours.

**13. What is `textContent` and why not `innerHTML`?**
Both write text into an element. `textContent` always shows plain text; `innerHTML` interprets
the text as HTML. I write the status messages with `textContent`, so anything a user types in
the search box can never become a tag.

**14. Where do you store the collection records, and will you keep them there?**
In a plain array called `collections` at the top of `main.js`, as eight objects with farmer,
liters, fat, snf, rate and shift. In a later phase this array will be replaced by records coming
from the database, but the events and the array functions will stay exactly the same.

**15. Why did you use a `data-shift` attribute on the shift blocks?**
Because the heading says "Morning Shift" while the records store `"Morning"`. Reading the shift
from the heading text matched nothing and showed 0 rows. The `data-shift` attribute keeps the
exact value used in the records in the HTML, separate from the wording shown to the user.

**16. How do you make the totals update when the filter changes?**
Every event finishes by calling `refreshRegister()`. That one function re-reads the controls,
applies the filters, redraws the rows, redraws the totals and rewrites the status message â€” so
the list, the totals and the message can never disagree with each other.

**17. Has any form validation been written yet?**
Not on this page. Assignment 6 only filters and looks up records. Form validation arrives with
Assignment 7 on `frontend/pages/farmers.html` â€” see the "Assignment 7" section of these notes.

---

## A6.9 New CSS added for Assignment 6

Added at the very end of `frontend/css/style.css`, in a clearly marked
`ASSIGNMENT 6 : STYLES FOR THE JAVASCRIPT PARTS` block. **94 lines were added and no existing
rule was modified**, so Assignments 1â€“5 look identical.

| Selector | Line | Purpose |
|---|---|---|
| `.register-tools` | 616 | the toolbar row â€” `display: flex; flex-wrap: wrap; align-items: center; gap: 10px`, dashed gold border |
| `.register-tools label` | 630 | bold brown labels |
| `.register-tools select`, `.register-tools input` | 635 | padded, rounded white controls |
| `.register-tools select:focus`, `.register-tools input:focus` | 643 | a visible gold outline for keyboard users |
| `.register-tools button` | 649 | `border: 0; cursor: pointer` so the `<button>` matches the `.btn` links from Assignment 3 |
| `.register-status` | 657 | the status strip â€” same look as `.centre-note` from Assignment 5 |
| `.total-value` | 669 | the big 26px value in each totals block |
| `.stats-grid .stat-card.selected`, `.flex-item.selected`, `.log-window tbody tr.selected` | 679 | the highlight `main.js` switches on; descendant selectors keep the specificity correct |
| `.log-window tbody tr` | 686 | `cursor: pointer` so rows show they are clickable |

Note the specificity fix that was applied on purpose: `.stats-grid .stat-card.selected` uses a
descendant selector rather than a plain `.stat-card.selected`, because `.stats-grid .stat-card`
from Assignment 4 already has two class selectors. Using a descendant selector avoids repeating
the specificity mistake described in section 11.1.

---

# Assignment 7 â€” JavaScript Frontend Functionality and Form Validation

**Files:** `frontend/pages/farmers.html` (the form), `frontend/js/main.js` section 6 (all the
logic), `frontend/css/style.css` â€” the `ASSIGNMENT 7` block at the end (the styling).

Nothing else in the project was changed or removed, and there is still only **one** JavaScript
file, `frontend/js/main.js`. It is now loaded by two pages: `dashboard.html` (Assignment 6) and
`farmers.html` (Assignment 7).

---

## A7.1 Purpose of the form

A milk producer joins the society. The clerk at the collection centre types the farmer's details
into the form, presses **Register Farmer**, and:

1. JavaScript checks every box,
2. a wrong entry is refused with a message written **under that box**,
3. a correct form is accepted: the farmer's name, mobile, village, litres, fat and the payment
   for that day appear on the page, and the list of farmers registered in this visit grows by
   one row.

Because Assignment 12 (PHP) and Assignment 13 (PHP + MySQL) have not been written yet, the
record is **not** saved anywhere. It lives in a JavaScript array and disappears when the page is
closed. That is stated on the page itself, in the aside at the bottom.

## A7.2 Form fields (`farmers.html`)

| Field | `id` | `name` | `<input>` type | HTML5 attributes |
|---|---|---|---|---|
| Farmer Name | `farmer-name` | `farmerName` | `text` | `required minlength="3" maxlength="40" pattern="[A-Za-z][A-Za-z .'-]{2,}" autocomplete="name"` |
| Mobile Number | `farmer-mobile` | `mobile` | `tel` | `required maxlength="10" pattern="[6-9][0-9]{9}" inputmode="numeric" autocomplete="tel"` |
| Email | `farmer-email` | `email` | `email` | `maxlength="60" autocomplete="email"` â€” **not** `required` |
| Village / Address | `farmer-village` | `village` | `text` | `required minlength="3" maxlength="60"` |
| Average Milk per Day (L) | `milk-quantity` | `milkQuantity` | `number` | `required min="0.5" max="100" step="0.5"` |
| Fat Percentage | `fat-percentage` | `fatPercentage` | `number` | `required min="3" max="8" step="0.1"` |

- `id` is used by JavaScript (`document.getElementById`) and by the `<label for="...">`.
- `name` is what a server would receive later; it is the field name that would become a column.
- `type="email"` gives the `@` keyboard on a phone, `type="number"` gives the digits-only box,
  `type="tel"` + `inputmode="numeric"` gives the number keypad.
- Two buttons: `type="submit"` (Register Farmer) and `type="reset"` (Clear Form).
- Under every input there is an **empty** `<p class="error-message" id="...-error">`. JavaScript
  writes the message into it, so the message is always next to the field it belongs to.

`min="3" max="8"` on fat and `min="0.5" max="100"` on litres are not arbitrary â€” they are the
limits printed on the rate board of `collection-centre.html` (fat below 3.5% is paid â‚¹40 instead
of â‚¹42).

## A7.3 Why the form has `novalidate`

```html
<form id="farmer-form" class="farmer-form" novalidate>
```

`novalidate` switches off the browser's own pop-up messages ("Please fill out this field"). It has
to be there, otherwise the browser blocks the submit **before** the `submit` event fires and our
own messages would never appear. The HTML5 attributes are still written on the inputs, and
JavaScript repeats the same rules so the messages can be written in the project's own words
("Mobile number must be exactly 10 digits." instead of the browser's wording).

## A7.4 The DOM elements used

| Element | How it is selected | Used for |
|---|---|---|
| the form | `document.getElementById("farmer-form")` | the `submit` and `reset` events, and `form.reset()` |
| the six inputs | `document.getElementById(...)` through the `formRules` list | reading `input.value` and holding the error state |
| the six message boxes | `document.getElementById("...-error")` | `errorBox.textContent = message` |
| the one message box above the form | `document.getElementById("farmer-form-status")` | the red failure message and the green success message |
| the table body below the form | `document.getElementById("registered-body")` | one `<tr>` per registered farmer |
| the count line | `document.getElementById("registered-count")` | "2 farmers registered in this session - 37.0 litres a day between them." |

## A7.5 How the form is selected

The six fields are **not** looked up one by one in six different places. They are declared once,
as a list of rules:

```javascript
const formRules = [
    { inputId: "farmer-name",    errorId: "farmer-name-error",    check: validateFarmerName },
    { inputId: "farmer-mobile",  errorId: "farmer-mobile-error",  check: validateMobile },
    { inputId: "farmer-email",   errorId: "farmer-email-error",   check: validateEmail },
    { inputId: "farmer-village", errorId: "farmer-village-error", check: validateVillage },
    { inputId: "milk-quantity",  errorId: "milk-quantity-error",  check: validateMilkQuantity },
    { inputId: "fat-percentage", errorId: "fat-percentage-error", check: validateFatPercentage }
];
```

Each entry joins three things: **which box to read**, **where its message goes**, and **which
function checks it**. Everything else in the code walks this list with `forEach()`, so adding a
seventh field means adding one line here and one `<input>` in the HTML.

## A7.6 The events

| Event | Where | What happens |
|---|---|---|
| `submit` | `<form id="farmer-form">` | the final check of all six fields |
| `blur` | each of the six inputs | check that one field when the farmer leaves it |
| `input` | each of the six inputs | re-check a field that is already showing an error |
| `reset` | `<form id="farmer-form">` | clear every error message, red border and `aria-invalid` |

The `submit` handler in full:

```javascript
form.addEventListener("submit", function (event) {
    event.preventDefault();          /* nothing is sent - no server yet */
    formWasSubmitted = true;

    const errorCount = checkWholeForm();

    if (errorCount > 0) {
        setFormStatus(errorCount + " field(s) need attention. " +
                      "Please correct the highlighted entries and " +
                      "submit the form again.", false);
        focusFirstError();
        return;                      /* stop: the form is NOT accepted */
    }

    const farmer = readFormValues();

    registerFarmer(farmer);

    /* form.reset() empties every box AND fires the reset event,
       so the success message is written after this line. */
    form.reset();
    formWasSubmitted = false;
    setFormStatus(successText(farmer), true);
});
```

### `event.preventDefault()`

A `<form>` with a submit button normally makes the browser send its data to the address in the
`action` attribute (or reload the current page). `preventDefault()` cancels that default action,
so the page does not reload and nothing leaves the browser. It is **required** here: the database
is Assignment 13, so there is nothing to send to yet.

### The `input` and `blur` events

```javascript
input.addEventListener("blur", function () {
    checkField(rule);
});

input.addEventListener("input", function () {
    const isShowingError = input.classList.contains("field-error");

    if (formWasSubmitted || isShowingError) {
        checkField(rule);
    }
});
```

`blur` always checks, so a message appears as soon as the farmer moves on. `input` fires on every
keystroke, so checking on every keystroke would shout at the user while they are still typing
"Rameshâ€¦". Therefore `input` only re-checks when the box is **already** in the error state â€” then
the red message disappears the moment the value becomes acceptable.

### The `reset` event

`reset` fires both when the **Clear Form** button is pressed and when JavaScript calls
`form.reset()` after a successful registration. The handler calls `clearAllFieldErrors()`, which
removes the `.field-error` class, the `aria-invalid` attribute and the message text of all six
fields. That is why the success message is written *after* `form.reset()` in the submit handler â€”
otherwise the reset handler would run last and the farmer would never see it.

## A7.7 The validation functions (logic only)

Each function takes **one value** and returns **a message**: `""` means acceptable, any text means
rejected. None of them touches the page â€” that is what makes them readable, reusable and easy to
test.

```javascript
function validateMobile(value) {
    const mobile = value.replace(/\s/g, "");     /* ignore typed spaces */

    if (mobile === "") {
        return "Please enter the mobile number.";
    }

    if (!/^\d+$/.test(mobile)) {                  /* \d = one digit */
        return "Mobile number must contain digits only.";
    }

    if (mobile.length !== 10) {
        return "Mobile number must be exactly 10 digits.";
    }

    if (!/^[6-9]/.test(mobile)) {
        return "Indian mobile number must start with 6, 7, 8 or 9.";
    }

    return "";                                    /* accepted */
}
```

The complete rule table:

| Field | Conditions checked, in order | Messages |
|---|---|---|
| Farmer Name | empty â†’ shorter than 3 â†’ wrong characters | "Please enter the farmer name." / "Farmer name must be at least 3 characters long." / "Farmer name can contain letters, spaces, . ' and - only." |
| Mobile | empty â†’ not digits â†’ not 10 digits â†’ wrong first digit | "Please enter the mobile number." / "â€¦digits only." / "â€¦exactly 10 digits." / "â€¦start with 6, 7, 8 or 9." |
| Email | empty â†’ wrong shape | *(nothing when empty)* / "Enter a valid email address, for example name@example.com." |
| Village | empty â†’ shorter than 3 | "Please enter the village or address." / "â€¦at least 3 characters long." |
| Milk Quantity | empty â†’ not a number â†’ 0 or less â†’ above 100 | "Please enter the milk quantity in litres." / "Milk quantity must be a number." / "â€¦greater than 0 litres." / "â€¦cannot be more than 100 litres a day." |
| Fat % | empty â†’ not a number â†’ below 3 â†’ above 8 | "Please enter the fat percentage." / "Fat percentage must be a number." / "Fat percentage cannot be below 3%." / "Fat percentage cannot be above 8%." |

Two more rules used after validation passes:

- `rateForFat(fat)` â€” returns `42` for fat 3.5% and above, `40` below it. It is the same rate
  board that is printed on the fixed `.rate-ticker` of `collection-centre.html`.
- `readFormValues()` â€” builds one object from the six boxes. **That object is the row that
  Assignment 13 will store in MySQL.**

## A7.8 The interface functions (page only)

```javascript
function checkField(rule) {
    const input = document.getElementById(rule.inputId);

    if (!input) {
        return true;                    /* field not on this page */
    }

    const message = rule.check(input.value);   /* the LOGIC decides */

    if (message === "") {
        clearFieldError(rule);
        return true;
    }

    showFieldError(rule, message);             /* the PAGE shows it */
    return false;
}
```

This is the bridge between the two halves of the code: the check function decides, `checkField()`
shows the result on the page.

| Function | What it does |
|---|---|
| `showFieldError(rule, message)` | adds the class `field-error` to the input, sets `aria-invalid="true"`, writes the message with `textContent` |
| `clearFieldError(rule)` | removes the class, the attribute and the message |
| `clearAllFieldErrors()` | runs the above for all six rules â€” used by `reset` |
| `checkWholeForm()` | `forEach()` over the rules; returns **how many** fields failed |
| `focusFirstError()` | `for` loop over the rules; `focus()` on the first box that still has the error class |
| `setFormStatus(message, isSuccess)` | writes the single message box and adds the class `ok` or `error` |
| `renderRegisteredFarmers()` | clears the table, draws one `<tr>` per farmer with `forEach()`, updates the count line with `reduce()` |
| `registerFarmer(farmer)` | `registeredFarmers.push(farmer)` + `renderRegisteredFarmers()` |
| `startFarmerForm()` | connects the four events; returns at once if the form is not on the page |

`textContent` is used everywhere a message is written, never `innerHTML`. That is the same rule
followed in Assignment 6: whatever the farmer typed can never be treated as HTML.

## A7.9 What a successful submission does

For the values *Ramesh Patil / 9876543210 / ramesh@example.com / Wadgaon / 18.5 / 4.6*:

1. `checkWholeForm()` returns `0`, so nothing is refused.
2. `readFormValues()` builds
   `{ name: "Ramesh Patil", mobile: "9876543210", email: "ramesh@example.com", village: "Wadgaon", quantity: 18.5, fat: 4.6, rate: 42, amount: 777 }`
   (18.5 Ã— 42 = 777 â€” the rate comes from `rateForFat(4.6)`).
3. `push()` adds it to `registeredFarmers`, and the table gets one row.
4. `form.reset()` empties the six boxes and clears the error styling.
5. The message box turns **green** and reads:
   *"Registration successful. Ramesh Patil of Wadgaon - 18.5 litres at 4.6% fat = â‚¹ 777.00 at 42 Rs
   per litre. Mobile 9876543210. Email ramesh@example.com."*
6. The count line reads *"1 farmer registered in this session - 18.5 litres a day between them."*

Register a second farmer and the count line becomes *"2 farmers registered in this session - 37.0
litres a day between them."* â€” that number is `reduce()` over the array.

## A7.10 How HTML5 and JavaScript work together here

| Layer | Job | Example |
|---|---|---|
| HTML attributes | stop obviously wrong input **before** JavaScript runs, and describe the field to the browser, to screen readers and to autofill | `type="email"`, `required`, `maxlength="10"`, `min="3" max="8"` |
| `novalidate` | switches off the browser's own bubbles so the project's messages are the visible ones | `<form ... novalidate>` |
| JavaScript | check every rule, write the message under the right field, decide accept or refuse | `checkWholeForm()` |

The two layers deliberately repeat the same limits. The HTML attribute gives instant, free
checking while typing; the JavaScript gives the explanation, marks the exact box, counts the
mistakes and controls the success path.

## A7.11 New CSS added for Assignment 7

Appended at the very end of `frontend/css/style.css` in an `ASSIGNMENT 7` block. No rule above it
was changed, so Assignments 1â€“6 look identical.

| Selector | Purpose |
|---|---|
| `.farmer-form` | space above the form |
| `.form-grid` | `display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px 22px` â€” two columns on a desktop, one column under 600px (the same Grid idea as `.stats-grid` in Assignment 4) |
| `.form-row label` | bold brown label on its own line |
| `.form-row input` | full-width padded box with a gold `outline` on `:focus` |
| `.field-hint` | small grey explanation under a field |
| `input.field-error` | **error state** â€” `border: 2px solid #b03a2e` on a pale red background |
| `.error-message` | the red message under the field, with `min-height` so the rows stay aligned |
| `.required-mark` | the red `*` beside a compulsory label |
| `.form-message.ok` | **success state** â€” green left border and background on the message box |
| `.form-message.error` | **failure state** â€” red left border and background on the same box |
| `.form-buttons`, `.btn-light` | the two buttons; `.btn-light` is the lighter "Clear Form" button |
| `@media (max-width: 600px)` | `.form-grid` becomes one column and the buttons go full width |

The success and failure states of the message box reuse `.register-status` from Assignment 6 and
only add the two colours â€” the project keeps one visual language.

## A7.12 Viva questions and answers â€” Assignment 7

**1. Why did you make a new `farmers.html` page instead of putting the form on the dashboard?**
The menu of `index.html` and `dashboard.html` already had a "Farmers" link pointing to
`pages/farmers.html`, which was a placeholder that gave a file-not-found error. Farmer
registration is a real screen of the society, so it belongs on its own page â€” and now the link
works instead of being broken.

**2. Why is there no `action` attribute on the form?**
There is nothing to send to yet. Sending the data to a server is Assignment 12 (PHP) and
Assignment 13 (PHP + MySQL). The `submit` event is cancelled with `preventDefault()`, so the
record stays in the browser.

**3. What does `event.preventDefault()` do?**
It cancels the browser's default action for that event. For a `submit` event the default action
is sending the form data to the `action` address and reloading the page. Without it the page
would reload and the typed values would be lost.

**4. Why is `novalidate` on the form?**
So the browser's own pop-up messages do not appear. With `required` attributes and no
`novalidate`, the browser refuses to submit and **the `submit` event never fires**, so our own
messages under the fields would never be written. The HTML5 attributes are still there; they
describe the field and give the mobile keypad, they just no longer block the submit.

**5. What is the difference between `required` in HTML and your own JavaScript check?**
`required` only answers "is it empty?" and shows the browser's own message. JavaScript checks the
full rule â€” a 10-digit number starting with 6-9, a number between 3 and 8, letters only in the
name â€” and writes the message in the project's own words, in the right place, and counts how many
fields are wrong.

**6. Why is the email field not `required`?**
Not every farmer in a village has an email address, so forcing one would stop a valid
registration. The JavaScript still checks it **if** something is typed: an empty field returns `""`
(accepted), and `ramesh@` returns an error. Optional-but-validated.

**7. How does the code know which error message belongs to which field?**
Through the `formRules` list. Each entry holds the `inputId`, the `errorId` and the check
function, so `checkField()` never has to guess: it reads that rule's input and writes to that
rule's message box.

**8. Why is the validation logic separated from the interface code?**
The check functions only receive a value and return a message â€” they know nothing about the page.
The interface functions only display what the checks decided. Because of that split, a rule can be
read, changed or reused on its own, and the same logic could later be reused by PHP without
touching the interface.

**9. Why not use `alert()` for the messages?**
An `alert()` is a browser dialog: it covers the page, it cannot be styled, a screen reader reads it
awkwardly, and it does not point at the box that is wrong. Writing the message into a `<p>` under
the field keeps the farmer looking at the form, and the red border shows which box to fix.

**10. What is `Number()` and `isNaN()` used for?**
`Number("18.5")` gives `18.5`, but `Number("abc")` gives `NaN` â€” "Not a Number". So
`if (isNaN(litres))` is how the code detects that a box does not hold a number, even though the
input is `type="number"`.

**11. Why does the `input` event not check the field on every keystroke?**
It would be noisy: after typing "R" the message "at least 3 characters" would appear while the
farmer is still typing "Ramesh". So `input` re-checks only when the box is already showing an
error â€” then the message vanishes the moment the value becomes correct. `blur` always checks.

**12. What does the `reset` event do, and why is it needed when `form.reset()` is also called?**
`form.reset()` **fires** the reset event, so one handler covers both the "Clear Form" button and
the emptying of the form after a successful registration. That is also why the success message is
written **after** `form.reset()` in the submit handler â€” otherwise the reset handler would run last
and overwrite the green message.

**13. How do you know how many fields are wrong?**
`checkWholeForm()` runs `checkField()` for every rule and counts the `false` answers, then returns
that count. The submit handler shows it: "5 field(s) need attention."

**14. How does the cursor land in the right box?**
`focusFirstError()` walks the rules from the top and calls `input.focus()` on the first box that
still carries the `field-error` class. The farmer starts correcting from the top of the form.

**15. What exactly happens when the form is valid?**
`readFormValues()` collects the values into one object, `push()` adds it to `registeredFarmers`,
`forEach()` draws one table row, `reduce()` recalculates the litres total in the count line, the
message box turns green with the farmer's details and the payment, and `form.reset()` empties the
boxes for the next farmer.

**16. Where is the data stored after a successful registration?**
In the `registeredFarmers` array inside `main.js`. It is a plain JavaScript array, so it is lost
when the page is closed â€” there is no database yet. The `readFormValues()` object has exactly the
shape of the row that Assignment 13 will insert into MySQL.

**17. Does adding this form break the Assignment 6 dashboard?**
No. `startFarmerForm()` starts with

```javascript
const form = document.getElementById("farmer-form");

if (!form) {
    return;
}
```

so on a page without the form it returns immediately, and the Assignment 6 code above it is
untouched.

**18. What is `aria-invalid` for?**
It tells assistive technology that the value in that box is not acceptable, so a screen reader can
announce the error. It is set with `setAttribute()` when a field is refused and removed with
`removeAttribute()` when it becomes correct.

**19. What is the difference between `textContent` and `innerHTML` here?**
`textContent` writes plain text â€” whatever the farmer typed stays text. `innerHTML` would parse
the string as HTML, so typed text containing tags could become part of the page. Messages and
table cells in this project use `textContent` or built HTML from data that was validated first.

**20. Why is the rate decided by `rateForFat()` instead of asking the farmer?**
Because the rate is the society's rule, not the farmer's choice â€” it is printed on the rate board
of the collection centre page (â‚¹42 for fat 3.5% and above, â‚¹40 below). Repeating it in JavaScript
shows the payment the farmer will actually receive, and the same function will be used when the
payment is really calculated in a later phase.

---

## 8. Important Code Concepts

Understand these before the viva; each one is used in the actual code.

**HTML**
1. `<!DOCTYPE html>`, `<html lang>`, `<head>`, `<body>` â€” the page skeleton.
2. `<meta charset>` and `<meta name="viewport">` â€” encoding and mobile behaviour.
3. `<h1>`â€“`<h3>` heading hierarchy and why only one `<h1>`.
4. `<p>` block paragraphs; source line breaks collapse into spaces.
5. `<ol>` vs `<ul>` and `<li>` â€” the single most-asked basic HTML question.
6. `<a href>` links, relative paths, `../` meaning "go up one folder".
7. `<img>` void element and its attributes `src`, `alt`, `width`, `height`.
8. HTML entities: `&amp;`, `&copy;`, `&nbsp;`, `&bull;`, `&deg;`, `&rarr;`, `&#8377;`.
9. Semantic tags: `header`, `nav`, `main`, `section`, `article`, `aside`, `footer`.
10. `<table>` with `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>` â€” and why only real tabular data
    should use a table.
11. Void vs container elements; `<span>` vs `<div>`.
12. Comments in HTML (`<!-- ... -->`) and how this project documents each assignment.

**CSS**
13. The three ways to attach CSS: external `<link>`, internal `<style>`, inline `style=""`.
14. Priority: external â†’ internal â†’ inline.
15. Selectors: element, descendant, class, class+class, universal, multiple selectors.
16. `.class` vs `#id` â€” and that this project uses **no ids at all**.
17. Specificity â€” class beats element, two classes beat one class (see the `.tank-card` note in
    section 11).
18. Inheritance â€” why `body` font and colour reach every element.
19. The box model: content â†’ padding â†’ border â†’ margin; `box-sizing: border-box`.
20. Shorthand properties: `margin`/`padding` (1â€“4 values), `border`, `border-radius`,
    `background`.
21. Colour formats: hex (`#fdf6ec`) and `rgba()` with alpha transparency.
22. Typography: `font-family` fallback stacks, `font-size`, `font-weight`, `text-align`,
    `letter-spacing`.
23. `display: inline-block` and why `padding` needs it on a link.
24. `:hover` pseudo-class and the three hover rules in this project.
25. `max-width` + `margin: auto` to centre a page column.
26. `overflow: hidden` vs `overflow: auto` and the `max-height` + scrollbar combination.
27. `opacity` vs `rgba()` transparency.

**Layout (Assignment 4)**
28. `display: flex` â€” container and items.
29. `justify-content` (main axis) vs `align-items` (cross axis); `align-items` defaults to
    `stretch`.
30. `flex-wrap: wrap`.
31. `flex: 1 1 200px` â€” grow, shrink, basis.
32. `gap` instead of manual child margins.
33. `display: grid`, `grid-template-columns`, `repeat()`, `1fr`, auto-placement.
34. Grid vs Flexbox â€” two-dimensional vs one-dimensional.
35. `@media (max-width: ...)` â€” what "at most this wide" means.
36. Desktop â†’ tablet â†’ mobile column counts (4 â†’ 2 â†’ 1).
37. Overriding: media queries come last so they win.

**Positioning (Assignment 5)**
38. `static` â€” the default; ignores offsets.
39. `relative` â€” stays in flow, anchors absolute children.
40. `absolute` â€” out of flow, measured from the nearest positioned ancestor.
41. `fixed` â€” out of flow, measured from the viewport.
42. `sticky` â€” in flow until `top` is reached, then held inside its parent.
43. `top` / `right` / `bottom` / `left`.
44. `z-index` and stacking order.
45. **relative parent â†’ absolute child** (section 6.7).
46. **fixed vs sticky** (section 6.8).
47. Percentage widths on an absolute child resolve against the positioned parent.
48. A sticky element must have an opaque background.
49. `overflow: hidden` on an ancestor silently breaks `sticky`.

**JavaScript (Assignment 6)**
50. An external `<script src="...">` file, and why the tag goes at the end of `<body>`.
51. `document.getElementById()` returning `null`, and why every element must be checked.
52. `addEventListener()` and the click / mouseover / mouseout / input / change events.
53. `forEach()`, `map()`, `filter()`, `find()`, `reduce()` â€” and what each one returns.
54. The `reduce()` accumulator and its initial value; why the `, 0` is needed.
55. `map()` + `reduce()` together to turn records into a total.
56. `textContent` vs `innerHTML` when inserting text safely.
57. `classList.add()` / `remove()` / `contains()` to switch styling from JavaScript.
58. `data-*` attributes, and why `data-shift` holds the machine value.
59. Closures â€” a row's click handler remembering its own record.
60. `DOMContentLoaded` as the safest moment to start.

---

## 9. Viva Questions and Answers

All answers below are based on code that actually exists in this project.

### 9.1 HTML (Assignment 1 and 2)

**1. What is the difference between `<ol>` and `<ul>`?**
`<ol>` is ordered â€” the browser numbers the items (1, 2, 3). `<ul>` is unordered â€” the browser
shows bullets. I used `<ol>` for the six steps of milk collection on `about.html` because the
order matters, and `<ul>` for the farmer services and the notice board because those items have
no sequence. Both use `<li>`.

**2. What does the `alt` attribute do?**
It is alternative text for an image. It is shown when the image fails to load and it is read
aloud by screen readers. All three images on `about.html` have it: "Dairy cow illustration",
"Dairy farm illustration", "Milk can illustration".

**3. Why is `<meta name="viewport" content="width=device-width, initial-scale=1.0">` needed?**
It tells the browser to use the real screen width as the CSS viewport width. Without it a phone
pretends the page is 980px wide, so none of my `@media (max-width: 600px)` rules would ever
apply and the responsive layout would never be tested.

**4. What is a void element? Give two examples from my project.**
An element with no closing tag that cannot contain other elements. Examples: `<img>` on
`about.html` and `<link>` in every `<head>`. Also `<meta>` and `<br>`.

**5. Why is there only one `<h1>` on a page?**
`<h1>` is the page's main title and the top of the heading outline. Screen-reader users navigate
by heading level, so more than one `<h1>` makes the structure confusing. On `about.html` there is
one `<h1>` ("About Dairy") and then `<h2>` for each section.

**6. What does the `../` in `../css/style.css` mean?**
"Go up one folder." `about.html` is inside `frontend/pages/`, so `..` brings it back to
`frontend/`, and then `css/style.css` finds the stylesheet. `index.html` is already in
`frontend/`, so it uses `css/style.css` without `../`.

**7. What is the difference between a hyperlink and a button?**
`<a href="...">` **navigates** to another page or URL. `<button>` **performs an action** like
submitting a form or running JavaScript. My "Learn More About Dairy" and "Back to Home" are
links to other pages, so they are `<a>` tags styled to look like buttons with the `.btn` class.

**8. What are `<thead>`, `<tbody>`, `<th>` and `<td>` for?**
They structure a data table. `<thead>` holds the header row, `<tbody>` holds the data rows,
`<th>` is a header cell and `<td>` is a data cell. I use them for the collection log on
`collection-centre.html` because that data really is tabular â€” farmer, quantity, fat %, SNF %,
rate and status.

**9. What are HTML entities and which ones did I use?**
They are special characters that cannot be typed directly or that need a code instead. I used
`&amp;` for &, `&copy;` for Â©, `&nbsp;` for a non-breaking space, `&bull;` for â€¢, `&deg;` for Â°,
`&rarr;` for â†’, and `&#8377;` for the Rupee symbol â‚¹.

**10. Why is `<!DOCTYPE html>` the first line?**
It tells the browser to render the page in modern standards mode instead of an old
compatibility mode. In quirks mode some CSS behaves differently, so it is always written first.

**11. Why is `<header>` used instead of a plain `<div>`?**
Because `<header>` tells the browser and assistive technology that this is the page's
introductory banner â€” it becomes the `banner` landmark. `<div>` says nothing about meaning.

**12. When do you use `<article>` instead of `<section>`?**
`<article>` is for content that is **independent and self-contained** â€” it would still make
sense if quoted on its own. On `index.html` each farmer's collection record is an `<article>`,
because that record stands alone. `<section>` groups related content under one heading.

**13. How do you know which page you are on?**
Each page puts `class="active"` on its own menu link â€” `about.html` line 39,
`dashboard.html` line 48, `collection-centre.html` line 58 â€” and `style.css` line 99
(`nav a.active`) highlights it in gold.

### 9.2 Semantic HTML (Assignment 2)

**14. Name the seven semantic tags I used and what each one is for.**
`<header>` page banner, `<nav>` main navigation links, `<main>` the page's primary content
(one per page), `<section>` a thematic group with its own heading, `<article>` an independent
piece of content, `<aside>` related but secondary content such as notices, `<footer>` closing
information like contact and copyright.

**15. Where is the `<aside>` in my project and why is it there?**
Three places: the Notice Board on `index.html` line 180, an explanation of the tools used on
`dashboard.html` line 129, and Centre Instructions on `collection-centre.html` line 222. It is
complementary information â€” not the main content â€” so `<aside>` is correct.

**16. Why does my about page have no `<header>`, `<main>` or `<footer>`?**
`about.html` is the Assignment 1 basic-HTML demonstration, kept deliberately plain so the basic
tags are not mixed up with the semantic layout. The other three pages use the full semantic
structure.

### 9.3 CSS (Assignment 3)

**17. What are the three ways of adding CSS, and which one wins?**
External (`<link rel="stylesheet">` to `style.css`), internal (a `<style>` block in the page's
`<head>`), and inline (`style="..."` on a tag). Priority is external â†’ internal â†’ inline, so
inline wins. My `index.html` uses all three: `style.css` is external, lines 36â€“50 are internal,
and lines 95 and 142 are inline.

**18. What does `* { box-sizing: border-box; }` do and why is it needed?**
Normally `width` means the content width only, so a 260px box with 20px padding becomes 300px
wide and overflows. `border-box` makes `width` include padding and border. I need it so my grid
cards and flex blocks fit inside their columns.

**19. Difference between margin and padding?**
Margin is the space **outside** the border and between elements; padding is the space **inside**
the border, and the background colour fills it. In `.centre-note` I use both: `margin: 12px 0 4px 0`
outside and `padding: 10px 14px` inside.

**20. How do you centre a block horizontally in CSS?**
Give it a `max-width` and `margin-left`/`margin-right` of `auto`. My `main` rule is
`max-width: 1100px; margin: 20px auto;`.

**21. What does `border: 2px dashed #d9a441` mean?**
Border width `2px`, border style `dashed`, border colour `#d9a441`. It is the aside rule in the
internal `<style>` of `index.html` â€” the only dashed border in the project.

**22. What does `border-radius: 999px` create?**
A fully rounded shape. On `.status-badge`, which is small, it produces a "pill" shape.

**23. Explain `box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3)`.**
Four values: 0 horizontally, 8px down, 20px blur, black at 30% opacity. I use it on
`.rate-ticker` so the rate board looks like it is floating above the page.

**24. What is `:hover`?**
A pseudo-class that applies styles only while the mouse pointer is over the element. I have
three: `nav a:hover` (gold pill behind a menu link), `.btn:hover` (button turns lighter) and
`a:hover` (ordinary link text darkens).

**25. Why does `.btn` need `display: inline-block`?**
A plain `<a>` is an inline element, and padding and height do not work correctly on inline
elements. `inline-block` lets the link have padding while still sitting in the line of text.

**26. What is the difference between a class and an id?**
A class can be used on any number of elements; an id must be unique on the page and has higher
priority. My project uses only classes â€” `.btn`, `.active`, `.stats-grid`, `.status-badge` â€” and
no ids at all, because I need the same styling on many elements.

**27. How does CSS get from `style.css` to a `<div>` in the HTML?**
Through the class name. The HTML says `<div class="stats-grid">` and the CSS says
`.stats-grid { display: grid; ... }`. The browser finds every element whose class list contains
`stats-grid` and applies those properties.

**28. What is specificity, and where did it bite me in this project?**
Specificity decides which rule wins when two rules target the same property. More classes beat
fewer classes. In `collection-centre.html` a `.tank-card` card has both `stat-card` and
`tank-card` classes. `.stats-grid .stat-card { padding: 18px; }` has two class selectors, so it
beats `.tank-card { padding-top: 26px; }` which has only one â€” so the card's top padding stays
18px and the badge overlaps the heading slightly. I know about this and will fix it.

### 9.4 Flexbox (Assignment 4)

**29. What does `display: flex` do?**
It turns the element into a **flex container** and its direct children into **flex items**,
which are laid out in a row by default.

**30. Where do I use Flexbox?**
In the navigation menu (`nav ul` with `display: flex`) which is on all four pages, and in the
shift summary strips (`.flex-row` with `display: flex` and three `.flex-item` children) on the
dashboard and collection-centre pages.

**31. What does `justify-content: center` do, and which axis does it work on?**
It centres the items along the **main axis**. In my nav the main axis is horizontal because I did
not set `flex-direction`, so it defaults to `row` â€” the menu is centred on the brown bar.

**32. What does `flex: 1 1 200px` mean?**
`flex-grow: 1`, `flex-shrink: 1`, `flex-basis: 200px`. Each block asks for 200px, then all three
share the extra space equally, so the three shift blocks always have the same width without any
width in the HTML.

**33. What does `flex-wrap: wrap` do?**
If the items do not fit on one line, they move down to a new line instead of being squashed. I
use it on `nav ul` so the menu stays usable in a narrow window, and on `.flex-row` so the blocks
reflow.

**34. Why are the three `.flex-item` blocks the same height?**
Because I did not set `align-items`, and its default value is `stretch`, which stretches every
item to the height of the tallest one.

### 9.5 CSS Grid (Assignment 4)

**35. What does `display: grid` do?**
It turns the element into a **grid container** and its children into **grid items** placed into
grid cells automatically.

**36. What does `grid-template-columns: repeat(4, 1fr)` mean?**
Four equal columns. `1fr` is one fraction of the available width, and `repeat(4, ...)` is
shorthand for `1fr 1fr 1fr 1fr`.

**37. Where is the grid used?**
In `.stats-grid` â€” the four stat cards on `dashboard.html` (Total Farmers, Today's Milk
Collection, Pending Payments, Average Fat) and the four tank/equipment cards on
`collection-centre.html`.

**38. What does `gap: 20px` do in a grid?**
It puts 20px of space between the rows **and** between the columns.

**39. Do I have to position each card in the grid?**
No. Grid **auto-placement** fills cells left to right and top to bottom automatically. I never
write coordinates for a card â€” adding a fifth card would need no CSS change.

**40. When would you use Grid instead of Flexbox?**
Grid is two-dimensional â€” it controls rows and columns at once â€” so I use it for the stat card
area where the number of columns must change with the screen. Flexbox is one-dimensional, so I
use it for the menu and the shift blocks, which are just a single row of similar items.

### 9.6 Media Queries (Assignment 4 and 5)

**41. What is a media query?**
A block of CSS that is applied only when a condition matches. I use
`@media (max-width: 900px)` and `@media (max-width: 600px)`.

**42. What does `max-width` mean in a media query?**
"Apply these rules only if the screen is at most this wide." It reacts to the window size, not to
a device name, so I can test by dragging the Chrome window.

**43. What changes at 900px?**
`.stats-grid` drops from 4 columns to 2 columns (`repeat(2, 1fr)`), and `.photo-frame` changes
from a fixed `width: 260px` to `width: 100%`.

**44. What changes at 600px?**
The grid goes to one column; `.flex-row` becomes `flex-direction: column` so the three blocks
stack; `nav ul` becomes `flex-direction: column` with a `gap` so the menu stacks; `.stat-number`
shrinks from 42px to 32px; the nav becomes `position: static`; `.rate-ticker` becomes a
full-width bottom strip; `.log-window` shrinks to 200px.

**45. Why are the media queries at the bottom of the file?**
CSS is read top to bottom and, when two rules have the same specificity, the later one wins. So
the media queries must come after the base rules to override them.

**46. How do you test a responsive page?**
Open it and drag the window width slowly from wide to narrow. At 900px and at 600px the layout
changes instantly without a reload. Chrome's device toolbar (Ctrl + Shift + M) lets me set exact
widths such as 1400px, 800px and 400px.

### 9.7 CSS Positioning (Assignment 5)

**47. Name the five `position` values and give one example of each from my project.**
`static` â€” `.centre-note`, the shift information strips. `relative` â€” `.tank-card` and
`.photo-frame`. `absolute` â€” `.status-badge` and `.photo-caption`. `fixed` â€” `.rate-ticker`,
the milk rate board. `sticky` â€” `nav` and the collection log's `thead th`.

**48. What is the default value of `position` and what does it do?**
`static` is the default. A static element sits in the normal page flow where the HTML puts it and
ignores `top`, `right`, `bottom` and `left`. My `.centre-note` sets it explicitly to show the
default; removing it changes nothing.

**49. Why is `.tank-card` `position: relative`?**
Because the `.status-badge` inside it is `position: absolute`. An absolutely positioned element
measures itself from the nearest positioned ancestor, so the card must be `relative` for the
badge to sit in that card's top-right corner rather than at the top-right of the whole page.

**50. What happens to the badge if I remove `position: relative` from `.tank-card`?**
The badge loses its positioned ancestor and jumps to the next positioned ancestor, which is
ultimately the page itself. All four badges would pile up in one corner of the screen instead of
sitting on their own cards.

**51. What happens to the badge if I remove `position: absolute`?**
It returns to the normal flow and becomes the first inline element inside the card, appearing
before the `<h3>` and pushing the heading down instead of floating in the corner.

**52. What is `position: fixed` used for in my project?**
The `.rate-ticker` milk rate board. It is `position: fixed` with `left: 20px; bottom: 20px`, so
it stays in the bottom-left corner of the **screen** while the staff scroll the long collection
log. Without it the rate would scroll away.

**53. Why did I add `padding-bottom: 170px` to the footer?**
Because a fixed box covers whatever is under it. `body.page-centre footer { padding-bottom: 170px; }`
reserves empty space so the rate board never hides the footer text. I put the rule under
`body.page-centre` so it applies only to the collection-centre page and the other pages keep their
original look.

**54. What is the difference between `fixed` and `sticky`?**
`sticky` stays in the normal flow, keeps its space, and only sticks **after** it reaches its
`top` offset; it also stops sticking at the bottom of its parent. `fixed` is removed from the
flow entirely and is measured against the viewport, so it is pinned to the screen permanently and
ignores the document. My `nav` is sticky and my `.rate-ticker` is fixed.

**55. Why did I turn sticky off on phones?**
On screens 600px and below the menu becomes a tall vertical stack, and a sticky tall menu would
fill most of the phone screen. So inside `@media (max-width: 600px)` I set `nav { position: static; }`
and the menu scrolls away normally.

**56. What are the `top`, `right`, `bottom` and `left` values in your project?**
`nav { top: 0 }` for the sticky trigger. `.status-badge { top: 8px; right: 8px }` for the card
corner. `.photo-caption { left: 0; bottom: 0 }` for the caption strip. `.rate-ticker {
left: 20px; bottom: 20px }`, changed to `left: 0; right: 0; bottom: 0` on mobile.
`.log-window thead th { top: 0 }` for the sticky column titles.

**57. What is `z-index` and what values do you use?**
It is the stacking order â€” a higher number paints on top, and it only works on positioned
elements. My values are: `2` for the sticky table header, `3` for the status badge, `50` for the
sticky nav, and `100` for the fixed rate board.

**58. What happens if I set the rate board's `z-index` to 1?**
It drops below the sticky nav (50) and below the badges, so when you scroll, the menu and cards
slide in front of the rate board. That is the easiest way to demonstrate `z-index` in a viva.

**59. What is `overflow` and where do you use it?**
`overflow` controls what happens to content that is too big for a box. I use `overflow: hidden`
on `.tank-card`, `.photo-frame` and `.rate-ticker` to clip content inside the rounded corners, and
`overflow: auto` on `.log-window` so the 19-row table gets its own scrollbar instead of making
the page endless.

**60. Why does `.log-window` need both `max-height` and `overflow: auto`?**
`max-height: 230px` limits how tall the box can grow, and `overflow: auto` shows a scrollbar only
when the content does not fit. Without the `max-height` the box would simply grow and no
scrollbar would ever appear.

**61. Where do you use `opacity`?**
On `.tank-fill`, the gold bar inside the tank gauge, with `opacity: 0.85`, so the bar looks
slightly translucent. The photo caption uses the other technique, `rgba(75, 46, 14, 0.7)`, which
makes only that background colour transparent.

**62. What is the difference between `opacity: 0.5` and `rgba(0, 0, 0, 0.5)`?**
`opacity` applies to the element **and all its children**, so any text inside also fades.
`rgba()` only changes that one colour, and the text stays fully visible.

**63. How are the tank levels shown without JavaScript?**
By width percentage classes. `.tank-gauge` is a 20px tall bar and `.tank-fill` fills it, and the
level comes from a class â€” `.gauge-92` is `width: 92%`, `.gauge-74` is 74%, `.gauge-58` is 58%
and `.gauge-35` is 35%` (lines 421â€“424).

**64. Why is the sticky table header's `background-color` compulsory?**
Because a sticky element stays on screen while the content scrolls underneath it. If the
background were transparent you would see the farmer rows through the header text. That is why
`.log-window thead th` has a solid `background-color: #6b4226`.

**65. What breaks if you put `overflow: hidden` on `<body>`?**
Every `position: sticky` element inside it silently stops working, including my navigation menu,
because a sticky element cannot stick inside a box that does not scroll. I have not declared
`overflow` on `body` or `html`, which is why the menu sticks correctly.

**66. Why does a percentage width on `.photo-caption` work?**
`width: 100%` on an absolutely positioned element resolves against its **containing block**,
which is the nearest positioned ancestor â€” `.photo-frame`. So the caption spans the frame's
padding box, not the page.

---

## 10. Practical Changes I Can Try

Make these yourself, one at a time, and observe the result in Chrome. Revert each change after
you have seen the effect.

| # | File | Selector / line | Change | Expected result |
|---|---|---|---|---|
| 1 | `frontend/css/style.css` | line 28 â€” `body { background-color: #fdf6ec; }` | change to `#eaf7ff` | The cream theme becomes pale blue on **all four pages** at once â€” proves one external stylesheet styles the whole project. |
| 2 | `frontend/css/style.css` | line 36 â€” `header { background-color: #e8b84b; }` | change to `#2f8f4e` | The gold title bar becomes green on every page that has a `<header>`. |
| 3 | `frontend/css/style.css` | line 222 â€” `.stats-grid { grid-template-columns: repeat(4, 1fr); }` | change to `repeat(3, 1fr)` | Desktop shows 3 cards in the first row and the 4th card wraps below. Notice `collection-centre.html` changes too, because it reuses `.stats-grid`. |
| 4 | `frontend/css/style.css` | line 292 â€” `@media (max-width: 900px)` | change `900px` to `1100px` | The 2-column tablet layout now appears **earlier**. Resize slowly and watch the exact width where the cards re-arrange. |
| 5 | `frontend/css/style.css` | line 71 â€” `nav ul { justify-content: center; }` | change to `flex-start` | The menu slides to the left edge of the brown bar. Then try `space-between` â€” the links spread out to fill the full width. |
| 6 | `frontend/css/style.css` | lines 345â€“346 â€” `nav { position: sticky; top: 0; }` | change `top: 0` to `top: 100px` | The menu parks 100px down and you can see page content scrolling in the gap above it â€” proves `top` is the trigger offset, not a distance it moves. |
| 7 | `frontend/css/style.css` | lines 378â€“379 â€” `.status-badge { top: 8px; right: 8px; }` | change to `top: 50px; right: 20px` | Badges move down and inward on all four tank cards. Then try `bottom: 8px; left: 8px` â€” they move to the bottom-left corner. |
| 8 | `frontend/css/style.css` | line 506 â€” `.rate-ticker { z-index: 100; }` | change to `z-index: 1` | Scroll the page: the rate board slides **behind** the sticky menu and behind the cards. The clearest `z-index` demonstration in the project. |
| 9 | `frontend/css/style.css` | lines 504â€“505 â€” `.rate-ticker { left: 20px; bottom: 20px; }` | change to `right: 20px; bottom: 20px` | The board jumps from the bottom-left to the bottom-right of the screen â€” shows that a fixed element is measured from the screen, not from the page flow. |
| 10 | `frontend/css/style.css` | line 424 â€” `.gauge-35 { width: 35%; }` | change to `width: 80%;` | On `collection-centre.html` the Payment Counter tank bar fills to 80% while its text still reads â‚¹ 52,400 â€” the class **is** the data. |
| 11 | `frontend/css/style.css` | line 355 â€” `.centre-note { position: static; }` | delete the line | **Nothing changes.** This proves `static` is the default and that static elements ignore offsets. Good viva evidence. |
| 12 | `frontend/css/style.css` | line 369 â€” `.tank-card { position: relative; }` | delete the line | All four status badges fly to the top-right corner of the page, away from their cards â€” proves the relative-parent / absolute-child relationship. Put it back afterwards. |
| 13 | `frontend/css/style.css` | line 345 â€” `nav { position: sticky; }` | delete the line | The menu bar scrolls off the top of `collection-centre.html` and can no longer be reached while scrolling. |
| 14 | `frontend/css/style.css` | line 239 â€” `.stat-number { font-size: 42px; }` | change to `font-size: 24px` | The big numbers on `dashboard.html` and `collection-centre.html` shrink on desktop; the media query still forces 32px below 600px, showing which rule wins. |
| 15 | `frontend/index.html` | line 44 â€” `border: 2px dashed #d9a441;` (inside the internal `<style>`) | change to `border: 2px solid #d9a441;` | The Notice Board loses its dashed edge **on the home page only** â€” demonstrates the difference between internal CSS and external CSS. |
| 16 | `frontend/css/style.css` | line 314 â€” inside `@media (max-width: 600px)`, `nav ul { gap: 6px; }` | change to `gap: 20px` | The stacked mobile menu becomes more spread out. Shows how a media query can change a value without touching the desktop rule. |

Bonus checks (read-only, no changes): open the same page at 1400px, 800px and 400px and note
every layout change; then use Chrome DevTools â†’ Computed to confirm the specificity of
`.stats-grid .stat-card` versus `.tank-card`.

### JavaScript experiments (Assignment 6)

All of these are done on `frontend/pages/dashboard.html` with `frontend/js/main.js`. Revert each
change afterwards.

| # | File | Selector / line | Change | Expected result |
|---|---|---|---|---|
| 17 | `frontend/js/main.js` | the `collections` array | set one `litres` value to `0` | The total litres, the total payment and the average all drop instantly â€” proves `reduce()` is recalculating, not showing a hard-coded number. |
| 18 | `frontend/js/main.js` | the `collections` array | change one `fat` value to `3.1` | The Low Fat Cans counter goes from 0 to 1 and that card turns into the highlighted red state â€” proves the `reduce()` condition is working. |
| 19 | `frontend/js/main.js` | `#collection-filter` handler | change the `value >= 15` to `value >= 20` | The 15+ filter now behaves like the 20+ filter and shows only 3 rows. |
| 20 | `frontend/js/main.js` | `applyFilters()` | comment out the `shift` check | Morning-only filtering stops working, so a Morning block shows Evening rows too â€” proves both conditions are combined with `&&`. |
| 21 | `frontend/js/main.js` | `selectRow()` | remove the `mouseover` listener block | Rows no longer highlight on hover â€” proves which listener causes that behaviour. |
| 22 | `frontend/js/main.js` | the `<script>` line position in `dashboard.html` | move the `<script>` tag into `<head>` | The status line shows "Loadingâ€¦" and never updates â€” proves why the script belongs at the end of `<body>`. |
| 23 | `frontend/pages/dashboard.html` | `#farmer-search` input | type `an` | Only the rows containing "an" in the farmer name stay â€” proves `includes()` is doing a case-sensitive substring match. |
| 24 | `frontend/js/main.js` | `showRecords()` | change `textContent` to `innerHTML` for the status | Still works here, but it is the unsafe version â€” good contrast for a viva answer about injection. |

### Form experiments (Assignment 7)

All of these are done on `frontend/pages/farmers.html` with `frontend/js/main.js`. Revert each
change afterwards.

| # | File | What to change | Expected result |
|---|---|---|---|
| 25 | `frontend/pages/farmers.html` | delete `novalidate` from the `<form>` | The browser takes over: submitting an empty form shows the browser's own bubble and **none** of our red messages under the fields appear â€” the clearest proof of why `novalidate` is needed. |
| 26 | `frontend/js/main.js` | in `validateFatPercentage()`, change `fat > 8` to `fat > 10` | A fat of 9 is now accepted and the table shows it â€” proves which condition produced the message. |
| 27 | `frontend/js/main.js` | in `validateMobile()`, delete the `/^[6-9]/` check | "5123456789" is accepted â€” proves the first-digit rule is a separate condition, not part of the digit check. |
| 28 | `frontend/js/main.js` | in `validateEmail()`, remove the `if (email === "")` return | Leaving the email blank now shows an error â€” shows how the optional rule is written. |
| 29 | `frontend/js/main.js` | comment out `event.preventDefault();` | The page reloads on submit and the green message is lost â€” proves exactly what `preventDefault()` stops. |
| 30 | `frontend/js/main.js` | in the `input` handler, delete `isShowingError` from the condition | Typing shows the error on the very first keystroke ("at least 3 characters" while typing "Ramesh") â€” shows why the check is limited. |
| 31 | `frontend/js/main.js` | in `rateForFat()`, change `3.5` to `4.5` | A 4.6% fat farmer is now paid 40 Rs instead of 42 Rs â€” the rate board rule is the only place the rate lives. |
| 32 | `frontend/css/style.css` | `.form-grid { grid-template-columns: repeat(2, 1fr); }` â†’ `repeat(3, 1fr)` | Three columns on a desktop screen â€” the form is an ordinary CSS Grid, the same property as `.stats-grid`. |
| 33 | `frontend/css/style.css` | delete the `input.field-error` rule | The messages still appear under the fields, but the boxes are no longer red â€” separates the error message from the error state. |

---

## 11. Assignment Status

| Assignment | Status | Where it is implemented |
|---|---|---|
| Assignment 1 â€” Basic HTML (headings, paragraphs, ordered/unordered lists, images) | **Completed** | `frontend/pages/about.html` â€” `h1` line 47, `h2` lines 64/80/95, `p` lines 50/57/66/82/109, `<ol>` lines 70â€“77, `<ul>` lines 86â€“92, `<img>` lines 104â€“106 |
| Assignment 2 â€” Semantic HTML | **Completed** | `frontend/index.html` â€” `header` 58, `nav` 68, `main` 84, `section` 91/120/153, `article` 125â€“169, `aside` 180, `footer` 196 |
| Assignment 3 â€” CSS (external, internal, inline) | **Completed** | External: `frontend/css/style.css` (584 lines). Internal: `index.html` lines 36â€“50. Inline: `index.html` lines 95 and 142, `about.html` lines 104â€“106 |
| Assignment 4 â€” Responsive design (Flexbox, Grid, Media Queries) | **Completed** | Flexbox: `style.css` lines 68â€“80 and 256â€“283. Grid: lines 220â€“236. Media queries: lines 292â€“297, 300â€“321, 538â€“546, 549â€“583. Demo page: `frontend/pages/dashboard.html` |
| Assignment 5 â€” CSS positions and other properties | **Completed** | `frontend/pages/collection-centre.html` with `style.css` lines 323â€“584 â€” static 355, relative 369 & 466, absolute 377 & 487, fixed 503, sticky 345 & 455, plus z-index, top/right/bottom/left, width, height, margin, padding, border, border-radius, box-shadow, overflow, opacity |
| Assignment 6 â€” JavaScript events and array functions | **Completed** | `frontend/js/main.js` â€” `collections` array (8 records), `DOMContentLoaded` start, events: `change` / `input` / `click` / `mouseover` / `mouseout`, functions: `forEach()` / `map()` / `filter()` / `find()` / `reduce()`. UI in `frontend/pages/dashboard.html`; styles appended to `frontend/css/style.css` |
| Assignment 7 â€” JavaScript frontend functionality and form validation | **Completed** | `frontend/pages/farmers.html` â€” the Farmer Registration form (6 fields, `novalidate`, HTML5 attributes) + the "Farmers Registered in This Session" table. Logic in `frontend/js/main.js` section 6 â€” `formRules` list, checks `validateFarmerName()` / `validateMobile()` / `validateEmail()` / `validateVillage()` / `validateMilkQuantity()` / `validateFatPercentage()` / `rateForFat()`, interface `checkField()` / `checkWholeForm()` / `showFieldError()` / `clearFieldError()` / `clearAllFieldErrors()` / `focusFirstError()` / `setFormStatus()` / `readFormValues()` / `registerFarmer()` / `renderRegisteredFarmers()` / `successText()` / `startFarmerForm()`, events `submit` / `blur` / `input` / `reset`, `preventDefault()`. Styles appended to `frontend/css/style.css` |
| Assignment 8 â€” React components and JSX | **Completed** | `react-app/` (Vite + React). Components in `react-app/src/App.jsx`: Header, StatCard, Dashboard, FarmerCard, MilkCollectionCard, Footer, App. Entry point `react-app/src/main.jsx`: `createRoot(document.getElementById('root')).render(<App />)` |
| Assignment 9 â€” React props, state, hooks, events | **Completed** | `react-app/src/App.jsx` â€” 6 `useState` hooks (selected farmer, milk quantity, search text, show details, paid farmers, high-collection threshold), props passed to every child, `onClick` / `onChange` handlers, conditional rendering (high/normal badge, paid/pending badge, details block, "no farmer found") |
| Assignment 10 â€” Fetch API + JSON | **Completed** | `react-app/src/ApiFarmerList.jsx` â€” `useEffect()` + `fetch('https://jsonplaceholder.typicode.com/users')` + `response.json()` + `useState()`, `.map()` with `key={farmer.id}`, loading message, friendly error message + Retry button. Styles: `Assignment 10` block at the end of `react-app/src/App.css` |
| Assignment 11 - DOM manipulation and event handling | **Completed** | `frontend/pages/collection-centre.html` - the "Add a Collection Entry" form (`submit` event) + the tools of "Today's Collection Log" (`input`, `change`, `click` events) + the summary under the log. Code in `frontend/js/main.js` section 7 (from line 957): `getElementById()`, `querySelector()`, `querySelectorAll()`, `textContent`, `classList.add()/remove()`, `style.display / style.width / style.fontWeight`, `createElement()`, `appendChild()`, `remove()`, `setAttribute()`. Styles: `ASSIGNMENT 11` block at the end of `frontend/css/style.css` (from line 824) |

### 11.1 Known issues and gaps in Assignments 1â€“5

These are real, verified from the code. Being able to name them is a strength in a viva, not a
weakness.

1. **Specificity bug â€” `.tank-card`'s `padding-top` has no effect.**
   `style.css` line 234 has `.stats-grid .stat-card { padding: 18px; }` with **two** class
   selectors (specificity 0,2,0). Lines 371, 544 and 564 set `padding-top` on `.tank-card` with
   **one** class selector (0,1,0). The shorthand wins, so the top padding stays 18px at every
   screen size. **Visible effect:** the `.status-badge` (top 8px, about 19px tall) overlaps the
   `<h3>` heading of each tank card. A fix would be a more specific selector such as
   `.stats-grid .tank-card { padding-top: 26px; }`.
2. **One link still points to a page that does not exist.** `index.html` lines 74â€“75 and
   `dashboard.html` lines 50â€“51 link to `pages/farmers.html` and `pages/milk.html`.
   `pages/farmers.html` now exists (Assignment 7), so only `pages/milk.html` is still a
   placeholder for a future phase. `about.html` and `collection-centre.html` do not show the
   Farmers or Milk Collection links at all, so their menus are still shorter than the ones on
   `index.html` and `dashboard.html`.
3. **The navigation is not identical on all pages.** `index.html` and `dashboard.html` show six
   menu items; `about.html` and `collection-centre.html` show four. Also `index.html` does not
   put `class="active"` on its own Home link, so no menu item is highlighted on the home page.
4. **`<aside>` is styled on only one page.** The aside rules live in the internal `<style>` of
   `index.html` (lines 38â€“49). There is no `aside` rule in `style.css`, so the asides on
   `dashboard.html` line 129 and `collection-centre.html` line 222 render unstyled.
5. **`about.html` comments do not match its code.** Line 79 says "h3 is a sub-section heading"
   but line 80 is `<h2>`, and line 7 claims the page shows h1, h2 and h3 â€” there is no `<h3>` on
   the page.
6. **`AGENTS.md` is out of date.** Its "Current state (Phase 2)" block (lines 19â€“29) says
   `css/` is empty and lists only two pages, and line 49 calls `dashboard.html` a placeholder.
   Reality: five pages and a stylesheet of more than 800 lines. `docs/assignment-mapping.md` is
   the accurate document. To be updated when convenient â€” not required for correctness of the
   project.
7. **A comment in `style.css` is inaccurate.** Lines 330â€“331 list `.rate-ticker` under
   `position: relative`, but `.rate-ticker` is `position: fixed` (line 503) and its children are
   static â€” they are not placed against it.
8. **`style.css` is getting large.** It holds four assignments in one file with five separate
   media-query blocks, and `nav` is styled in both the Assignment 3 section (line 59) and the
   Assignment 5 section (line 344). Everything works, but splitting it into `base.css`,
   `responsive.css`, `positions.css` and `forms.css` would be cleaner.
9. **Minor:** `about.html` line 39 and `collection-centre.html` line 58 use redundant
   self-referencing paths (`../pages/about.html` instead of `about.html`). `about.html` shows the
   200Ã—180 SVGs at 160Ã—145, a slight squash. `.btn` and `nav a` have no `transition`, so hover
   changes are instant.
10. **Not a bug, but know the answer:** `position: static` on `.centre-note` is a deliberate no-op.
    Deleting it changes nothing, and that is the correct answer if you are asked.

### 11.2 Where Assignment 6 fits, and what came next

- `frontend/js/main.js` is the single external script. It is loaded by
  `frontend/pages/dashboard.html` and `frontend/pages/farmers.html`, both at the very end of
  `<body>`.
- The other three pages (`index.html`, `about.html`, `collection-centre.html`) do **not** load the
  script, so they are unaffected and show no errors.
- The script is written defensively: every element is fetched with `document.getElementById()` and
  checked for `null` before use, so if the same file is later added to another page that lacks some
  of the Assignment 6 elements, the page still loads without errors.
- Assignment 6 only **added** behaviour and UI to `dashboard.html`, plus new rules at the end of
  `style.css`. No Assignment 1â€“5 markup, class name, or existing rule was removed or changed, so
  the layout of all four pages is exactly as before.
- The known specificity bug in item 1 above was deliberately **not** fixed here, because that
  would change the existing Assignment 3â€“5 layout. New Assignment 6 rules avoid repeating the
  mistake by using selectors like `.stats-grid .stat-card.selected` instead of a bare
  `.stat-card.selected`.

### 11.3 Where Assignment 7 fits, and what is still not built

- `frontend/pages/farmers.html` is a new page, but it is not a throw-away demo page: it fills the
  `pages/farmers.html` link that the menu of `index.html` and `dashboard.html` already had as a
  placeholder, and it is a screen the society really needs (registering a milk producer).
- Assignment 7 **added** `main.js` section 6 and one call (`startFarmerForm()`) at the end of
  `startApp()`. Nothing in the Assignment 6 code above it was edited, and the Assignment 1â€“5
  markup and CSS rules were not touched either â€” the new CSS is appended in its own block at the
  end of `style.css`.
- `startFarmerForm()` returns immediately when `#farmer-form` is missing, so `dashboard.html`,
  `index.html`, `about.html` and `collection-centre.html` are unaffected and stay error-free.
- The static `frontend/` part still has **no** backend of its own: no `action` attribute, no PHP, no MySQL, no Node/Express. A registered farmer only lives in the `registeredFarmers` array until the page is closed, and the aside on the page says so. (The `fetch()` call of Assignment 10 lives in the separate `react-app/` project, not in `frontend/`.)
  file, no Node/Express. A registered farmer only lives in the `registeredFarmers` array until the
  page is closed, and the aside on the page says so.
- Assignments 8, 9 and 10 are implemented inside `react-app/` (see section A8 and the Assignment 10 section at the end of this file). Assignments 11-16 are still untouched: there is no `php/`, `node-backend/` or `database/` folder in the project, and the project has no server of its own.

---

*Document created after Assignment 5, then updated after Assignment 6 (JavaScript events and array
functions) and after Assignment 7 (JavaScript frontend functionality and form validation). Every
code sample above was copied from the real files in this project.


# Assignment 8 — React Components and JSX

## A8.1 ReactJS in this project
- ReactJS is a JavaScript library for building UIs with reusable components.
- Used to demonstrate component-based UI, JSX and functional components.
- Project location: react-app/ (Vite + React).

## A8.2 Project structure (actual)
```text
react-app/
├── index.html
├── package.json
├── vite.config.js
├── public/
└── src/
    ├── App.jsx      // Root component
    ├── App.css      // Styles
    ├── main.jsx     // Entry point
    ├── index.css    // Global styles
    └── assets/
```

## A8.3 JSX
- JSX = JavaScript XML.
- Examples: expressions {siteTitle}, {totalFarmers}, {currentYear}; className used.

## A8.4 Functional components created
| Component | Purpose | File |
|---|---|---|
| Header | Site title/tagline | App.jsx |
| Dashboard | Overview cards | App.jsx |
| FarmerCard | Farmer details | App.jsx |
| MilkCollectionCard | Collection details | App.jsx |
| Footer | Contact/copyright | App.jsx |
| App | Root component | App.jsx |

## A8.5 How App connects components
App renders Header, main (Dashboard + section with FarmerCard/MilkCollectionCard), Footer.

## A8.6 How JSX is rendered
src/main.jsx uses createRoot(document.getElementById('root')).render(<App />); index.html has <div id='root'></div>.

## A8.7 How to run the React application
cd react-app && npm run dev. Build: npm run build. Preview: npm run preview.

## A8.8 Testing performed
- Starts and renders correctly; all components render; no console errors.
- Existing frontend/ (Assignments 1–7) remains untouched.
- Assignment 9 features (useState, props, events, conditional rendering) are implemented in `App.jsx`.

## A8.9 Files created/modified
- Created: react-app/ (entire Vite React project).
- Modified: docs/assignment-mapping.md, docs/viva-notes.md.


Update this file after every future assignment.*


# Assignment 10 - Fetch API and JSON

## A10.1 Which file proves this assignment
- `react-app/src/ApiFarmerList.jsx` - the whole Assignment 10 code.
- Rendered by `react-app/src/App.jsx` as the last section inside `<main>` (the
  `<ApiFarmerList />` element), so it appears at the bottom of the Dairy Management page
  under the heading **"Farmer Information from API"**.
- Styles for it are at the end of `react-app/src/App.css` in the
  `Assignment 10: styles of the API farmer section` block.

## A10.2 The whole flow in one line

```
API URL -> fetch() -> HTTP response -> response.json() -> JSON data -> useState() -> .map() -> UI
```

## A10.3 API (Application Programming Interface)
- An **API** is a set of rules one program follows to ask another program for data.
  The second program (the **server**) does not belong to my project - I only call it.
- The API used here is **JSONPlaceholder**: `https://jsonplaceholder.typicode.com/users`
  (a free public test API). It is read-only, needs no key or login, and is safe to show
  in a college demonstration.
- It answers with a **JSON array of 10 user objects**. Other useful endpoints of the same
  API are `/posts` and `/comments`.
- My project has **no backend of its own** for this assignment - no PHP page, no MySQL
  database, no Node.js/Express server.

## A10.4 JSON (JavaScript Object Notation)
- **JSON** is the text format in which an API sends data. It looks like a JavaScript
  object but it is only text: `"name"` in double quotes, no comments, no functions.
- One record from this API looks like this:

```json
{
  "id": 1,
  "name": "Leanne Graham",
  "username": "Bret",
  "email": "Sincere@april.biz",
  "address": { "city": "Gwenborough" }
}
```

- The result is an **array** of 10 such objects, so `data[0]` is the first farmer.
- `address` is a **nested object**, which is why the city is read as `farmer.address.city`.
- `JSON.stringify(object)` turns JavaScript data into JSON text;
  `response.json()` (see A10.6) is the opposite direction - JSON text into JavaScript data.

## A10.5 Fetch API and `fetch()`
- The **Fetch API** is the browser's built-in way to make HTTP requests. It needs no
  library and no installation - no Axios, no jQuery.
- `fetch(url)` **starts** the request and immediately returns a **Promise**; it does
  **not** return the data. The data arrives later, so the code must wait for it with
  `await`, and the function that uses `await` must be marked `async`.

```js
async function fetchFarmerData() {
  const response = await fetch(API_URL)   // <- the request happens here
  // ... the data is available from here on
}
```

- `response` is a **Response** object, not the data. Two members are used:
  - `response.ok` - `true` for status codes 200-299.
  - `response.status` - the HTTP code, used in the error message.

```js
if (!response.ok) {
  throw new Error('API request failed with status ' + response.status)
}
```

- **Why this check is needed:** `fetch()` only *rejects* when the network is completely
  broken. A 404 (wrong address) or a 500 (server error) arrives as a perfectly normal
  response, so without this test a broken page would be shown as if it were data.

## A10.6 `response.json()`
- The body of a response arrives as **text**. `response.json()` reads that text and
  converts it into real JavaScript data (here: an array of objects).
- It is itself asynchronous, so it also needs `await`:

```js
const data = await response.json()
return data
```

- The alternative would be `await response.text()` (plain text) followed by
  `JSON.parse(text)` - both lines together do exactly what `response.json()` does in one.

## A10.7 `useState()` - storing the data
Three states are used in `ApiFarmerList`:

```js
const [apiFarmers, setApiFarmers] = useState([])    // the records from the API
const [loading, setLoading] = useState(true)         // is the request still running?
const [error, setError] = useState('')               // friendly message, '' = no error
```

- `useState(initialValue)` returns **two** values: the current value and the setter.
- Calling the setter (`setApiFarmers(data)`) changes the value, and React draws the
  component again with the new value.
- The array starts as `[]` (empty) so `.map()` has nothing to draw until the data arrives.
- `loading` starts as `true`, so the loading text appears immediately instead of a blank
  section, and `error` starts as `''` (empty string) meaning "no problem".

## A10.8 `useEffect()` - fetching when the component loads

```js
useEffect(() => {
  loadFarmerData()
}, [])
```

- `useEffect(fn)` registers work that must happen **after** the component has been drawn
  on the screen. It is the right place for a network request, because a request must never
  be written directly in the body of a component (that would repeat on every render).
- The second argument is the **dependency array**. `[]` (empty) means "run this effect only
  once, when the component loads". If a value were written inside `[ ]`, the effect would
  run again whenever that value changed.
- The effect calls `loadFarmerData()`, the `async` function that does
  `fetch()` + `response.json()` and then calls the setters.

## A10.9 Loading state
- `{loading && <p className="api-message">Loading farmer data...</p>}` shows the message
  while the request is in flight.
- `setLoading(false)` is written in the **`finally`** block, so the message disappears
  whether the request **succeeded or failed** - otherwise a failed request would leave
  "Loading..." on the screen forever.

## A10.10 Error handling
`loadFarmerData()` uses `try / catch / finally`:

```js
try {
  const data = await fetchFarmerData()
  setApiFarmers(data)                          // success
} catch (technicalError) {
  console.error('Could not load farmer data from the API:', technicalError)
  setError('Unable to load farmer data. Please try again.')   // user-friendly message
} finally {
  setLoading(false)
}
```

- **Three states, one request:** loading, success, error. Only one of the three blocks is
  drawn on the page at a time (conditional rendering).
- The real technical error goes to `console.error` (the browser console); the page shows
  only the simple sentence **"Unable to load farmer data. Please try again."**
- A **Retry** button sets the states back to loading and calls the same fetch again - the
  simplest possible way to recover without reloading the page.

## A10.11 `map()` and the React `key`
```jsx
{apiFarmers.map((farmer) => (
  <ApiFarmerCard key={farmer.id} farmer={farmer} />
))}
```
- `.map()` is an **array function**: it goes through every record and returns a new array
  of JSX, which React then renders. (The same array function was used for Assignment 6.)
- `key={farmer.id}` gives each item a **unique identity**. `id` is unique in the API data
  (1 to 10), so it is the correct key.
- **Why the key is needed:** React compares the old list with the new one by key. Without
  a unique key React cannot tell which card is which, and it may reuse the wrong card, lose
  the state of an item or warn in the console.
- A key must be a **string or a number**, **unique among the siblings**, and **stable**
  (never the array index, because the index changes when the list is reordered or filtered).

## A10.12 The component in short

| Piece | Where | Purpose |
|---|---|---|
| `API_URL` | line 36 | the public API address |
| `fetchFarmerData()` | lines 45-68 | async: `fetch()` -> check `response.ok` -> `response.json()` -> return data |
| `ApiFarmerCard` | lines 76-89 | draws one record: name, username, email, city, record id |
| `ApiFarmerList` | lines 97-200 | owns the three states, the `useEffect()`, the retry button and the `map()` |
| `App.jsx` | line 575 | renders `<ApiFarmerList />` at the end of `<main>` |

## A10.13 Viva questions and answers

**Q: What is the difference between an API and JSON?**
A: An API is the *interface* - the rules and the address at which I ask for data. JSON is
the *format* in which that data is written. One API can answer in several formats; this one
answers in JSON.

**Q: What does `fetch()` return?**
A: A Promise that resolves with a `Response` object - not the data. The data is read from
the response with `response.json()`.

**Q: Why do we need `await` twice?**
A: Once for `fetch()` (waiting for the response headers to arrive) and once for
`response.json()` (waiting for the body to be downloaded and parsed).

**Q: Why is `useEffect()` used and not just a normal function call?**
A: A component body runs again on every render, so a request written there would fire
repeatedly. `useEffect` with `[]` runs it exactly once, after the first render.

**Q: What happens if the internet is off?**
A: `fetch()` rejects, the `catch` block runs, the page shows "Unable to load farmer data.
Please try again." and the Retry button appears.

**Q: Why not Axios?**
A: `fetch()` is built into the browser, needs no installation and no extra dependency, and
this assignment asks for Fetch and JSON. Axios is only an alternative wrapper library.

**Q: Is this data really stored anywhere?**
A: No. The API is a public test service and the records only live in React state while the
page is open. Real storage comes later with PHP + MySQL (Assignments 12 and 13).

## A10.14 Files created / modified for Assignment 10
- Created: `react-app/src/ApiFarmerList.jsx`.
- Modified: `react-app/src/App.jsx` (import + `<ApiFarmerList />`), `react-app/src/App.css`
  (new `Assignment 10` block at the end), `docs/assignment-mapping.md`, `docs/viva-notes.md`.
- Assignments 1-9 were not changed: `frontend/` is untouched and every Assignment 8 and 9
  feature in `App.jsx` (state, props, events, conditional rendering) still works.

## A10.15 How to test it
1. `cd react-app` then `npm run dev`, open the printed local URL.
2. The section first shows **"Loading farmer data..."**, then 10 farmer cards
   (name, username, email, city).
3. Turn off the network (or stop the dev server) and press **Retry**: the friendly error
   message appears instead of the cards.


# Assignment 11 - DOM Manipulation and Event Handling

## A11.1 Which file proves this assignment
- `frontend/pages/collection-centre.html` - two ordinary working parts of the collection
  centre screen, not a separate demo box:
  1. the **"Add a Collection Entry"** form (weighbridge entry of one can), lines 125-211;
  2. the **tools, status strip and summary of "Today's Collection Log"**, lines 213-342.
- `frontend/js/main.js` **section 7** - all the code, from line 957 (`startCollectionLog()`
  is called from `startApp()` on line 443).
- `frontend/css/style.css` - the `ASSIGNMENT 11` block, from line 824 (only four new rules
  and one media query; the page mostly reuses the Assignment 4, 5, 6 and 7 classes).
- The page loads the same single script as before: `<script src="../js/main.js"></script>`
  on line 439, at the end of `<body>`.

## A11.2 What the DOM is, and why the log is read back from the page
- The **DOM** (Document Object Model) is the browser's live tree of the HTML: every tag on the
  page is an *object* that JavaScript can read, change, create or delete.
- `document` is the object that holds the whole tree. `document.getElementById(...)` and
  `document.querySelector(...)` **search** it; the returned element can then be changed.
- The 19 rows of the collection log are written **in the HTML file**, so the only honest way to
  count, filter, update or delete them is to read them back **out of the page**. That is why
  this assignment reads the DOM instead of using the `collections` array of Assignment 6.

## A11.3 Finding elements - the selectors used

| Method | Selector used in my project | Returns | Where |
|---|---|---|---|
| `document.getElementById("collection-log-body")` | id | the one `<tbody>` | `addLogEntry()` line 1483 |
| `document.getElementById("log-status-message")` | id | the status strip | `setTextById()` line 1076 |
| `document.getElementById("shift-fill")` | id | the progress bar | `updateLogSummary()` line 1124 |
| `document.getElementById("log-entry-form")` | id | the entry form | `startLogEntryForm()` line 1590 |
| `querySelectorAll("#collection-log-body tr")` | id + tag | a **list** of every row | `logRows()` line 1014 |
| `querySelectorAll("td")` | tag | the six cells of **one** row | `readLogEntry()` line 1022 |
| `querySelectorAll("#collection-log-body tr.selected")` | id + tag + class | the chosen row | `clearLogSelection()` line 1106 |
| `querySelectorAll("#collection-log-body tr.peak-row")` | id + tag + class | the biggest can | `highlightLargestEntry()` line 1274 |
| `querySelectorAll("#collection-log-body tr[data-added='yes']")` | **attribute** selector | the rows JavaScript created | `removeAddedRows()` line 1360 |
| `querySelector("#collection-log-body tr.selected")` | id + tag + class | the **first** match only | `removeSelectedRow()` line 1334 |

- `getElementById` is the fastest way to find one element, because `id` is unique.
- `querySelector(All)` takes a **CSS selector** - the same selectors used in `style.css`.
- `querySelector` returns **one** element (or `null`); `querySelectorAll` returns a **list**
  that can be walked with `forEach()`, exactly like an array.
- The list is **static**: it is a snapshot, so after a row is added or deleted the function is
  called again to get a fresh list.

## A11.4 Changing the page

| What changes | Code | Visible result |
|---|---|---|
| **Text** | `element.textContent = "..."` | the status strip, the three summary numbers, the progress sentence, every cell of a new row, and one replaced Status cell |
| **A class** | `classList.add("selected")` / `.remove("is-pending")` / `.remove("peak-row")` / `.add("ok")` | the row is highlighted, an unpaid row shows its Status in red, the biggest can is marked, the message box turns green or red |
| **A style** | `row.style.display = "none"` (and `= ""` to show it again) | a filtered-out row disappears |
| **A style** | `progressBar.style.width = percent + "%"` | the gold bar grows or shrinks |
| **A style** | `row.style.fontWeight = "bold"` (and `= ""`) | the biggest can is printed bold |
| **An attribute** | `row.setAttribute("data-added", "yes")` | marks a row as "made by JavaScript", so the HTML rows are never deleted by mistake |
| **Create** | `document.createElement("tr")`, `document.createElement("td")`, `row.appendChild(cell)`, `tbody.appendChild(row)` | a new line at the bottom of the log for every entry typed in the form |
| **Delete** | `row.remove()` | "Remove Selected Row" and "Remove Added Rows" take the row off the page |
| **Read** | `cell.textContent`, `Number(cell.textContent)` | the six cells become a JavaScript object again |

- `textContent` is used everywhere and **`innerHTML` is never used with typed text**. If the
  farmer types `<b>hello</b>`, `textContent` shows those characters literally, while
  `innerHTML` would turn them into bold text - that is the XSS (cross-site scripting) risk.
- `row.remove()` is the short form of `row.parentNode.removeChild(row)`. After `remove()` the
  element object still exists in memory, but it is no longer in the page.

## A11.5 The events used

| Event | Element | What happens |
|---|---|---|
| `DOMContentLoaded` | document | `startApp()` -> `startCollectionLog()` (line 1551): first summary, then every listener is connected |
| `submit` | `#log-entry-form` | `preventDefault()`, four checks, then the new `<tr>` is created and appended; on failure the message box turns red and the cursor goes to the first wrong box |
| `input` | `#log-search` | on **every keystroke** `applyLogFilter()` (line 1229) hides or shows the rows - the summary follows automatically |
| `input` | the four entry boxes | a box that is already showing an error is re-checked while typing, so the message disappears as soon as the value is right |
| `change` | `#log-status-filter` | shows only the Paid or only the Pending entries |
| `click` | any `<tr>` of the log | `selectLogRow()` (line 1185): removes `selected` from the old row, adds it to the clicked one, writes the details of that can |
| `click` | `#log-highlight-largest` | `reduce()` finds the biggest can of the current view and moves the `peak-row` class onto it |
| `click` | `#log-mark-paid` | the Status cell of the selected row becomes "Paid" and `is-pending` is removed |
| `click` | `#log-remove-selected` | the selected row is deleted with `.remove()` |
| `click` | `#log-remove-added` | every `tr[data-added="yes"]` is deleted; the 19 HTML rows stay |
| `click` | `#log-reset-view` | the search box and the dropdown are emptied, every class and inline style is taken off |
| `reset` | `#log-entry-form` | the four error messages are removed (also fires from `form.reset()`) |

- `click` is used for **buttons and rows**; `input` fires while typing; `change` fires when a
  `<select>` choice is finished; `submit` is the only event that could send a form anywhere.
- `element.addEventListener("click", function () { ... })` connects an event to a function.
  `onButtonClick(id, fn)` (line 1095) is the small helper used for the five buttons.

## A11.6 Reuse - what was NOT written again
- Validation: `validateFarmerName()`, `validateMilkQuantity()` and `validateFatPercentage()`
  from Assignment 7, plus `checkField()`, `clearFieldError()`, `fieldValue()` and the rate
  board rule `rateForFat()`. Only `validateSnf()` (3% to 10%) is new.
- Array functions from Assignment 6: `totalLitres()` (reduce) and `filter()` for the pending
  count and the biggest can.
- CSS: `.farmer-form`, `.form-grid`, `.form-row`, `.form-buttons`, `.btn-light` (A7);
  `.register-tools`, `.register-status`, `.total-value`, `.log-window tbody tr.selected` (A6);
  `.flex-row`, `.flex-item` (A4); `.tank-gauge`, `.tank-fill`, `.log-window` (A5).
- New CSS is only: `.form-row select`, `.log-window tbody tr.is-pending td:last-child`,
  `.log-window tbody tr.peak-row`, `.log-tools button`, `.log-tools button.btn-light` and one
  `max-width: 600px` rule.

## A11.7 Safety and what is NOT stored
- `startCollectionLog()` returns immediately when `#collection-log-body` is not on the page,
  which is why the same `main.js` can be loaded by `dashboard.html` and `farmers.html`
  without any change - Assignment 6 and Assignment 7 keep working exactly as before.
- Every button is connected through `onButtonClick()`, which checks the element first.
- Nothing is sent anywhere: there is no `action` on the form, no PHP page and no database
  (that is Assignment 13), so `preventDefault()` keeps everything in the browser and a row
  added here disappears when the page is closed.

## A11.8 Viva questions and answers

**Q: What is the DOM?**
A: The browser's tree of the HTML page. Every tag becomes an object that JavaScript can read
or change. `document` holds the tree, and each element can hold child elements.

**Q: `getElementById` or `querySelector` - which one and why?**
A: `getElementById` for a single known element (fastest, `id` is unique).
`querySelector`/`querySelectorAll` when I need a CSS selector, several elements, or a
descendant such as `"#collection-log-body tr"`.

**Q: `textContent` or `innerHTML`?**
A: `textContent`. It writes plain text and can never interpret what is typed as HTML, so it is
safe. `innerHTML` would execute markup typed into a box - that is the XSS risk.

**Q: How do you add an element to the page?**
A: `createElement()` to build it in memory, fill it, then `appendChild()` on its parent. It
only appears on the page at the moment of `appendChild()`.

**Q: How do you delete one?**
A: `row.remove()` - it deletes the element and all its children. `removeChild()` on the parent
is the older, longer form of the same thing.

**Q: Why hide a filtered row with `style.display` instead of deleting it?**
A: The clerk may want the whole log back. `Reset Log View` clears the search box and the
dropdown and every row returns, which is much safer than reloading the page.

**Q: `classList.add()` or `className = "..."`?**
A: `classList` changes one class and leaves the others alone; `className = "..."` would throw
every other class away. That matters here, because `tr` already has classes from the table.

**Q: What is event bubbling / why connect a listener to each row?**
A: An event travels up from the element that was clicked to its parents. The rows are created
by JavaScript, so each new row is connected by `connectRowClicks()` after it is added - the
listeners keep working for rows that did not exist when the page loaded.

**Q: Where is the data of this assignment?**
A: In the page itself. The rows are read back out of the DOM for every calculation, so the
HTML and the displayed numbers can never disagree. Real storage comes with Assignment 13
(PHP + MySQL).

**Q: Which Assignment 6 and 7 features are still working?**
A: All of them. `main.js` is still the only script, the Assignment 6 register on
`dashboard.html` and the Assignment 7 farmer form on `farmers.html` were not changed, and
section 7 simply stops at once on a page that has no collection log.

## A11.9 Files created / modified for Assignment 11
- Created: none - no new file was needed.
- Modified: `frontend/pages/collection-centre.html` (entry form, log tools, status strip,
  summary, `id="collection-log-body"`, the script tag), `frontend/js/main.js` (section 7 and
  the `startCollectionLog()` call), `frontend/css/style.css` (new `ASSIGNMENT 11` block),
  `docs/assignment-mapping.md`, `docs/viva-notes.md`.
- Assignments 1-10 were not changed: `index.html`, `about.html`, `dashboard.html` and
  `farmers.html` are untouched, every CSS rule above the new block is unchanged, and
  `react-app/` was not opened.

## A11.10 How to test it
1. Open `frontend/pages/collection-centre.html` in a browser (no server needed).
2. **Add a Collection Entry**: type a name, quantity, fat and SNF, press **Add To Log** - a
   new row appears at the bottom of the log, the summary counts go up and the progress bar
   grows. Type a fat % of 12% to see the red message and the red border.
3. **Click a row** - it is highlighted and its details appear in the status strip.
4. **Mark Selected as Paid** - the last cell of that row changes to "Paid" and the pending
   count drops by one.
5. **Search box**: type `pat` - only rows whose name contains "pat" stay, and the summary
   counts only those. Clear it and all rows return.
6. **Dropdown**: choose "Pending only" - only unpaid rows stay.
7. **Highlight Highest Quantity** - the biggest visible can is marked and printed bold.
8. **Remove Selected Row**, then **Remove Added Rows** - rows disappear; the 19 rows written
   in the HTML file are never removed by the second button.
9. **Reset Log View** - everything is shown again with no marks.
