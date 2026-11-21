# Maison Square: handoff for Claude Code

Read this first. It is the state of the Maison Square online store as of **19 November 2026, 09:40 Beirut time**, written at the end of a long claude.ai chat so the work can continue in Claude Code.

What's in this folder:

| Path | What |
|---|---|
| `HANDOFF.md` | This file: context, rules, how things are deployed, what is open |
| `docs/maison-square-site-build.md` | Full record of what is built on staging and where the owner changes it |
| `docs/maison-square-hosting-setup.md` | Domain, server, DNS, email, Cloudflare, holding page |
| `theme/maison-square/` | Source of the custom WordPress theme, identical to what runs on staging |
| `maison-square-theme.zip` | The same theme as an installable zip |
| `holding-page/` | The "shop is on its way" page now live on www.maisonsquare.com |

The owner's claude.ai Project "Maison Square" also holds `maison-square-website-brief.md` (full requirements and the payments/shipping research) and `maison-square-brand-summary.md` (brand rules). Ask the owner to paste them if they are needed; the essentials are below.

## 1. The project in one paragraph

Maison Square is a Lebanese online home-goods store (things for the home, in every season; not gadgets). The shop is WooCommerce on WordPress with a **custom classic theme written from scratch** (`maison-square`), no page builder, no parent theme. It is being built on **https://staging.maisonsquare.com**. The live domain **www.maisonsquare.com** shows a static holding page until launch.

## 2. Rules from the owner (must follow)

1. **Always reply in English**, even when the owner writes in Arabic. The owner often reads on a phone: short replies, answer first.
2. **Change nothing at server level.** Work only inside the `maisonsq` cPanel account / this site. The server also hosts other businesses (e.g. dynamic.me).
3. **Do not touch the owner's other browser window / sessions** (another assistant once worked there as root with a WHM Terminal). Work only in your own tabs.
4. **Never type passwords or secret API keys.** The owner signs in and pastes keys himself.
5. **Ask before anything irreversible** (deleting media, products, DNS records, emails). Unused media items 60, 61, 62, 66, 67, 68 are waiting for a yes; no answer yet.
6. Do not draw or copy other companies' logos; the owner supplies logo files.
7. The owner's personal email is private; the business emails are hello@ and info@maisonsquare.com.

## 3. Environment

- **Server:** server.dynamic-lb.com (cPanel/WHM 138, AlmaLinux 8.10), shared IP 203.161.38.44, PHP 8.3. cPanel user `maisonsq`, home `/home/maisonsq`.
- **Staging docroot:** `/home/maisonsq/public_html/staging.maisonsquare.com`
- **Live docroot:** `/home/maisonsq/public_html` (only `index.html` + `soon/` for the holding page right now).
- **Software:** WordPress 7.1.4, WooCommerce 11.3.0. Plugins: Nextend Social Login 3.1.27 (waiting for Google/Facebook keys), WPS Hide Login 1.9.20.
- **WordPress login:** `https://staging.maisonsquare.com/square` (`/wp-admin` and `/wp-login.php` return 404 when logged out). Admin user `admin_owwsa4yx`, email hello@maisonsquare.com. Password known only to the owner (WP Toolkit can reset it).
- **Cloudflare (Free):** root, www and staging proxied; `mail` DNS only; SSL Full (strict). SPF, DKIM, DMARC (`p=none`) in place. Scripted writes to the Cloudflare API from the dashboard session are refused, so DNS changes go through the dashboard UI or through an API token the owner creates.
- **No SSH/SFTP has been used so far.** Everything was done through the browser: WordPress admin, the WordPress REST API, cPanel's UAPI (`Fileman/save_file_content`, API2 `fileop` copy) and the Cloudflare dashboard. If Claude Code is given SSH, SFTP or a cPanel API token by the owner, deploy that way instead (see 5).

## 4. Brand essentials

- Colours: **Maison Yellow `#F7C20A`** (frames, lines, highlights, never text on light), **Ink `#161616`** (text, buttons), **Cream `#FDF8EF`** (page), Graphite `#5A5852` (secondary text), Sand `#EFE7D6` (panels), White.
- Type: **Inter** (400/600/700), self-hosted in the theme. Headlines **lowercase and ending with a full stop** ("new arrivals.").
- Devices: the **yellow outlined square frame**; the **thin yellow vertical line** between logo and details. Square corners everywhere (except 3 px on payment/delivery cards).
- Logo files are in `theme/maison-square/assets/img` (`logo-horizontal-color.svg`, `symbol-color.svg`, reversed versions).
- QR codes in print point to **www.maisonsquare.com/info**, which must exist at launch (the page `/info` exists on staging with its own template `page-info.php`).

## 5. The theme

### File map

- `functions.php` loads the `inc/` files.
- `inc/setup.php`: theme supports, menus, assets, fonts, favicons, **login page branding**, **admin bar logo**, **admin footer text**, **dashboard start screen**.
- `inc/template-tags.php`: helpers, `ms_marks()` (payment/delivery cards in the footer, logos found in Media by the name `logo-<name>`), WhatsApp button.
- `inc/customizer.php`: Customizer panel `ms_panel` with sections `ms_home`, `ms_contact`, `ms_footer`; defaults in `ms_defaults()`.
- `inc/banners.php`: custom post type `ms_banner` (home banners: title = headline, meta `ms_banner_text`, `ms_banner_button`, `ms_banner_url`, featured image, `menu_order`). Not exposed to REST.
- `inc/no-blog.php`: no blog (redirects, hides Posts, product-only search).
- `inc/woocommerce.php`: shop layout tweaks, social-login button slot, **delivery price by weight** (`ms_delivery_by_weight`: Lebanon "Delivery" flat-rate instance id **2** costs $5 under 7 kg and $10 from 7 kg of total cart weight; pick-up stays free).
- Templates: `header.php`, `footer.php`, `front-page.php` (banners, categories, new arrivals, reviews), `page.php`, `page-info.php`, `404.php`, `index.php`.
- `assets/css/main.css` (all styles), `assets/js/main.js` (menu drawer, banner rotation with fade, swipe, pause, reduced motion).

### Deployed file fingerprints

Every file below matches the server. Hash = djb2 variant over JS string chars: `h=5381; for each char: h = ((h*33) ^ code) >>> 0`.

| File | Length | Hash |
|---|---|---|
| assets/css/main.css | 47572 | 2655352736 |
| assets/js/main.js | 7052 | 97870170 |
| front-page.php | 10263 | 3312733050 |
| footer.php | 3503 | 3067407190 |
| header.php | 5601 | 1872098182 |
| inc/setup.php | 12306 | 3933963854 |
| inc/template-tags.php | 16985 | 3631173965 |
| inc/woocommerce.php | 11471 | 3873352694 |
| inc/customizer.php | 4828 | 663748922 |
| inc/banners.php | 5794 | 3873073570 |
| inc/no-blog.php | 2419 | 761747607 |
| functions.php | 558 | 342114730 |

### How changes were deployed (browser only)

1. Open `/wp-admin/theme-editor.php?file=<path>&theme=maison-square` while logged in.
2. In page JS: `const cm=document.querySelector('.CodeMirror').CodeMirror; let s=cm.getValue();` Check length and hash against the table, apply exact string replacements (each anchor must occur once), check the new length and hash, then `cm.setValue(s); cm.save(); document.querySelector('#submit').click();`
3. Wait 5–10 s and confirm "File edited successfully.". Then update the local copy and rebuild the zip.

Other no-upload paths that worked:

- **Media:** `wp.apiFetch({path:'/wp/v2/media', method:'POST', body:FormData})`.
- **Plugins:** `/wp/v2/plugins` POST `{slug, status:'active'}`.
- **Store data:** the WooCommerce REST API from a logged-in admin tab.
- **Checkout tests:** the Store API (`/wp-json/wc/store/v1/cart/...`). Clean the cart afterwards.

With file access, just upload `theme/maison-square/` (or install the zip via Appearance → Themes → Upload, which replaces the installed theme).

## 6. Data on staging

- Four categories (Candles, Lamps, Wall art, Vases and planters) with 12 **sample** products tagged `sample` (made-up names, prices and weights, Pexels stock photos). CNC-101 is the model for sizes: a variable product with per-size SKU (`CNC-101-1/-3/-5`), price, weight and stock.
- Three home banners (posts 69, 70, 71) with wide Pexels photos (media 72, 73, 74).
- Store: Lebanon, USD, kg/cm, Asia/Beirut, stock management on (low stock at 3).
- Delivery zone Lebanon: Pick-up (free) and Delivery (by weight). Cash on delivery for Lebanon only. Nothing set up yet for outside Lebanon.
- Policy pages: Shipping, Refund and return, Privacy. Contact: hello@maisonsquare.com, +961 79 000 000 (placeholder number), Jounieh Highway, Keserwan; Mon–Fri 9–7, Sat 9–1.
- Footer cards: Visa, Mastercard, Amex, Google Pay, Apple Pay, Cash on delivery, Whish, Aramex, DHL (Whish/Aramex/DHL logos are media 64, 65, 63).

## 7. What's next

Waiting on the owner:

1. **Google and Facebook app keys.** The owner pastes them into Settings → Nextend Social Login. The redirect URLs are based on `/square`. Then check the buttons under the login and register forms.
2. **Card gateway** (areeba via bank, Tap, NetCommerce, MontyPay; 2Checkout as backup), **Whish Pay**, **Aramex** and **DHL Express** accounts with API keys. Then: carrier plugins with live rates outside Lebanon, payment methods by country (Lebanon: card, Whish, COD; abroad: card only).
3. Real catalogue: categories, products with **weights** (the delivery price depends on them), photos.
4. Refund policy decision (damaged or wrong items; Lebanese consumer law, Law 659/2005, may give a 10-day right of return). Delivery times for the Shipping page.
5. Yes/no on deleting unused media 60–62 and 66–68.

Can be done without the owner:

- Invoices and receipts (A4 and 80 mm thermal), paid/unpaid status, customer statement of account.
- SEO (structured data, sitemap), GA4 through Tag Manager with ecommerce events (the owner must give access).
- Opt-in email marketing setup.
- Test that site emails (orders, password reset) are delivered.
- Theme to-dos: highlight the current category in the default menu; show the category icon (not the plain symbol) for cart items without a photo.

At launch:

- Move WordPress to www.maisonsquare.com and replace every staging URL (banner button links are absolute).
- Delete `public_html/index.html` and `public_html/soon/`.
- Make sure `/info` works on the live domain.
- Add the live-domain redirect URLs to the Google and Facebook apps.
- Turn search-engine indexing on.

## 8. Decisions already made (don't revisit unless asked)

- WooCommerce engine plus a from-scratch theme and small custom code, rather than a fully custom shop (security, existing Lebanese gateway and carrier plugins).
- No blog.
- Full-width photo banner on the home page. On phones: photo 16:9 on top, banner dots, then the words.
- Footer and payment/delivery cards were made smaller at the owner's request (cards 48×30).
- Login at `/square`, branded. Dashboard replaced by a Maison Square start screen.
- Delivery inside Lebanon: $5 under 7 kg, $10 from 7 kg (exactly 7 kg costs $10).
