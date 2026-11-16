# Financial reporting rules

Updated 17 November 2026. `App\Services\FinancialReport` now supplies Dashboard, Summary and Ledger.

- Credits: recorded customer `full_payment`, event and yoga `totalPayAmount`.
- Debits: recorded customer `payTotrainer` plus office `expenseAmount`. This is not an independently reconciled bank balance or audited profit calculation.
- Live customer statuses, including renewal/expiry status 5, retain their recorded money. Explicitly deleted/voided records (status 0) and leads transferred to yoga (status 6) are excluded. Yoga status 0 is excluded. Events use existing records (deletion is physical).
- Missing categories become Uncategorized. Yoga receipts use Yoga Center.
- Cumulative amounts are authoritative. Active installment records and the latest matching stored renewal split that total by known payment dates; they are never added on top of the cumulative amount.
- Trainer debits use trainerPayDate; expenses use their entered expense date. End dates include the whole selected calendar day. Date and category filters combine.
- Undated entries remain in all-time totals. Date-range reports exclude them and show their amount and count. Customer creation dates are never substituted for missing payment dates.
- Legacy renewals overwrite totalPayDate and the renewal-history row. Older residual money cannot be accurately dated from that data; it stays undated. Inconsistent installment/renewal details are flagged for reconciliation and the cumulative amount is preserved as undated. Repairing missing history requires original payment records, not a guessed allocation.
- Calculations sum integer paise, show two decimal places, and retain negative net balances. Summary, ledger and dashboard use the same totals for the same scope.

Validation: isolated SQLite feature tests cover all sources, expiry, decimal totals, excluded statuses, dated installments, end-of-day inclusion, undated receipts, renewals, inconsistent details, empty reports, HTTP endpoint reconciliation and filter validation. Node tests cover signed display, numeric sorting, empty footer reset, combined filters, and missing-date notices. No production records or schema were changed.
