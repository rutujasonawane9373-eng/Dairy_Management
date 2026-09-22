# Assignment Mapping - Dairy Management System

This document maps every college practical assignment to the file / feature that demonstrates
it in the project. Use it to show your teacher exactly where each concept is implemented.

Current project phase: Phase 4 — CSS (Assignment 4)

## Assignment → File/Feature

| # | Assignment | File / Feature | Status |
|---|------------|----------------|--------|
| 1 | Basic HTML (headings, paragraphs, ordered/unordered lists, images) | `frontend/pages/about.html` | ✅ Done |
| 2 | Semantic HTML tags (header, nav, main, section, article, aside, footer) | `frontend/index.html` | ✅ Done |
| 3 | CSS - inline, internal, external | External: `frontend/css/style.css`; Internal: `<style>` in `frontend/index.html`; Inline: `style=""` in `frontend/index.html` & `frontend/pages/about.html` | ✅ Done |
| 4 | Responsive design - Flexbox, Grid, Media Queries | `frontend/pages/dashboard.html` + `frontend/css/style.css` (Flexbox nav & summary, Grid stat cards, 3 media-query layouts) | ✅ Done |
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

## Project structure (as of Phase 4)

```
Dairy_Management/
├── AGENTS.md
├── frontend/
│   ├── index.html
│   ├── pages/
│   │   ├── about.html
│   │   └── dashboard.html
│   ├── css/
│   │   └── style.css
│   ├── js/
│   └── assets/
│       ├── cow.svg
│       ├── farm.svg
│       └── milk-can.svg
├── docs/
│   └── assignment-mapping.md
└── later-phase folders are not created yet
```