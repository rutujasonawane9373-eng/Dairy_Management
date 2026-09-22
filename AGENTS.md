# AGENTS.md

Project: **Dairy_Management**

College Web Development course project: a single "Dairy Management System" app that demonstrates
15 practical assignments + the final course project across HTML/CSS/JS, React, PHP, MySQL, and
Node.js + Express.

## Development rules (user-mandated)

- Build strictly **phase-by-phase**. Never build ahead into later phases.
- Never delete or overwrite existing useful files.
- Before major changes, explain which files will be created/changed and why, then wait for confirmation.
- Use simple, beginner-friendly code with comments at important concepts. Explain code to the user.
- Do NOT install packages/software without explaining why it is needed and how to install it.
- Every assignment must be clearly identifiable in the project (see `docs/assignment-mapping.md`).
- Same Dairy theme throughout.

## Current state (Phase 2)

```
frontend/
├── index.html          # Assignment 2: semantic HTML homepage (header/nav/main/section/article/aside/footer)
├── pages/about.html    # Assignment 1: headings, paragraphs, ol/ul lists, images
├── css/                # empty - CSS added in Phase 4 (Assignment 3)
├── js/                 # empty - JS added in Phase 5 (Assignment 6)
└── assets/             # cow.svg, farm.svg, milk-can.svg
docs/assignment-mapping.md   # maps every assignment to its file/feature
```

## Phase plan (verify before executing)

- Phase 3: (TBD on user request)
- Phase 4: CSS assignments 3, 4, 5
- Phase 5: JS assignments 6, 7, 11
- Phase 6: React assignments 8, 9
- Phase 7: Fetch/JSON assignment 10
- Phase 8: PHP assignment 12
- Phase 9: PHP+MySQL CRUD assignment 13
- Phase 10: Node.js+Express assignment 14
- Phase 11: REST API assignment 15
- Phase 12: Final integration

## Environment notes

- Windows / PowerShell. Node v24, npm v11, MySQL 8.0 running (service `MySQL80`).
- PHP is NOT installed; XAMPP not installed — do not install until Phase 8 without confirmation.
- MySQL root password not yet configured — do not set up DB until the planned phase.
- Nav links to `pages/dashboard.html`, `pages/farmers.html`, `pages/milk.html` are placeholders until their phases.

## Testing

- Phase 2 pages are static — open directly in a browser or via VS Code Live Server.
- Folders `react-app/`, `node-backend/`, `php/`, `database/` do not exist yet — do not reference as if they do.