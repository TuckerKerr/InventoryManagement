# Inventory code review

Reviewed September 8, 2026. Scope: the Desktop `inventory` working tree, including its PHP endpoints, three dashboard pages, login UI, and shared CSS. Application source was not changed. Git already showed deleted `query/db_conn.php` and `query/debug.log`; those deletions were preserved. Other Desktop projects were not reviewed.

This is a source review, not a production penetration test. PHP was not available on PATH, and database configuration, schema, routines, triggers, and server configuration were absent. Database-dependent findings describe the visible application behavior; unseen constraints or server controls may limit particular effects.

## Priority 1 — address before deployment

### 1. Direct inventory requests bypass login and staff checks

**Locations:** `main.php:20`, `admin_page.php:21`, `query/ModelAR.php:13`, `query/QuantityChange.php:13`, `query/openEQ.php:15`, `query/expand.php:1`.

Page access depends on editable browser `sessionStorage`. Write endpoints start a session but accept `Unknown User` when no user is logged in; read endpoints have no login check. Requests sent directly to these scripts can therefore read or mutate inventory without application-level authorization when the database is configured. Hiding the staff menu is not permission enforcement. No CSRF token validation is present in the mutation scripts.

**Change:** introduce a shared server-side session guard before any output or database work. Require authenticated sessions for inventory access and enforce the intended staff/campus permissions per operation. Return 401/403 appropriately. Require POST and validate a session-bound CSRF token for changes. Treat browser storage only as display state.

**Verify:** requests without a session cannot read or write inventory; a non-staff account cannot invoke staff operations directly; unauthorized campuses and missing/invalid CSRF tokens are rejected.

### 2. Request values are interpolated into SQL identifiers

**Locations:** `query/expand.php:10`, `query/expand.php:53`, `query/expand.php:58`, `query/QuantityChange.php:21`, `query/QuantityChange.php:27`, `query/ModelAR.php:19`, `query/ModelAR.php:30`.

The `search` query parameter becomes an `ORDER BY` expression, while delivery parameters become table names. Preparing the other values does not protect these interpolated portions. A crafted request can alter SQL structure; the extent depends on database privileges and driver behavior.

**Change:** map request keys to fixed, explicitly allowed table and column identifiers. Reject unknown keys with HTTP 400. Continue binding data values; placeholders cannot substitute table or column names.

**Verify:** unknown sort keys and delivery types are rejected before database execution. Valid allowlisted types and columns still work.

### 3. Search and inventory rendering allow HTML injection/XSS

**Locations:** `query/searchTest.php:45`, `query/searchTest.php:54`, `main.php:662`, `main.php:703`, `main.php:736`, `main.php:771`; similar patterns in `main_test.php` and `admin_page.php`.

The search endpoint concatenates the supplied search term and database values into HTML without escaping. The dashboard inserts those responses and interpolated database fields through `innerHTML`. Search text can become markup, and saved model/location values can become stored markup in another user's browser.

**Change:** return structured JSON and build cells with `textContent`, creating buttons and event listeners through DOM methods. Where server-rendered HTML remains, escape text and attributes with `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`. Encode query parameters with `URLSearchParams`.

**Verify:** search strings and saved text containing `<`, `>`, quotes, and ampersands display literally and never create elements or execute handlers.

### 4. Quantity removal checks and records the wrong campus

**Locations:** `query/QuantityChange.php:36–70`.

The availability SELECT filters only by model and checks the first result. The UPDATE filters by model and campus. The history INSERT SELECT again filters only by model. For a model with 10 units at one campus and 1 at another, removing 5 at the second campus may pass the first campus's availability check, attempt a negative balance, and create history rows for both campuses. The history copies the stock record's original `Name` instead of the current withdrawing user.

**Change:** identify one stock row by a stable ID or exact model/campus pair, apply it to every query, and record the authenticated actor explicitly. Use `=` for exact identifiers instead of `LIKE`, which treats `%` and `_` as wildcards.

**Verify:** seed the same model at two campuses with different quantities. A withdrawal affects only its target campus, records exactly one event, and attributes it to the withdrawing user.

### 5. Stock updates lack positive-integer validation and concurrency protection

**Locations:** `query/QuantityChange.php:23–79`.

`intval()` silently converts malformed/fractional input and accepts negatives; a negative removal adds stock. The separate availability check and decrement can race: two withdrawals can both see sufficient stock and then overdraw it. The decrement and history insert are not in one transaction, so a history failure leaves the stock changed.

**Change:** reject anything except an integer greater than zero within an explicit business limit. In a transaction, use an atomic conditional decrement on the exact stock row (`... SET Num_units = Num_units - :amount WHERE ... AND Num_units >= :required`), require exactly one affected row, then insert the history and commit. Use distinct bound parameter names as shown. Add appropriate uniqueness/nonnegative constraints after checking the actual schema.

**Verify:** negative, zero, fractional, malformed, and excessive quantities fail. Two simultaneous withdrawals cannot overdraw stock, and a forced history failure rolls back the balance change.

### 6. Toner/model operations can leave partially updated sticker state

**Locations:** `query/tonerAdd.php:31–70`, `query/tonerRetrieval.php:20–36`, `query/ModelAR.php:45–86`, `query/ModelAR.php:98–133`.

Related writes are issued without an encompassing application transaction. For example, toner addition marks a sticker in use before inserting the toner record; an insert failure leaves that sticker reserved. Model removal fetches one sticker but deletes all matching inventory rows, potentially leaving additional stickers in use if multiple matches exist. Sticker selection and claiming are separate calls; concurrent allocation safety cannot be established without the missing routine definition.

**Change:** make each complete operation transactional, atomically claim an available sticker using appropriate locking, and fail clearly when none is available. Delete by an exact identity; either enforce one stock row per model/campus or release every affected sticker. Decide explicitly how to handle deleting a model with nonzero inventory.

**Verify:** injected failures leave stock and sticker state unchanged. Concurrent additions receive distinct stickers. Model removal cannot silently delete nonzero inventory or leave orphaned sticker reservations.

### 7. The email update flow does not prove account ownership

**Location:** `register.php:14–36`.

A request containing a username and an email, but no password, can assign an email to an existing account whose email is blank. No authenticated identity or ownership check is required. Separately, registration proves only that a supplied worker name is active and that the supplied email ends in `@jwu.edu`; it does not verify that the requester owns that address or worker identity.

**Change:** require authenticated ownership for email updates, deriving the account ID from the session. Establish new accounts through verified email/invitations or institutional sign-in linked to the roster, rather than a self-asserted name/domain suffix.

**Verify:** an anonymous request cannot set another user's email, and an unverified requester cannot claim a roster identity.

## Priority 2 — correctness and operational fixes

### 8. A fresh checkout cannot connect to the database

**Locations:** `login.php:10`, `register.php:5`, `query/expand.php:2`, `query/QuantityChange.php:5`.

Connection files are missing, includes use inconsistent relative paths, and no schema/routine export or migration is provided. Git also tracks a locally deleted connection file and debug log; previous contents were not inspected for secrets.

**Change:** add sanitized examples, deterministic `__DIR__` imports, a documented private configuration mechanism, and schema migrations or a sanitized export. Add ignore rules and remove sensitive tracked files from future commits deliberately. If prior commits contain live credentials, rotate them; ignoring a file does not erase its history.

**Verify:** a new checkout can start against a disposable database using only documented setup and private environment configuration.

### 9. Timer code raises an error and refreshes every second after five minutes

**Locations:** `main.php:62–93`, `admin_page.php:64–80`.

`trackIdleTime` is commented out but still passed to `setInterval`, causing `ReferenceError: trackIdleTime is not defined`. Both reset functions assign `idleTimer`, whereas the counters are named `idleTime` and `refreshTime`. Consequently `refreshTime` stays above its limit after the first refresh and schedules another batch every second.

**Change:** consistently implement or remove idle tracking; reset `idleTime` and `refreshTime` correctly. A single five-minute refresh interval is simpler. Avoid overlapping requests and enforce any security timeout on the server.

**Verified locally:** executing the first inline script in a Node VM with minimal DOM/timer stubs reproduced the undefined-function error in both pages. Simulating 302 one-second ticks reproduced three refresh batches instead of one. This was not a full browser test.

### 10. Login/logout and JSON response handling disagree

**Locations:** `index.html:193`, `index.html:212–218`, `main.php:95–100`, `login.php:70–92`, `main.php:829–838`.

Login returns failure details under `message`, but the UI reads `error`. Registration parses a string and then reads `data.name`, writes a fake logged-in flag, and redirects to the login page; the server does not establish that new account's authenticated session. Logout removes only browser values, uses `username` rather than the stored `userName`, and leaves the PHP session active. Some inventory forms close/reset on any text response, including database failure text.

**Change:** standardize JSON responses and HTTP status codes, check both `response.ok` and application success, display `message`, and preserve forms on errors. After registration, require login or deliberately establish a server session. Add a server logout endpoint that destroys the session/cookie, and clear the correct UI storage keys. Regenerate session IDs after successful authentication; do not establish normal access until required account checks pass.

**Verify:** failure messages are visible, failed mutations preserve input, registration has an unambiguous next step, and the old session cannot access endpoints after logout.

### 11. Login logging exposes password hashes and account data

**Location:** `login.php:66`; database exception responses throughout `query/`.

The fetched user row contains `password_hash`, email, and ID and is sent wholesale to `debugLog`. That unnecessarily puts password hashes and personal information in server logs. Several endpoints also return raw database exceptions, and toner/open-equipment additions enable displayed PHP errors.

**Change:** remove row/session dumps, log only minimal event metadata, keep detailed exceptions in restricted server logs, and return generic structured failures to clients. Review existing logs for sensitive contents and apply suitable access/retention controls.

**Verify:** successful and failed requests never place hashes or credentials in client responses or application logs.

### 12. Summary queries mishandle strict SQL and empty results

**Locations:** `query/openCount.php:38–55`, `query/chartData.php:15–64`.

`openCount.php` selects `COUNT(*)` together with nonaggregated equipment columns without grouping. Under `ONLY_FULL_GROUP_BY`, this query can fail; the extra fields are not used by the response. `$finalData` is never initialized when no models exist. `chartData.php` similarly leaves `$hardware` undefined for empty data and appends one label per model but potentially zero or multiple quantities, allowing array positions to become misaligned depending on view results.

**Change:** select only the count or group deliberately, constrain counts by equipment type as appropriate, initialize every output collection, and emit one object per model with its type and quantity (using an explicit aggregate/default zero). Consider a grouped query to replace one query per model.

**Verify:** empty databases return valid empty arrays, strict SQL mode succeeds, and every chart label has exactly one corresponding count.

### 13. Printing uses the globally latest toner record

**Locations:** `query/recentSticker.php:12`, `main.php:1216–1241`.

The print action fetches the last inserted toner record across all users. If another person adds toner between a user's save and print, that user can print the other record's sticker.

**Change:** return the allocated sticker ID from `tonerAdd.php` and print that explicit ID, or print a selected toner record. Validate access to that record rather than querying the global latest item.

**Verify:** interleaved additions by two users each print the sticker associated with their own saved record.

## Maintainability improvements

- Extract duplicated dashboard JavaScript and shared PHP connection/authentication/response code. Decide whether `main_test.php` remains a supported page or belongs in development-only material.
- Replace `Object.values(row)` rendering with named field mappings so database column order cannot silently change the interface.
- Add unique HTML IDs: multiple forms reuse identifiers such as `Campus` and `Type-of-Delivery`. Scope form queries to their form element.
- Pin frontend dependency versions and document CDN availability requirements. Chart.js currently uses an unversioned CDN reference.
- Remove generated NAS `@eaDir` thumbnails from source control and restore/remove the missing favicon reference.
- Fix `style.css:762`: `var(#333)` is invalid; use `#333` or a defined CSS custom property.
- Add targeted integration tests for the security and inventory invariants above before broader refactoring.

## Validation completed

- Inventoried project files, read PHP endpoints, traced frontend callers, and checked Git status/tracked paths.
- Parsed all inline JavaScript with Node's `vm.Script`: one block in `index.html` and two each in `main.php`, `main_test.php`, and `admin_page.php`; all parsed successfully.
- Reproduced the two timer defects described above using the actual first inline scripts in a minimal VM harness.
- Did not run PHP lint, database integration tests, live requests, or a full browser session. Missing PHP/database dependencies prevent a claim that the application runs correctly.

Suggested sequence: restore reproducible local setup; add authorization and safe SQL/rendering; fix transactional stock operations and identity flows; repair UI/session/reporting behavior; then consolidate duplicated code.
