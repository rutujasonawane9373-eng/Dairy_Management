<?php
/* ============================================================
   DAIRY MANAGEMENT SYSTEM - ASSIGNMENT 13
   SHARED PAGE FOOTER
   File: php/db-crud/db-footer.php

   Closes the <main> that db-header.php opened and prints the
   last part of the page. Included at the END of every A13 page.
   ============================================================ */
?>
        <!-- The two actions of the CRUD screen, on every page, so
             the four operations are always one click apart. -->
        <p>
            <a class="btn" href="farmer-create.php">Add a New Farmer (INSERT)</a>
            <a class="btn btn-light" href="farmer-list.php">View All Farmers (SELECT)</a>
        </p>

    </main>

    <footer>
        <p>Contact: Dairy Cooperative Society Office, Main Road, Village &nbsp;|&nbsp; Phone: 98765 43210</p>
        <p>&copy; 2026 Dairy Management System - College Web Development Project</p>
        <p>
            Assignment 13 (PHP + MySQL) pages read and write the
            <code><?= safeText(DB_NAME) ?>.<?= safeText(FARMERS_TABLE) ?></code>
            table on the server. Unlike Assignment 12, nothing is kept
            in the session any more - the records are permanent and are
            shared by every visitor.
        </p>
    </footer>

</body>
</html>
