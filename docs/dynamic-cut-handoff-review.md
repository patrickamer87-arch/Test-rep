# Dynamic cut-to-length checkout: handoff review and access needed

Reviewed 2026-11-21 from `Dynamic_Aluminium_Calculator_Handoff.md` and the matching ZIP.
Nothing in this review touched dynamic.me, its database, mail or payments. The archive was
only extracted and read in an isolated scratch directory.

## 1. What was verified from the archive

| Check | Result |
| --- | --- |
| SHA256SUMS.txt against all 75 files | all match |
| `php -l` on every PHP file (PHP 8.3.6 CLI, mysqli + gd present) | no syntax errors |
| `quote_quantity_qa.php` (grouped solver vs exact solver) | 600 comparisons pass, 120-piece and 17-piece cases pass, 0.16 s |
| Acceptance example at $5/m, 3 x 61 cm, kerf 0, leave offcut | 1.83 m billed, $9.15 + $1.50 = $10.65, 3 cuts, 0 cm returned |
| Acceptance example, keep offcut | 2.00 m billed, $10.00 + $3.00 = $13.00, 4 cuts (3 piece + 1 release), 17 cm returned |
| 100.2 cm, leave offcut | 1.002 m billed, $5.01, no paid cuts |
| draft-boundary-http-qa.log | 69 PASS lines, none failing |
| native-transport-rehearsal.log, quote-quantity-qa.log, migration QA JSON | consistent with the handoff text |
| Secrets scan of staging config and prepend | no passwords or hosts; everything comes from env vars and is loopback-only |

The pricing engine (StageQuote.php) therefore matches the handoff's acceptance numbers exactly,
and the included evidence is internally consistent.

## 2. What is ready and what is not

### Ready for staging integration (reviewed, tested in isolation)
- Quote engine `StageQuote.php`: exact packing, cent accounting, 120 pieces / 30 rows, manual-quote fallback.
- Server authority `ProductCutAuthority.php`: enablement flag, catalogue-price quote, 5-minute bound quote,
  transactional add-to-basket, barcode from the explicitly selected catalogue field (SKU, CNC-ACC-090).
- `DraftBoundary.php`: separate order IDs for ordinary vs cut drafts; refuses to reuse a draft that
  may have a card attempt.
- Save path: `Guard.php`, `SaveCoordinator.php`, `PrefixOrderPort.php` (same-connection transaction,
  InnoDB verification, digest-proved snapshot rows, retire-on-uncertain-commit).
- `NativeCutTransport.php` + `build_db_transport.py`: adds explicit cut-transaction methods to the
  reviewed OpenCart DB wrapper; refuses to build if the live `db.php` / `db/mysqli.php` hashes differ;
  rejects DDL inside a cut transaction (Improved Options can issue ALTER TABLE on first use).
- `schema.py`: three new InnoDB sidecar tables, pilot seeded disabled, fails on existing objects.
- Quote/add endpoint `dynamic_cut.php` + `dynamic-cut.js`: GET exposes only a session ticket; POST validated.
- Rendering from the saved order: `WorkshopProjection`, `AdminProjection`, `BarcodeReference`, `ProductBarcodePng`.

### Not ready (must be done on a licensed staging copy, never directly on production)
- Production bootstrap: registering `dynamic_cut_product_authority`, `dynamic_cut_cart_projection`,
  `dynamic_cut_save` and `dynamic_cut_workshop` in the live registry, route binding for the endpoint,
  and the product-page template hook. None of this is installed.
- The `*.stage.php` overlays (Journal checkout cart, Journal order model, native cart, mail/order,
  admin order) were patched against a synthetic store. They must be re-derived against the
  *effective* live files (OCMOD-modified, Journal3 version, Improved Options io3 3.1.0 runtime).
- Payment admission for cut baskets (COD only, labelled "Pay at pickup") and the pickup-only
  shipping filter exist only as harness patches in `pilot_setup.py`, not as release files.
- `DraftBoundary` only accepts `cybersource_order` / `cybersource_order_status` as attempt tables.
  The real CyberSource extension table names on the live DB have not been confirmed.
- Legacy MyISAM conversion of the six order/voucher tables: rehearsed on synthetic data only.
  `PrefixOrderPort::verifiedFor` fails closed until all six are InnoDB, so cut checkout cannot
  open before that planned operation.
- Workshop email: recipient, duplicate/retry behaviour and the real SMTP path are unverified.
- Live preflight items in `LIVE-PREFLIGHT.md` (MariaDB version, disk, cPanel backup and upcp
  windows, in-flight payment quiet period) are the operator's checks, not something the bundle does.
- Stock setting for product 2594 on the live store was never read.

### Do not deploy
`PilotHttp.php`, `PilotFixtureController.php`, `SinkMail.php`, `IoHttpStub.php`, `HttpPort.php`,
`HttpGuestTransfer.php`, `fixture_other` payment, `fixture_attempt` table, the harness router and
the `search-staging-20261108` bootstrap bundle.

## 3. Access needed to continue (staging only)

No production credentials are requested at this stage. Items 1 to 3 unblock the integration work.

1. **Licensed staging copy of the store code**, in one of these forms:
   - a private GitHub repository I can attach to this session (preferred for a cloud session), or
   - a folder on your computer shared through Remote Control (Claude desktop app, Settings,
     Claude Code, Remote Control). No folder is listed right now.
   It must contain the effective tree, not a clean OpenCart: the Journal3 theme and
   `catalog/controller|model/journal3/*`, the Improved Options extension (`system/library/liveopencart/*`),
   the CyberSource and Google Pay payment extensions, `system/storage/modification/` (the active
   OCMOD output), `system/storage/vendor/`, and the admin tree. Keep `config.php` out of it.
2. **Sanitised schema dump of the live database**: `mysqldump --no-data` plus the rows for
   `setting`, `extension`, `product` 2594 and its `product_to_store`, `order_status`, and the
   CyberSource extension tables (structure only). This gives the real table prefix, engines of the
   six legacy tables, the CyberSource table names and the actual COD / pickup extension status.
   Replace any customer or order data with synthetic rows before sending.
3. **A staging database I may write to**: either MariaDB on your machine via Remote Control, or
   permission to install MariaDB in this cloud container and load the sanitised dump there.
   PHP 8.3 with mysqli is already present here; MariaDB is not.
4. **Confirmations from you (text answers are enough)**: Journal3 exact version, Improved Options
   version, OpenCart build (handoff says 3.0.4.0), MariaDB release (preflight said 11.8.10),
   whether product 2594 has stock tracking on, and the intended workshop mailbox.
5. **Written authorisation** for each later step separately: (a) staging edits, (b) the MyISAM to
   InnoDB conversion window on production, (c) installing the bundle on production with the pilot
   disabled, (d) enabling the pilot for product 2594.

Not needed and not wanted: card provider keys, SMTP passwords, customer records, production DB
credentials, cPanel or SSH logins. If a production read is ever needed it should be done by you
from phpMyAdmin and pasted as text.

## 4. Order of work once access exists

1. Diff the staging tree against `dynamic-current-source/` to find drift since the snapshots.
2. Run `build_db_transport.py` against the staging `system/library`; it must build without a hash error.
3. Generate the sidecar SQL with `schema.py --prefix <live prefix>` and apply it to staging only.
4. Port the harness patches in `pilot_setup.py` into proper OCMOD/override files for the live
   Journal3 and Improved Options versions; register the components in a startup controller.
5. Re-run the 69-check HTTP suite against staging with synthetic data and a mail sink.
6. Add the live-only checks: real CyberSource table names in `DraftBoundary`, pickup-only shipping,
   COD-only payment for cut baskets, workshop mail recipient and dedupe, tracking job untouched.
7. Rehearse disable/rollback on staging, then hand over a production install plan with the
   feature disabled.

## 5. Notes
- The SupaSkills "OpenCart" skill was found but the connector returned a rate limit when loading
  it. Not blocking; it can be retried later.
- This repository was empty apart from a README, so it can serve as the integration workspace.
  The handoff archive itself was not committed: it contains Journal3 and Improved Options code
  that is licensed to you and should not be pushed anywhere public.
