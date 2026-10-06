// ================================================================
//  DAIRY MANAGEMENT SYSTEM
//  ASSIGNMENT 14 : Node.js + Express  (Web Server)
//  ASSIGNMENT 15 : REST API  (JSON endpoints + MySQL)
//
//  What Assignment 14 part of this file demonstrates:
//    1. Creating an Express application        -> express()
//    2. Starting a server and listening on a port -> app.listen()
//    3. GET routing                             -> app.get()
//    4. Serving static files from a folder      -> express.static()
//    5. A friendly 404 page for unknown routes
//
//  What Assignment 15 added (see routes/farmers.js):
//    - express.json()      reads a JSON request body into req.body
//    - res.json()          answers with JSON instead of HTML
//    - POST / PUT / DELETE routes for /api/farmers
//    - a MySQL connection  (db.js + db-config.js, prepared statements)
//
//  How to run:
//    cd node-backend
//    npm install        (only the first time)
//    npm start
//  then open http://localhost:3000
//  API demo: http://localhost:3000/api/farmers
// ================================================================

// require() loads a module that was installed by npm.
// 'express' is our web framework, 'path' is a built-in Node.js helper
// for building file paths in a way that works on Windows, Mac and Linux.
const express = require('express');
const path = require('path');

// The /api/farmers router of Assignment 15 (all five endpoints).
const farmersRouter = require('./routes/farmers');

// express() creates the application object.
// Everything we want the server to do is attached to this object.
const app = express();

// ================================================================
//  express.json()  -  Assignment 15
//
//  Reads the body of a request whose Content-Type is
//  application/json, turns it into a JavaScript object and puts it
//  in req.body. Without this line req.body would be undefined and
//  POST / PUT could never see what the client sent.
//
//  It only runs for JSON content types, so the HTML GET routes of
//  Assignment 14 are not affected in any way.
// ================================================================
app.use(express.json());

// The port the server listens on.
// process.env.PORT lets us change the port from outside the code
// (e.g. "PORT=4000 node server.js"). If nothing is given, use 3000.
const PORT = process.env.PORT || 3000;

// Folder that holds the static files (HTML, CSS, images).
// __dirname is the folder of THIS file (node-backend), so this
// always points to node-backend/public no matter where we start from.
const PUBLIC_FOLDER = path.join(__dirname, 'public');


// Turns the characters that have a special meaning in HTML into harmless
// text, so that whatever a visitor typed in the address bar is only ever
// shown as words and can never become HTML code (this stops XSS).
// Same idea as safeText() in php/db-crud/db-helpers.php (Assignment 13).
function escapeHtml(text) {
  return String(text)
    .replace(/&/g, '&amp;')    // & must be replaced first
    .replace(/</g, '&lt;')     // < starts a tag
    .replace(/>/g, '&gt;')     // > ends a tag
    .replace(/"/g, '&quot;')   // " starts an attribute value
    .replace(/'/g, '&#39;');   // ' starts an attribute value
}


// ================================================================
//  1. ROUTES  -  the pages the server builds itself
//
//  IMPORTANT: these two routes are written BEFORE express.static().
//  Express checks its handlers from top to bottom and uses the FIRST
//  one that answers. So "/" is answered by the route below and never
//  reaches the static folder.
// ================================================================

// GET /  ->  the home page
// req = the request that arrived (what the browser asked for)
// res = the response that goes back (what we send to the browser)
app.get('/', (req, res) => {
  res.send(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Dairy Management System</title>

    <!-- A CSS file that lives in public/ and is served by express.static() -->
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1>Dairy Management System</h1>
        <p>Your daily partner for milk collection, fat &amp; SNF tracking and farmer payments.</p>
    </header>

    <main>
        <section>
            <h2>This page was sent by the Node.js server</h2>
            <p>
                You are reading <strong>Assignment 14 - Node.js + Express</strong>.
                The HTML above was written inside <code>server.js</code> and sent with
                <code>res.send()</code> when the browser asked for <code>/</code>.
            </p>

            <h3>Try these</h3>
            <ul>
                <li><a href="/about">/about</a> - the second route of this server</li>
                <li><a href="/index.html">/index.html</a> - a STATIC file from <code>public/</code></li>
                <li><a href="/style.css">/style.css</a> - a static CSS file (this page is using it)</li>
                <li><a href="/milk-can.svg">/milk-can.svg</a> - a static image file</li>
                <li><a href="/no-such-page">/no-such-page</a> - the 404 page</li>
            </ul>
        </section>

        <section>
            <h3>Static vs route</h3>
            <table>
                <tr><th>URL</th><th>Who answers it</th></tr>
                <tr><td><code>/</code></td><td>the <code>app.get('/')</code> route in server.js</td></tr>
                <tr><td><code>/about</code></td><td>the <code>app.get('/about')</code> route in server.js</td></tr>
                <tr><td><code>/index.html</code>, <code>/style.css</code>, <code>/milk-can.svg</code></td>
                    <td><code>express.static()</code>, reading the file from <code>public/</code></td></tr>
            </table>
            <img src="/milk-can.svg" alt="Milk can" width="90" height="90">
        </section>
    </main>

    <footer>
        <p>Dairy Management System - Assignment 14 (Node.js + Express). College project.</p>
    </footer>
</body>
</html>`);
});

// GET /about  ->  the about page
app.get('/about', (req, res) => {
  res.send(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Dairy Management System</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1>About the Dairy Management System</h1>
    </header>

    <main>
        <section>
            <h2>What the system does</h2>
            <p>
                A dairy cooperative society collects milk from farmers every morning and
                evening. Each sample is tested for fat and SNF (non-fat solids), a rate per
                litre is decided from the fat content, and the farmer is paid once a
                fortnight. This project turns that paper work into web pages.
            </p>

            <h2>Routes on this server</h2>
            <ol>
                <li><code>GET /</code> - the home page (written by the server)</li>
                <li><code>GET /about</code> - this page (written by the server)</li>
                <li><code>GET /index.html</code>, <code>/style.css</code>,
                    <code>/milk-can.svg</code> - static files served from
                    <code>public/</code></li>
            </ol>

            <h2>How the server was built</h2>
            <ul>
                <li><code>npm install express</code> - downloads the express package into
                    <code>node_modules/</code></li>
                <li><code>npm start</code> - runs <code>node server.js</code></li>
                <li><code>app.listen(3000)</code> - the server waits for requests on port 3000</li>
            </ul>
        </section>
    </main>

    <footer>
        <p><a href="/">Back to Home</a> - Dairy Management System, Assignment 14.</p>
    </footer>
</body>
</html>`);
});


// ================================================================
//  2. REST API  (Assignment 15)
//
//  app.use('/api/farmers', farmersRouter) mounts the router from
//  routes/farmers.js under the prefix /api/farmers. Inside the
//  router the paths are written short ("/", "/:id") and Express
//  joins the prefix on:
//
//      GET    /api/farmers        -> router.get('/')
//      GET    /api/farmers/4      -> router.get('/:id')
//      POST   /api/farmers        -> router.post('/')
//      PUT    /api/farmers/4      -> router.put('/:id')
//      DELETE /api/farmers/4      -> router.delete('/:id')
//
//  It is registered BEFORE express.static() and before the HTML
//  404 page, so an API address never receives an HTML answer.
// ================================================================
app.use('/api/farmers', farmersRouter);

// Unknown /api/... address -> a JSON 404, not the HTML 404 page.
// A program that called the wrong URL must get JSON it can read.
app.use('/api', (req, res) => {
  res.status(404).json({
    error: `Unknown API endpoint: ${req.method} ${req.originalUrl}. Try GET /api/farmers`
  });
});


// ================================================================
//  3. STATIC FILES
//
//  express.static(PUBLIC_FOLDER) tells Express: "whenever a file is
//  asked for that exists inside node-backend/public, send that file".
//
//  It is registered AFTER the two routes on purpose - otherwise
//  public/index.html would answer "/" and our route would never run.
//
//  Note the short URLs: a file at public/style.css is requested as
//  /style.css, not /public/style.css. The folder name is not part of
//  the address.
// ================================================================
app.use(express.static(PUBLIC_FOLDER));


// ================================================================
//  4. 404 PAGE
//
//  This runs only if nothing above answered the request.
//  404 means "I am alive, but that page does not exist".
//  res.status(404) sets the HTTP status code before sending the page.
// ================================================================
app.use((req, res) => {
  res.status(404).send(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Dairy Management System</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1>404 - Page Not Found</h1>
    </header>

    <main>
        <section>
            <p>The address <strong>${escapeHtml(req.originalUrl)}</strong> does not exist on this server.</p>
            <p>These addresses do exist:</p>
            <ul>
                <li><a href="/">/</a></li>
                <li><a href="/about">/about</a></li>
                <li><a href="/index.html">/index.html</a></li>
            </ul>
        </section>
    </main>

    <footer>
        <p>Dairy Management System - Assignment 14 (Node.js + Express). College project.</p>
    </footer>
</body>
</html>`);
});


// ================================================================
//  5. ERROR HANDLER  (Assignment 15)
//
//  A normal middleware has (req, res); an ERROR middleware also
//  receives "next" as its fourth argument, which is how Express
//  recognises it. Routes call next(error) when something throws
//  (MySQL down, bad SQL, ...) and execution jumps straight here.
//
//  It MUST be registered after every route - Express skips regular
//  middleware and looks only for error handlers once next(err) ran.
//
//  Two kinds of error are turned into JSON, so an API client never
//  receives an HTML stack trace:
//    - entity.parse.failed -> the client sent broken JSON   -> 400
//    - anything else       -> our bug or a database problem -> 500
// ================================================================
app.use((error, req, res, next) => {
  // A syntax error in the JSON body (e.g. {"name": ) is the
  // CLIENT's mistake -> 400 Bad Request.
  if (error.type === 'entity.parse.failed') {
    return res.status(400).json({ error: 'The request body is not valid JSON.' });
  }

  // Everything else is logged for us and answered with a plain 500.
  // The message is hidden from the client so no SQL or paths leak.
  console.error('Server error:', error.message);
  res.status(500).json({ error: 'Internal server error. Check that MySQL is running.' });
});


// ================================================================
//  6. START THE SERVER
//
//  app.listen() does not return the running server - it returns
//  immediately and keeps the program alive in the background while it
//  waits for requests. The function inside the brackets runs once,
//  just after the server has started.
// ================================================================
app.listen(PORT, () => {
  console.log('=================================================');
  console.log(' Dairy Management System - Assignments 14 + 15');
  console.log(' Node.js + Express server is running');
  console.log('=================================================');
  console.log(` Open in the browser : http://localhost:${PORT}`);
  console.log(` Route  GET /        : http://localhost:${PORT}/`);
  console.log(` Route  GET /about   : http://localhost:${PORT}/about`);
  console.log(' REST API (Assignment 15):');
  console.log(`   GET    /api/farmers     : http://localhost:${PORT}/api/farmers`);
  console.log(`   GET    /api/farmers/:id : http://localhost:${PORT}/api/farmers/1`);
  console.log(`   POST   /api/farmers     : JSON body, curl/Postman`);
  console.log(`   PUT    /api/farmers/:id : JSON body, curl/Postman`);
  console.log(`   DELETE /api/farmers/:id : http://localhost:${PORT}/api/farmers/1`);
  console.log(` Static files folder : ${PUBLIC_FOLDER}`);
  console.log(' Stop the server     : press Ctrl + C');
  console.log('=================================================');
});