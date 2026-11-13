# CRM UI and dashboard audit

Audited on 13 November 2026.

## Implemented

- Both dashboard charts have a fixed 180 x 180 CSS-pixel container, reduced to 156 x 156 on narrow screens. Chart.js uses `maintainAspectRatio: false`.
- Added visible counts, proportions, totals, INR amounts, and a separate signed net balance. Removed the misleading income/expense/profit pie: the net balance is derived from credit and debit and must not be an additional slice.
- Labeled the overview as all time and current statuses. These counts cannot establish historical conversion rates.
- Added loading, request failure, zero-data, signed-amount, and missing-chart-library states. Counts still return when financial results are empty.
- Standardized action colors, hover states, button heights, 6px table-action separation, 12px filter gaps, modal action spacing, and wrapping toolbars.
- Fixed legacy CSS overriding the sidebar offset and active-link color. Wide record tables scroll inside their card rather than stretching the page.
- Moved the user avatar, name, and role to the top-right navbar.

## Verification and limits

- Rendered 35 CRM template fixtures, including add/edit forms, profiles, list pages, dashboard, renewals, accounts, admin pages, and offline view. All rendered successfully with synthetic inputs.
- Browser DOM checks across all 35 fixtures found no document-level horizontal overflow at desktop and mobile viewport settings after fixes. Visible `.btn` controls were at least 32 CSS pixels high. The browser's effective inner width differs from requested viewport sizing; measurements used actual `innerWidth`.
- Browser inspection confirmed both desktop chart containers measured 180 x 180; representative row-action variants retained their semantic foreground/background colors, 3px margins on each side, and 32px minimum height. Filter groups measured 12px gaps.
- Used sample data only. Fixture scripts suppressed application requests and form submissions; this was visual/layout QA, not an authenticated end-to-end test. Dynamic table data, database totals, permission enforcement, form saves, deletions, and invoice downloads were not exercised. Print-only invoices were outside the shared interactive button theme.
- `php artisan view:cache`, PHP syntax checking, JavaScript syntax checking, and `node tests/Frontend/dashboard.cjs` passed. The JavaScript checks cover ordinary values, negative net balance, zero totals, signed inputs, failed requests, chartless roles, and missing Chart.js.

## Recommended next data improvements

1. Add a shared reporting period and previous-period comparison. Compute trends from dated records; do not invent growth percentages from current totals.
2. Add an operational queue for renewals due, pending follow-ups, and outstanding balances, with links into the existing filtered lists. Agree on due-date and payment-status definitions before calculating these.
3. Reconcile financial coverage before calling the total revenue or profit. The existing counts query includes customer/event credits and trainer/office debits but omits yoga-center receipts; it does not distinguish invoiced, partially paid, and collected amounts.
4. Align dashboard record scopes. Role 3 leads/customers use creator filters while rejected records are currently global; the present total is labeled "Total shown" rather than a universal customer funnel.
5. Fix the existing unread-leads calculation before exposing it as a KPI: the non-role-3 branch does not filter `read_status = 0`. Trainer/recruit unread values are already available and are displayed.
6. Replace full-row retrieval and collection counting in `DashboardController::counts` with database count/aggregate queries after verifying role scopes. This will reduce memory and response time as records grow.
