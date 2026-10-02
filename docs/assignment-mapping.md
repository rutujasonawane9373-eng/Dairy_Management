# Assignment Mapping - Dairy Management System

This document maps every college practical assignment to the file / feature that demonstrates
it in the project. Use it to show your teacher exactly where each concept is implemented.

Current project phase: Phase 4 — CSS (Assignments 3, 4, 5)

## Assignment → File/Feature

| # | Assignment | File / Feature | Status |
|---|------------|----------------|--------|
| 1 | Basic HTML (headings, paragraphs, ordered/unordered lists, images) | `frontend/pages/about.html` | ✅ Done |
| 2 | Semantic HTML tags (header, nav, main, section, article, aside, footer) | `frontend/index.html` | ✅ Done |
| 3 | CSS - inline, internal, external | External: `frontend/css/style.css`; Internal: `<style>` in `frontend/index.html`; Inline: `style=""` in `frontend/index.html` & `frontend/pages/about.html` | ✅ Done |
| 4 | Responsive design - Flexbox, Grid, Media Queries | `frontend/pages/dashboard.html` + `frontend/css/style.css` (Flexbox nav & summary, Grid stat cards, 3 media-query layouts) | ✅ Done |
| 5 | CSS positions and other properties | `frontend/pages/collection-centre.html` + `frontend/css/style.css` (ASSIGNMENT 5 block: static / relative / absolute / fixed / sticky positions, z-index, top-right-bottom-left, width-height, margin, padding, border, border-radius, box-shadow, overflow, opacity) | ✅ Done |
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

## Project structure (as of Phase 4 — Assignment 5)

```
Dairy_Management/
├── AGENTS.md
├── frontend/
│   ├── index.html
│   ├── pages/
│   │   ├── about.html
│   │   ├── dashboard.html
│   │   └── collection-centre.html
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