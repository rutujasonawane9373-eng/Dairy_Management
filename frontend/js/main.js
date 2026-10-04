/* ============================================================
   DAIRY MANAGEMENT SYSTEM - External JavaScript file
   ASSIGNMENT 6 : BASIC JAVASCRIPT EVENTS AND ARRAY FUNCTIONS

   HOW THIS FILE IS CONNECTED TO A PAGE
   -------------------------------------
   frontend/pages/dashboard.html loads this file at the very end of
   its <body> with:

       <script src="../js/main.js"></script>

   The script tag is placed AFTER the HTML on purpose. By the time
   the script runs, every element it needs is already on the page,
   so it can look them up with document.getElementById().

   ".." in the src path means "go up one folder": dashboard.html is
   in frontend/pages/, so ../js/ finds frontend/js/.

   WHAT IS DEMONSTRATED IN THIS FILE
   ---------------------------------
   1. EVENTS
        click       - statistic card, shift block, table row, button
        mouseover   - hovering a statistic card shows what it means
        mouseout    - moving away puts the summary message back
        input       - typing in the farmer search box looks a farmer up
        change      - choosing a filter shows only those collections
        DOMContentLoaded - runs the whole app once the page is ready

   2. ARRAY FUNCTIONS
        forEach()   - draw one table row per collection record
        map()       - build a new array of payment amounts
        filter()    - keep only the records we want to show
        find()      - get the first record that matches a name
        reduce()    - add up litres, money and fat values

ASSIGNMENT 7 : the farmer registration form and its validation
         submit      - check every field before accepting the form
         blur        - check one field when the farmer leaves it
         input       - re-check a field that is showing an error
         reset       - clear the error messages with the form
         preventDefault() - nothing is sent, there is no server yet

   ASSIGNMENT 11 : DOM MANIPULATION AND EVENT HANDLING
   Page: frontend/pages/collection-centre.html
         getElementById()      - find the log, the form, the summary
         querySelector()       - find one row inside the log
         querySelectorAll()    - find every row, every cell, every
                                 highlighted row
         textContent           - write the messages and the numbers
         classList.add/remove  - selected / is-pending / peak-row
         style.display, style.width - hide rows, move the progress bar
         createElement()       - build a new <tr> and its <td>s
         appendChild()         - put the new row on the page
         remove()              - delete a row from the page
         click / input / change / submit - the four events used here

   ERROR SAFETY
   ------------
   Every element is fetched with document.getElementById() and used
   only after checking that it exists ("if (element) { ... }"), so
   this file never throws an error on a page where that part of the
   interface is missing.
   ============================================================ */


/* ============================================================
   1. THE DATA
   ============================================================
   Today's collection records for the register. They are stored as a
   normal JavaScript array of objects. In a later phase the same
   list will come from the database instead of being written here. */

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

/* The records currently visible in the register, and the shift the
   user has clicked on. "All" means no shift filter is applied. */
let currentRecords = collections;
let currentShift = "All";


/* ============================================================
   2. SMALL HELPER FUNCTIONS
   ============================================================ */

/* toMoney() turns a number into Rupee text: 814 -> "₹ 814.00".
   .toFixed(2) always keeps two digits after the decimal point. */
function toMoney(amount) {
    return "₹ " + amount.toFixed(2);
}

/* setStatus() writes a message into the status strip. textContent
   is used (not innerHTML) so the message is always shown as plain
   text, never as HTML. */
function setStatus(message) {
    const statusBox = document.getElementById("register-status");

    if (!statusBox) {
        return;          /* no status strip on this page - do nothing */
    }

    statusBox.textContent = message;
}


/* ============================================================
   3. ARRAY FUNCTIONS
   ============================================================
   Each one below has a single clear job in this project.        */

/* forEach() -> draw one table row for every record it is given.
   forEach() visits every item but does NOT return a new array. */
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

        /* click event on every row that is drawn */
        row.addEventListener("click", function () {
            selectRow(row, record);
        });
    });
}

/* filter() -> keep only the records at or above the given litres.
   filter() returns a NEW array; the original array is untouched. */
function recordsAtLeast(minimumLitres) {
    return collections.filter(function (record) {
        return record.liters >= minimumLitres;
    });
}

/* map() -> build a new array of payment amounts (litres x rate).
   map() returns a new array of the SAME LENGTH, one new value for
   each old value. Here the new values are the money amounts. */
function paymentAmounts(recordList) {
    return recordList.map(function (record) {
        return record.liters * record.rate;
    });
}

/* find() -> return the FIRST record whose farmer name contains the
   typed text, or undefined if nobody matches. */
function findFarmer(nameTyped) {
    const wanted = nameTyped.trim().toLowerCase();

    if (wanted === "") {
        return undefined;
    }

    return collections.find(function (record) {
        return record.farmer.toLowerCase().indexOf(wanted) !== -1;
    });
}

/* reduce() -> add up the litres of a record list.
   reduce() walks the array and keeps one running total. The 0 at
   the end is the starting value of that total. */
function totalLitres(recordList) {
    return recordList.reduce(function (total, record) {
        return total + record.liters;
    }, 0);
}

/* reduce() again -> add up a list of money amounts that map()
   has already created. */
function totalAmount(amounts) {
    return amounts.reduce(function (total, amount) {
        return total + amount;
    }, 0);
}

/* reduce() again -> average fat of a record list.
   An empty list has no average, so 0 is returned instead. */
function averageFat(recordList) {
    if (recordList.length === 0) {
        return 0;
    }

    const fatTotal = recordList.reduce(function (total, record) {
        return total + record.fat;
    }, 0);

    return fatTotal / recordList.length;
}


/* ============================================================
   4. DRAWING THE REGISTER
   ============================================================ */

/* totalCard() builds one summary block for the totals strip below
   the register. It returns HTML text, which the caller inserts. */
function totalCard(title, value) {
    return '<div class="flex-item">' +
           '<h3>' + title + '</h3>' +
           '<p class="total-value">' + value + '</p>' +
           '</div>';
}

/* showTotals() fills the four summary blocks below the register.
   map() makes the amounts array, reduce() adds it up, filter()
   counts the low-fat cans. */
function showTotals(recordList) {
    const totalsBox = document.getElementById("register-totals");

    if (!totalsBox) {
        return;
    }

    const litres = totalLitres(recordList);                   /* reduce */
    const money = totalAmount(paymentAmounts(recordList));     /* map + reduce */
    const fat = averageFat(recordList);                        /* reduce */

    /* A cow with fat below 3.5% is paid at the lower rate, so the
       number of such cans is worth showing. filter() keeps them. */
    const lowFatCans = recordList.filter(function (record) {
        return record.fat < 3.5;
    });

    totalsBox.innerHTML =
        totalCard("Litres in this view", litres.toFixed(1) + " L") +
        totalCard("Payment in this view", toMoney(money)) +
        totalCard("Average fat", fat.toFixed(2) + "%") +
        totalCard("Low-fat cans", lowFatCans.length + " cans");
}

/* summaryText() builds the standard status message for whichever
   records are currently on screen. */
function summaryText() {
    return "Showing " + currentRecords.length + " of " + collections.length +
           " register entries - " + totalLitres(currentRecords).toFixed(1) +
           " litres, " + toMoney(totalAmount(paymentAmounts(currentRecords))) +
           " today.";
}

/* selectRow() marks one table row as chosen and describes that
   farmer's collection in the status strip. */
function selectRow(row, record) {
    const allRows = document.querySelectorAll(".log-window tbody tr");

    allRows.forEach(function (otherRow) {
        otherRow.classList.remove("selected");
    });

    row.classList.add("selected");

    setStatus(record.farmer + ": " + record.liters.toFixed(1) +
              " litres at " + record.fat.toFixed(1) + "% fat, " +
              toMoney(record.liters * record.rate) + " at " + record.rate +
              " Rs per litre (" + record.shift + " shift).");
}

/* refreshRegister() re-reads the filter controls, redraws the list
   and the totals, and puts the standard message in the status
   strip. Every event finishes by calling this, so the list, the
   totals and the message always agree with each other. */
function refreshRegister() {
    const filterSelect = document.getElementById("collection-filter");
    let recordList = collections;

    /* filter() by litres, if a minimum has been chosen */
    if (filterSelect && filterSelect.value !== "all") {
        recordList = recordsAtLeast(Number(filterSelect.value));
    }

    /* filter() by shift, if a shift block has been clicked */
    if (currentShift !== "All") {
        recordList = recordList.filter(function (record) {
            return record.shift === currentShift;
        });
    }

    currentRecords = recordList;

    showRecords(recordList);
    showTotals(recordList);
    setStatus(summaryText());
}


/* ============================================================
   5. STARTING THE APP AND CONNECTING THE EVENTS
   ============================================================ */

function startApp() {

    /* ---- change event: the "Show collections" dropdown -------- */
    /* Choosing 15 litres and above calls filter(). */
    const filterSelect = document.getElementById("collection-filter");

    if (filterSelect) {
        filterSelect.addEventListener("change", refreshRegister);
    }

    /* ---- input event: the "Find a farmer" search box ----------- */
    /* Every keystroke calls find(). The typed text is only used for
       the search, never placed into the page as HTML. */
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

    /* ---- click event: the "Refresh Totals" button -------------- */
    const refreshButton = document.getElementById("refresh-totals");

    if (refreshButton) {
        refreshButton.addEventListener("click", function () {
            refreshRegister();

            setStatus("Totals recalculated at " +
                      new Date().toLocaleTimeString() + " from " +
                      collections.length + " register entries.");
        });
    }

    /* ---- mouseover / mouseout / click: the statistic cards ----- */
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

    /* ---- click event: the three shift summary blocks ---------- */
    /* Clicking "Morning Shift" shows only the morning cans, using
       filter() inside refreshRegister(). Clicking the same block
       again removes that filter.

       The shift to filter on is read from the data-shift attribute
       of the block, NOT from its heading text. The heading says
       "Morning Shift" but the records store "Morning", so the
       machine value is kept separately in the HTML. */
    const shiftBlocks = document.querySelectorAll("#shift-summary .flex-item");

    if (shiftBlocks.length > 0) {
        shiftBlocks.forEach(function (block) {
            block.addEventListener("click", function () {
                const shiftName = block.getAttribute("data-shift");

                /* clicking the same block again clears the filter */
                currentShift = (currentShift === shiftName) ? "All" : shiftName;

                shiftBlocks.forEach(function (otherBlock) {
                    otherBlock.classList.remove("selected");
                });

                if (currentShift !== "All") {
                    block.classList.add("selected");
                }

                refreshRegister();

                if (currentShift === "All") {
                    setStatus("All shifts are shown again. " + summaryText());
                } else {
                    setStatus("Showing the " + currentShift + " shift only - " +
                              currentRecords.length + " cans.");
                }
            });
        });
    }

    /* ---- first paint: draw the register as the page opens ------ */
    refreshRegister();

    /* ---- ASSIGNMENT 7 : the farmer registration form ---------- */
    /* Started from here so that both halves of the script begin
       from the same DOMContentLoaded event. startFarmerForm()
       stops at once on a page that has no form, so the pages of
       Assignment 1-6 are not affected. */
    startFarmerForm();

    /* ---- ASSIGNMENT 11 : DOM manipulation on the collection log - */
    /* Same idea: it stops at once on a page that has no collection
       log, so the Assignment 6 and 7 pages are not affected. */
    startCollectionLog();
}

/* DOMContentLoaded fires once the browser has finished reading the
   whole page, which is the safest moment to start looking for
   elements. */
document.addEventListener("DOMContentLoaded", startApp);


/* ============================================================
   6. ASSIGNMENT 7 : FARMER REGISTRATION FORM + FORM VALIDATION
   Page: frontend/pages/farmers.html

   WHAT THE FORM IS FOR
   --------------------
   A new milk producer joins the society. The clerk types the
   farmer's name, mobile number, village, average daily milk and
   fat percentage. JavaScript checks every box before the record
   is accepted:

     - a wrong entry is rejected with a message under that box
     - a correct form shows a green success message and adds one
       row to the "Farmers Registered in This Session" table

   THERE IS NO DATABASE YET (that is Assignment 13), so nothing
   is sent anywhere. preventDefault() keeps the form in the
   browser, and the records live in the array below until the
   page is closed.

   THE CODE IS SPLIT IN TWO HALVES ON PURPOSE:
     7.1  VALIDATION LOGIC  - looks at a value, returns a message
     7.2  THE INTERFACE     - puts that message on the page
   A validation rule never touches the page, so it can be read and
   reused on its own.
   ============================================================ */


/* ============================================================
   6.1 THE DATA
   ============================================================ */

/* Farmers registered through the form during this visit. It is a
   normal array, the same style as the `collections` array above.
   push() adds a new farmer, and reduce() adds up the litres. */
const registeredFarmers = [];

/* Becomes true after the first submit attempt. From that moment on
   the input event re-checks a field while the farmer is typing,
   so a corrected value removes its error message at once. */
let formWasSubmitted = false;


/* ============================================================
   6.2 VALIDATION LOGIC
   Each function receives ONE VALUE (text typed in a box) and
   returns a MESSAGE:
       "" (empty text)  -> the value is acceptable
       "some message"   -> the value is rejected

   These functions do NOT look at the page. They only decide
   whether a value is right or wrong.
   ============================================================ */

/* Farmer Name: not empty, at least 3 characters, letters and
   spaces only, and it must begin with a letter.
   .trim() removes the spaces typed at either end. */
function validateFarmerName(value) {
    const name = value.trim();

    if (name === "") {
        return "Please enter the farmer name.";
    }

    if (name.length < 3) {
        return "Farmer name must be at least 3 characters long.";
    }

    /* /^[A-Za-z][A-Za-z .'-]*$/ means: first character a letter,
       after that only letters, spaces, dot, apostrophe or hyphen. */
    if (!/^[A-Za-z][A-Za-z .'-]*$/.test(name)) {
        return "Farmer name can contain letters, spaces, . ' and - only.";
    }

    return "";
}

/* Mobile Number: 10 digits, and the first digit must be 6, 7, 8
   or 9 - the way Indian mobile numbers start.
   Spaces are removed first, so "98765 43210" is also accepted. */
function validateMobile(value) {
    const mobile = value.replace(/\s/g, "");     /* \s = any space */

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

    return "";
}

/* Email: not compulsory, but if something is typed it has to look
   like an email address: text, then @, then a domain with a dot. */
function validateEmail(value) {
    const email = value.trim();

    if (email === "") {
        return "";            /* the farmer has no email - allowed */
    }

    if (!/^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/.test(email)) {
        return "Enter a valid email address, for example name@example.com.";
    }

    return "";
}

/* Village / Address: not empty and at least 3 characters, because
   a single letter is never a usable address. */
function validateVillage(value) {
    const village = value.trim();

    if (village === "") {
        return "Please enter the village or address.";
    }

    if (village.length < 3) {
        return "Village or address must be at least 3 characters long.";
    }

    return "";
}

/* Milk Quantity: a number, and more than 0 litres.
   Number("abc") gives NaN, which is how a non-number is detected.
   isNaN() asks "is this not a number?". */
function validateMilkQuantity(value) {
    const litres = Number(value);

    if (value.trim() === "") {
        return "Please enter the milk quantity in litres.";
    }

    if (isNaN(litres)) {
        return "Milk quantity must be a number.";
    }

    if (litres <= 0) {
        return "Milk quantity must be greater than 0 litres.";
    }

    if (litres > 100) {
        return "Milk quantity cannot be more than 100 litres a day.";
    }

    return "";
}

/* Fat Percentage: a number between 3% and 8%. That is the range the
   society's rate board accepts - a cow's milk is never tested
   outside it. */
function validateFatPercentage(value) {
    const fat = Number(value);

    if (value.trim() === "") {
        return "Please enter the fat percentage.";
    }

    if (isNaN(fat)) {
        return "Fat percentage must be a number.";
    }

    if (fat < 3) {
        return "Fat percentage cannot be below 3%.";
    }

    if (fat > 8) {
        return "Fat percentage cannot be above 8%.";
    }

    return "";
}

/* The rate board rule, the same one printed on the fixed rate
   board of the collection centre page: fat 3.5% and above is paid
   at 42 Rs per litre, below 3.5% at 40 Rs per litre. */
function rateForFat(fat) {
    if (fat >= 3.5) {
        return 42;
    }

    return 40;
}


/* ============================================================
   6.3 THE INTERFACE PART
   These functions read the form and change what is on the page.
   ============================================================ */

/* One row of the list below: which box to read, where its error
   message goes, and which check function to use. Writing the rules
   in a list like this means a new field is added in ONE place. */
const formRules = [
    { inputId: "farmer-name",    errorId: "farmer-name-error",    check: validateFarmerName },
    { inputId: "farmer-mobile",  errorId: "farmer-mobile-error",  check: validateMobile },
    { inputId: "farmer-email",   errorId: "farmer-email-error",   check: validateEmail },
    { inputId: "farmer-village", errorId: "farmer-village-error", check: validateVillage },
    { inputId: "milk-quantity",  errorId: "milk-quantity-error",  check: validateMilkQuantity },
    { inputId: "fat-percentage", errorId: "fat-percentage-error", check: validateFatPercentage }
];

/* fieldValue() safely reads one box: .trim() removes stray spaces
   and an empty string is returned if the box is not on the page. */
function fieldValue(inputId) {
    const input = document.getElementById(inputId);

    if (!input) {
        return "";
    }

    return input.value.trim();
}

/* showFieldError() puts a field into its ERROR state: the input
   gets the class "field-error" (red border in style.css) and the
   message is written under it. textContent is used, never
   innerHTML, so typed text can never become HTML. */
function showFieldError(rule, message) {
    const input = document.getElementById(rule.inputId);
    const errorBox = document.getElementById(rule.errorId);

    if (!input || !errorBox) {
        return;
    }

    input.classList.add("field-error");
    input.setAttribute("aria-invalid", "true");     /* for screen readers */
    errorBox.textContent = message;
}

/* clearFieldError() removes the error state of one field. */
function clearFieldError(rule) {
    const input = document.getElementById(rule.inputId);
    const errorBox = document.getElementById(rule.errorId);

    if (!input || !errorBox) {
        return;
    }

    input.classList.remove("field-error");
    input.removeAttribute("aria-invalid");
    errorBox.textContent = "";
}

/* clearAllFieldErrors() is used by the reset event. */
function clearAllFieldErrors() {
    formRules.forEach(function (rule) {
        clearFieldError(rule);
    });
}

/* checkField() runs ONE rule and updates the page.
   Returns true when the value is acceptable, false when it is not.
   This is the bridge between the two halves of the code: the check
   function decides, and this function shows the result. */
function checkField(rule) {
    const input = document.getElementById(rule.inputId);

    if (!input) {
        return true;            /* field not on this page - skip it */
    }

    const message = rule.check(input.value);

    if (message === "") {
        clearFieldError(rule);
        return true;
    }

    showFieldError(rule, message);
    return false;
}

/* checkWholeForm() runs every rule and returns HOW MANY fields
   failed, so the clerk is told the number of mistakes, not only
   the first one. */
function checkWholeForm() {
    let errorCount = 0;

    formRules.forEach(function (rule) {
        const passed = checkField(rule);

        if (!passed) {
            errorCount = errorCount + 1;
        }
    });

    return errorCount;
}

/* focusFirstError() puts the cursor in the first box that failed,
   so the farmer knows exactly where to start correcting. */
function focusFirstError() {
    for (let index = 0; index < formRules.length; index++) {
        const input = document.getElementById(formRules[index].inputId);

        if (input && input.classList.contains("field-error")) {
            input.focus();
            return;
        }
    }
}

/* setFormStatus() writes the single message box above the form.
   The class decides the colour: .form-message.ok is green and
   .form-message.error is red. */
function setFormStatus(message, isSuccess) {
    const statusBox = document.getElementById("farmer-form-status");

    if (!statusBox) {
        return;
    }

    statusBox.textContent = message;
    statusBox.classList.remove("ok");
    statusBox.classList.remove("error");

    if (isSuccess) {
        statusBox.classList.add("ok");
    } else {
        statusBox.classList.add("error");
    }
}

/* readFormValues() collects the checked values into ONE object.
   This is the object that a database row would be made from in
   Assignment 13. */
function readFormValues() {
    const quantity = Number(fieldValue("milk-quantity"));
    const fat = Number(fieldValue("fat-percentage"));
    const rate = rateForFat(fat);

    return {
        name: fieldValue("farmer-name"),
        mobile: fieldValue("farmer-mobile"),
        email: fieldValue("farmer-email"),
        village: fieldValue("farmer-village"),
        quantity: quantity,
        fat: fat,
        rate: rate,
        amount: quantity * rate
    };
}

/* successText() builds the green message shown after a
   registration. */
function successText(farmer) {
    let message = "Registration successful. " + farmer.name + " of " +
                  farmer.village + " - " + farmer.quantity.toFixed(1) +
                  " litres at " + farmer.fat.toFixed(1) + "% fat = " +
                  toMoney(farmer.amount) + " at " + farmer.rate +
                  " Rs per litre. Mobile " + farmer.mobile + ".";

    if (farmer.email !== "") {
        message = message + " Email " + farmer.email + ".";
    }

    return message;
}

/* totalRegisteredLitres() -> reduce() over the farmers registered
   through the form. */
function totalRegisteredLitres() {
    return registeredFarmers.reduce(function (total, farmer) {
        return total + farmer.quantity;
    }, 0);
}

/* renderRegisteredFarmers() draws the table below the form with
   forEach(), one <tr> per registered farmer, and updates the
   count line above it. */
function renderRegisteredFarmers() {
    const tableBody = document.getElementById("registered-body");
    const countBox = document.getElementById("registered-count");

    if (!tableBody) {
        return;
    }

    tableBody.innerHTML = "";          /* clear the old rows first */

    registeredFarmers.forEach(function (farmer, position) {
        const row = document.createElement("tr");

        row.innerHTML =
            "<td>" + (position + 1) + "</td>" +
            "<td>" + farmer.name + "</td>" +
            "<td>" + farmer.mobile + "</td>" +
            "<td>" + farmer.village + "</td>" +
            "<td>" + farmer.quantity.toFixed(1) + "</td>" +
            "<td>" + farmer.fat.toFixed(1) + "%</td>" +
            "<td>" + farmer.rate + "</td>" +
            "<td>" + toMoney(farmer.amount) + "</td>";

        tableBody.appendChild(row);
    });

    if (countBox) {
        if (registeredFarmers.length === 0) {
            countBox.textContent = "No farmer registered yet in this session.";
        } else {
            countBox.textContent = registeredFarmers.length +
                (registeredFarmers.length === 1 ? " farmer" : " farmers") +
                " registered in this session - " +
                totalRegisteredLitres().toFixed(1) +
                " litres a day between them.";
        }
    }
}

/* registerFarmer() is called ONLY when every field has passed.
   push() adds the farmer to the array and the table is redrawn. */
function registerFarmer(farmer) {
    registeredFarmers.push(farmer);
    renderRegisteredFarmers();
}

/* startFarmerForm() connects the four events of the form.
   It is written as its own function, and it returns immediately
   when the form is not on the page - that is why main.js can be
   loaded by every page of the project without errors. */
function startFarmerForm() {
    const form = document.getElementById("farmer-form");

    if (!form) {
        return;
    }

    /* ---- submit event : the final check --------------------- */
    /* This is the only event that can send the form anywhere.
       preventDefault() stops the browser's own sending, because
       there is no PHP page and no database yet. */
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        formWasSubmitted = true;

        const errorCount = checkWholeForm();

        if (errorCount > 0) {
            setFormStatus(errorCount + " field(s) need attention. " +
                          "Please correct the highlighted entries and " +
                          "submit the form again.", false);
            focusFirstError();
            return;
        }

        const farmer = readFormValues();

        registerFarmer(farmer);

        /* form.reset() empties every box AND fires the reset event,
           so the success message is written after this line. */
        form.reset();
        formWasSubmitted = false;
        setFormStatus(successText(farmer), true);
    });

    /* ---- blur and input events : checking while typing ------ */
    formRules.forEach(function (rule) {
        const input = document.getElementById(rule.inputId);

        if (!input) {
            return;
        }

        /* blur -> the box is left, check it now */
        input.addEventListener("blur", function () {
            checkField(rule);
        });

        /* input -> check again only if the box is already in the
           error state, so the message disappears as soon as the
           value becomes correct. */
        input.addEventListener("input", function () {
            const isShowingError = input.classList.contains("field-error");

            if (formWasSubmitted || isShowingError) {
                checkField(rule);
            }
        });
    });

    /* ---- reset event : the "Clear Form" button -------------- */
    /* This runs both when the button is pressed and when
       form.reset() is called after a successful registration. */
    form.addEventListener("reset", function () {
        clearAllFieldErrors();
    });

    renderRegisteredFarmers();
}


/* ============================================================
   7. ASSIGNMENT 11 : DOM MANIPULATION AND EVENT HANDLING
   Page: frontend/pages/collection-centre.html

   WHAT IS DEMONSTRATED HERE, IN THE ORDER OF THE PAGE
   ---------------------------------------------------
   1. THE ENTRY FORM ("Add a Collection Entry")
        submit  - build a brand new <tr>, put it in the table
        input   - clear a field's error while the clerk types
        reset   - remove every error message
        createElement() + appendChild() = CREATING elements
        textContent = WRITING text into a new cell

   2. THE LOG TOOLS (search box, dropdown, five buttons)
        input   - type a name -> rows are hidden or shown
        change  - choose a status -> rows are hidden or shown
        click   - select a row, highlight the biggest can, mark a
                  payment done, delete a row, delete the rows this
                  session added, or reset the whole view
        classList.add() / .remove() = CHANGING CLASSES
        style.display / style.width  = CHANGING STYLES
        remove()                        = REMOVING elements
        querySelector() / querySelectorAll() = FINDING elements

   3. THE SUMMARY UNDER THE LOG
        The three numbers and the progress bar are written with
        textContent and style.width, so they always match the rows
        that are really on the screen.

   WHY THE DOM IS USED INSTEAD OF THE ARRAY OF SECTION 1
   -----------------------------------------------------
   The collection log on this page is written in the HTML, so the
   only honest way to add, count, filter and delete rows is to read
   the rows back FROM the page. That is the whole point of DOM
   manipulation: the HTML file and the array in section 1 become one
   live document.

   NOTHING IS SENT ANYWHERE. There is no server and no database yet
   (that is Assignment 13), so a row added here disappears when the
   page is closed. preventDefault() on the submit event keeps the
   form in the browser.
   ============================================================ */


/* ============================================================
   7.1 READING THE LOG BACK OUT OF THE PAGE
   ============================================================ */

/* The number of litres the morning shift is expected to collect.
   It is only used to work out the width of the progress bar. */
const SHIFT_TARGET_LITRES = 1000;

/* logRows() -> every <tr> of the collection log.
   querySelectorAll() takes a CSS SELECTOR, so "#collection-log-body
   tr" means "every tr inside the element with that id". The result
   is a LIST (a NodeList) of elements, and it can be walked with
   forEach() exactly like an array. */
function logRows() {
    return document.querySelectorAll("#collection-log-body tr");
}

/* readLogEntry() reads ONE row back into a JavaScript object.
   row.querySelectorAll("td") finds the six cells of that row, and
   .textContent is the text the visitor can see inside a cell.
   Number() turns "18.5" into 18.5 so it can be added up later. */
function readLogEntry(row) {
    const cells = row.querySelectorAll("td");

    if (cells.length < 6) {
        return null;            /* not a real log row - skip it */
    }

    return {
        row: row,
        farmer: cells[0].textContent,
        liters: Number(cells[1].textContent),
        fat: Number(cells[2].textContent),
        snf: Number(cells[3].textContent),
        rate: Number(cells[4].textContent),
        status: cells[5].textContent,
        pending: cells[5].textContent.trim() === "Pending"
    };
}

/* logEntries() reads every row, so the page and the JavaScript
   always hold the same numbers. */
function logEntries() {
    const list = [];

    logRows().forEach(function (row) {
        const entry = readLogEntry(row);

        if (entry) {
            list.push(entry);
        }
    });

    return list;
}

/* visibleLogEntries() keeps only the rows that are on the screen.
   A hidden row still exists in the page, but row.style.display is
   "none", so it must not be counted in the summary. */
function visibleLogEntries() {
    return logEntries().filter(function (entry) {
        return entry.row.style.display !== "none";
    });
}


/* ============================================================
   7.2 SMALL WRITING HELPERS
   ============================================================ */

/* setTextById() writes text into any element of the page.
   document.getElementById() finds ONE element by its id, and
   textContent replaces whatever was inside it. textContent is used
   and never innerHTML, so text typed into a box can never become
   HTML. */
function setTextById(elementId, text) {
    const element = document.getElementById(elementId);

    if (!element) {
        return;
    }

    element.textContent = text;
}

/* setLogMessage() writes the one status strip of the log, so every
   action of this assignment has a single place to report itself. */
function setLogMessage(message) {
    setTextById("log-status-message", message);
}

/* onButtonClick() connects one button to one function. If the button
   is not on the page, nothing happens - that is how this section
   stays safe to load anywhere. */
function onButtonClick(elementId, handlerFunction) {
    const button = document.getElementById(elementId);

    if (button) {
        button.addEventListener("click", handlerFunction);
    }
}

/* clearLogSelection() removes the "selected" class from every row
   that has it. querySelectorAll(".selected") inside the log finds
   them all at once. */
function clearLogSelection() {
    const chosenRows = document.querySelectorAll("#collection-log-body tr.selected");

    chosenRows.forEach(function (row) {
        row.classList.remove("selected");
    });
}


/* ============================================================
   7.3 THE SUMMARY UNDER THE LOG
   ============================================================ */

/* updateLogSummary() rewrites the three numbers and the progress
   bar from the rows that are currently visible.

   reduce() adds up the litres (it is the same helper used by the
   Assignment 6 register) and filter() counts the pending rows. */
function updateLogSummary() {
    const entries = visibleLogEntries();
    const litres = totalLitres(entries);                        /* reduce */
    const pendingRows = entries.filter(function (entry) {       /* filter */
        return entry.pending;
    });

    setTextById("log-entries-count", entries.length);
    setTextById("log-litres-total", litres.toFixed(1) + " L");
    setTextById("log-pending-count", pendingRows.length +
                (pendingRows.length === 1 ? " row" : " rows"));

    /* STYLE CHANGE: the width of the gold bar is a percentage of the
       shift target. style.width is an inline style, exactly like the
       style="" attributes of Assignment 3, but set from JavaScript.
       Math.min() stops the bar from overflowing when the shift
       collects more than the target. */
    const progressBar = document.getElementById("shift-fill");

    if (progressBar) {
        const percent = Math.min(100, (litres / SHIFT_TARGET_LITRES) * 100);

        progressBar.style.width = percent.toFixed(1) + "%";
    }

    setTextById("shift-progress-text",
        "Morning shift target: " + SHIFT_TARGET_LITRES +
        " litres - " + litres.toFixed(1) + " litres in view (" +
        Math.min(100, (litres / SHIFT_TARGET_LITRES) * 100).toFixed(1) +
        "% of the target), " + entries.length + " of " +
        logRows().length + " entries shown.");
}

/* refreshPendingClasses() puts the "is-pending" class on every row
   whose payment is still due and takes it off the paid rows.
   classList.add() and .remove() change the CLASS, and the class is
   what style.css uses to colour the status cell red. */
function refreshPendingClasses() {
    logRows().forEach(function (row) {
        const entry = readLogEntry(row);

        if (!entry) {
            return;
        }

        if (entry.pending) {
            row.classList.add("is-pending");
        } else {
            row.classList.remove("is-pending");
        }
    });
}


/* ============================================================
   7.4 CLICK ON A ROW - SELECTING IT
   ============================================================ */

/* selectLogRow() is the click handler of every row. It removes the
   class from the row selected before, adds it to the clicked row and
   describes that can in the status strip. */
function selectLogRow(row) {
    const entry = readLogEntry(row);

    clearLogSelection();
    row.classList.add("selected");

    if (!entry) {
        return;
    }

    setLogMessage("Selected: " + entry.farmer + ", " +
                  entry.liters.toFixed(1) + " litres, " +
                  entry.fat.toFixed(1) + "% fat, " + entry.snf.toFixed(1) +
                  "% SNF, " + entry.rate + " Rs per litre, payment " +
                  entry.status + ".");
}

/* connectRowClicks() gives the click listener to every row of the
   log. It is run once, when the page opens, so it visits the 19 rows
   that were written in the HTML file. Rows created later connect
   themselves inside createLogEntryRow(). */
function connectRowClicks() {
    logRows().forEach(function (row) {
        row.addEventListener("click", function () {
            selectLogRow(row);
        });
    });
}


/* ============================================================
   7.5 THE SEARCH BOX AND THE DROPDOWN
   input AND change EVENTS
   ============================================================ */

/* applyLogFilter() hides the rows that do not match and shows the
   ones that do.

   row.style.display = "none" hides a row; setting it back to ""
   removes the inline style, so the row returns to the normal table
   layout. Hiding is done with a STYLE and not by deleting the row,
   because the clerk may want to see the whole log again - the
   "Reset Log View" button simply clears the search box and the
   dropdown. */
function applyLogFilter() {
    const searchBox = document.getElementById("log-search");
    const statusSelect = document.getElementById("log-status-filter");

    const wantedName = searchBox ? searchBox.value.trim().toLowerCase() : "";
    const wantedStatus = statusSelect ? statusSelect.value : "all";

    logRows().forEach(function (row) {
        const entry = readLogEntry(row);

        if (!entry) {
            return;
        }

        /* indexOf() returns -1 when the text is not inside the name,
           and any other number (0, 1, 2...) when it is. */
        const nameMatches = wantedName === "" ||
                            entry.farmer.toLowerCase().indexOf(wantedName) !== -1;

        const statusMatches = wantedStatus === "all" ||
                              entry.status === wantedStatus;

        if (nameMatches && statusMatches) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });

    updateLogSummary();
}


/* ============================================================
   7.6 THE FIVE BUTTONS - click EVENTS
   ============================================================ */

/* highlightLargestEntry() finds the biggest can that is on the
   screen and marks its row. reduce() is used with a condition: the
   running value is kept only when the new row is bigger.

   CLASS CHANGE: the class "peak-row" is removed from the row that
   had it and added to the biggest one, so only one row is ever
   marked. STYLE CHANGE: the same row also gets bold text, which is
   removed again together with the class. */
function highlightLargestEntry() {
    const oldPeakRows = document.querySelectorAll("#collection-log-body tr.peak-row");

    oldPeakRows.forEach(function (row) {
        row.classList.remove("peak-row");
        row.style.fontWeight = "";        /* undo the bold text */
    });

    const entries = visibleLogEntries();

    if (entries.length === 0) {
        setLogMessage("No entry is on the screen, so there is nothing to highlight.");
        return;
    }

    const biggest = entries.reduce(function (largest, entry) {
        if (entry.liters > largest.liters) {
            return entry;
        }

        return largest;
    });

    biggest.row.classList.add("peak-row");
    biggest.row.style.fontWeight = "bold";

    setLogMessage("Biggest can on screen: " + biggest.farmer + ", " +
                  biggest.liters.toFixed(1) + " litres. The row is marked " +
                  "with the class \"peak-row\".");
}

/* markSelectedAsPaid() UPDATES a row that is already on the page:
   the text of its last cell is replaced and its class is changed.
   This is what the payment counter does at 5:00 PM. */
function markSelectedAsPaid() {
    const selectedRow = document.querySelector("#collection-log-body tr.selected");

    if (!selectedRow) {
        setLogMessage("Select a row first - click any line of the log.");
        return;
    }

    const cells = selectedRow.querySelectorAll("td");
    const statusCell = cells[cells.length - 1];
    const farmerName = cells[0].textContent;

    statusCell.textContent = "Paid";          /* the text is replaced */
    selectedRow.classList.remove("is-pending"); /* the class is changed */

    updateLogSummary();

    setLogMessage(farmerName + "'s payment is marked as done. The status " +
                  "cell was updated and the \"is-pending\" class removed.");
}

/* removeSelectedRow() REMOVES one row from the page.
   querySelector() finds the first row that has the class, and
   remove() deletes the element itself - the element and all of its
   children leave the page, and the table closes up behind it.
   remove() is the short form of parent.removeChild(row). */
function removeSelectedRow() {
    const selectedRow = document.querySelector("#collection-log-body tr.selected");

    if (!selectedRow) {
        setLogMessage("Select a row first - click any line of the log.");
        return;
    }

    const entry = readLogEntry(selectedRow);

    selectedRow.remove();
    updateLogSummary();

    if (entry) {
        setLogMessage("Removed the row of " + entry.farmer + " (" +
                      entry.liters.toFixed(1) + " litres) from the log.");
    }
}

/* removeAddedRows() removes every row that this page created, and
   leaves the 19 rows that were written in the HTML file.

   The selector 'tr[data-added="yes"]' is an ATTRIBUTE selector: it
   picks the rows carrying that attribute. That attribute is set in
   createLogEntryRow(), so it is the mark of a row made by
   JavaScript. */
function removeAddedRows() {
    const addedRows = document.querySelectorAll("#collection-log-body tr[data-added='yes']");
    let removedCount = 0;

    addedRows.forEach(function (row) {
        row.remove();
        removedCount = removedCount + 1;
    });

    applyLogFilter();          /* re-shows the rows and the summary */
    setLogMessage(removedCount + " row(s) added in this session were " +
                  "removed. The " + logRows().length +
                  " rows written in the HTML file are untouched.");
}

/* resetLogView() clears the search box and the dropdown, takes the
   classes off every row and shows the whole log again. */
function resetLogView() {
    const searchBox = document.getElementById("log-search");
    const statusSelect = document.getElementById("log-status-filter");

    if (searchBox) {
        searchBox.value = "";
    }

    if (statusSelect) {
        statusSelect.value = "all";
    }

    logRows().forEach(function (row) {
        row.classList.remove("selected");
        row.classList.remove("peak-row");
        row.style.display = "";
        row.style.fontWeight = "";
    });

    updateLogSummary();
    setLogMessage("Log view reset - the search box is empty, the dropdown " +
                  "shows all entries and no row is marked.");
}


/* ============================================================
   7.7 THE ENTRY FORM - CREATING A NEW ROW
   ============================================================ */

/* The rules of the four fields of the entry form. Three of them are
   the very same functions the Assignment 7 farmer form uses, which
   shows that a validation rule is written once and reused. */
const logEntryRules = [
    { inputId: "log-farmer",   errorId: "log-farmer-error",   check: validateFarmerName },
    { inputId: "log-quantity", errorId: "log-quantity-error", check: validateMilkQuantity },
    { inputId: "log-fat",      errorId: "log-fat-error",      check: validateFatPercentage },
    { inputId: "log-snf",      errorId: "log-snf-error",      check: validateSnf }
];

/* SNF (non-fat solids) is tested between 3% and 10% by the lab, so
   the box is checked the same way as the fat box. */
function validateSnf(value) {
    const snf = Number(value);

    if (value.trim() === "") {
        return "Please enter the SNF percentage.";
    }

    if (isNaN(snf)) {
        return "SNF percentage must be a number.";
    }

    if (snf < 3) {
        return "SNF percentage cannot be below 3%.";
    }

    if (snf > 10) {
        return "SNF percentage cannot be above 10%.";
    }

    return "";
}

/* createLogEntryRow() BUILDS one row of the log.

   document.createElement("tr") makes a new element that is not on
   the page yet - it lives only in memory. The same is done for the
   six <td> cells. Each cell is filled with textContent (never
   innerHTML, so a farmer's typed name can never become HTML) and
   row.appendChild(cell) puts the cell inside the row.

   The finished row is returned; the caller decides where it goes. */
function createLogEntryRow(entry) {
    const row = document.createElement("tr");
    const cellValues = [
        entry.farmer,
        entry.liters.toFixed(1),
        entry.fat.toFixed(1) + "%",
        entry.snf.toFixed(1) + "%",
        String(entry.rate),
        entry.status
    ];

    cellValues.forEach(function (value) {
        const cell = document.createElement("td");

        cell.textContent = value;
        row.appendChild(cell);
    });

    /* the mark of a row made by JavaScript - used by the
       "Remove Added Rows" button */
    row.setAttribute("data-added", "yes");

    /* A row that is born in JavaScript has to be connected to the
       click event by JavaScript too - connectRowClicks() only visits
       the rows that were already on the page when it opened. */
    row.addEventListener("click", function () {
        selectLogRow(row);
    });

    return row;
}

/* addLogEntry() puts the new row at the bottom of the log with
   tbody.appendChild(row), then refreshes the summary. */
function addLogEntry(entry) {
    const tableBody = document.getElementById("collection-log-body");
    const newRow = createLogEntryRow(entry);

    clearLogSelection();
    tableBody.appendChild(newRow);
    applyLogFilter();          /* also rewrites the summary */

    setLogMessage("Added " + entry.farmer + ", " + entry.liters.toFixed(1) +
                  " litres at " + entry.fat.toFixed(1) + "% fat, " +
                  entry.rate + " Rs per litre (" + entry.status +
                  "). A new <tr> was created and appended to the log.");
}

/* readLogEntryForm() collects the four boxes into one object. The
   rate is NOT typed by the clerk - rateForFat() is the rate board
   rule of Assignment 7, so the rate always matches the fat. */
function readLogEntryForm() {
    const fat = Number(fieldValue("log-fat"));
    const statusSelect = document.getElementById("log-status");

    return {
        farmer: fieldValue("log-farmer"),
        liters: Number(fieldValue("log-quantity")),
        fat: fat,
        snf: Number(fieldValue("log-snf")),
        rate: rateForFat(fat),
        status: statusSelect ? statusSelect.value : "Pending"
    };
}

/* setEntryMessage() writes the message box above the entry form and
   gives it a class - that class is what turns the box green or red
   in style.css (Assignment 7). */
function setEntryMessage(message, isSuccess) {
    const messageBox = document.getElementById("log-entry-message");

    if (!messageBox) {
        return;
    }

    messageBox.textContent = message;
    messageBox.classList.remove("ok");
    messageBox.classList.remove("error");

    if (isSuccess) {
        messageBox.classList.add("ok");
    } else {
        messageBox.classList.add("error");
    }
}

/* focusFirstLogError() puts the cursor in the first rejected box of
   the entry form. */
function focusFirstLogError() {
    for (let index = 0; index < logEntryRules.length; index++) {
        const input = document.getElementById(logEntryRules[index].inputId);

        if (input && input.classList.contains("field-error")) {
            input.focus();
            return;
        }
    }
}

/* startLogDom() connects every event of Assignment 11 and does the
   first update of the page. Like startFarmerForm() it returns at
   once when the log is not on the page. */
function startCollectionLog() {
    const tableBody = document.getElementById("collection-log-body");

    if (!tableBody) {
        return;
    }

    /* ---- first paint of the part this section owns ---------- */
    refreshPendingClasses();
    connectRowClicks();
    updateLogSummary();

    /* ---- input event : the search box ----------------------- */
    const searchBox = document.getElementById("log-search");

    if (searchBox) {
        searchBox.addEventListener("input", applyLogFilter);
    }

    /* ---- change event : the status dropdown ----------------- */
    const statusSelect = document.getElementById("log-status-filter");

    if (statusSelect) {
        statusSelect.addEventListener("change", applyLogFilter);
    }

    /* ---- click events : the five buttons of the log ---------- */
    onButtonClick("log-highlight-largest", highlightLargestEntry);
    onButtonClick("log-mark-paid", markSelectedAsPaid);
    onButtonClick("log-remove-selected", removeSelectedRow);
    onButtonClick("log-remove-added", removeAddedRows);
    onButtonClick("log-reset-view", resetLogView);

    /* ---- the entry form ------------------------------------- */
    startLogEntryForm();
}

/* startLogEntryForm() connects the submit, input and reset events of
   the "Add a Collection Entry" form. */
function startLogEntryForm() {
    const form = document.getElementById("log-entry-form");

    if (!form) {
        return;
    }

    /* ---- submit event : check, then create the new row ------- */
    form.addEventListener("submit", function (event) {
        event.preventDefault();     /* no server yet - stay in the page */

        let errorCount = 0;

        logEntryRules.forEach(function (rule) {
            if (!checkField(rule)) {
                errorCount = errorCount + 1;
            }
        });

        if (errorCount > 0) {
            setEntryMessage(errorCount + " field(s) of the entry need " +
                            "attention. Please correct the highlighted boxes " +
                            "and press \"Add To Log\" again.", false);
            focusFirstLogError();
            return;
        }

        const entry = readLogEntryForm();

        addLogEntry(entry);

        /* form.reset() empties the four boxes AND fires the reset
           event, which clears the error messages. */
        form.reset();
        setEntryMessage("Entry accepted. " + entry.farmer + " - " +
                        entry.liters.toFixed(1) + " litres at " +
                        entry.rate + " Rs per litre. The row has been " +
                        "added to the log below.", true);
    });

    /* ---- input event : clear an error while typing ---------- */
    logEntryRules.forEach(function (rule) {
        const input = document.getElementById(rule.inputId);

        if (!input) {
            return;
        }

        input.addEventListener("input", function () {
            const isShowingError = input.classList.contains("field-error");

            if (isShowingError) {
                checkField(rule);
            }
        });
    });

    /* ---- reset event : the "Clear Entry" button ------------- */
    form.addEventListener("reset", function () {
        logEntryRules.forEach(function (rule) {
            clearFieldError(rule);
        });
    });
}
