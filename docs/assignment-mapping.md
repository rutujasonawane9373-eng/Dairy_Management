# Assignment Mapping - Dairy Management System

This document maps every college practical assignment to the file / feature that demonstrates
it in the project. Use it to show your teacher exactly where each concept is implemented.

Current project phase: **Phase 2 (HTML structure - Assignments 1 and 2 done)**

## Assignment → File/Feature

| # | Assignment | File / Feature | Status |
|---|------------|----------------|--------|
| 1 | Basic HTML (headings, paragraphs, ordered/unordered lists, images) | `frontend/pages/about.html` | ✅ Done |
| 2 | Semantic HTML tags (header, nav, main, section, article, aside, footer) | `frontend/index.html` | ✅ Done |
| 3 | CSS - inline, internal, external | External: `frontend/css/style.css` (planned) | ⏳ Pending |
| 4 | Responsive design - Flexbox, Grid, Media Queries | `frontend/pages/dashboard.html` (planned) | ⏳ Pending |
| 5 | CSS positions and other properties | Various UI elements (planned) | ⏳ Pending |
| 6 | JavaScript events + array functions | `frontend/js/main.js` (planned) | ⏳ Pending |
| 7 | JavaScript form validations | `frontend/js/main.js` (planned) | ⏳ Pending |
| 8 | React - components, JSX | `react-app/` (planned) | ⏳ Pending |
| 9 | React - props, state, hooks, events | `react-app/` (planned) | ⏳ Pending |
| 10 | Fetch API + JSON | `frontend/js/api.js` (planned) | ⏳ Pending |
| 11 | DOM manipulation + events | `frontend/js/main.js` (planned) | ⏳ Pending |
| 12 | PHP - forms, validation, strings, sessions | `php/auth/` (planned) | ⏳ Pending |
| 13 | PHP + MySQL - CRUD | `php/farmer/` (planned) | ⏳ Pending |
| 14 | Node.js + Express - server, routing, static files | `node-backend/server.js` (planned) | ⏳ Pending |
| 15 | REST API (Node.js + Express + DB) | `node-backend/routes/` (planned) | ⏳ Pending |
| 16 | Complete project integration | All modules together | ⏳ Pending |

## Project structure (as of Phase 2)

```
Dairy_Management/
├── AGENTS.md
├── frontend/
│   ├── index.html          # Assignment 2 homepage (semantic HTML)
│   ├── pages/
│   │   └── about.html      # Assignment 1 about page
│   ├── css/                # empty - CSS added in Phase 4 (Assignment 3)
│   ├── js/                 # empty - JS added in Phase 5 (Assignment 6)
│   └── assets/
│       ├── cow.svg
│       ├── farm.svg
│       └── milk-can.svg
├── docs/
│   └── assignment-mapping.md
└── (react-app/, node-backend/, php/, database/ added in later phases)
```