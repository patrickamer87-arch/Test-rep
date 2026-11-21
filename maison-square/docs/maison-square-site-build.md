# Maison Square: site build on staging

What has been built on https://staging.maisonsquare.com, how to change it, and what comes next. Status as of 19 November 2026, 09:40 Beirut time. Requirements are in `maison-square-website-brief.md`, hosting facts (DNS, email, Cloudflare, holding page, login address) in `maison-square-hosting-setup.md`, brand rules in `maison-square-brand-summary.md`.

## Status

- **Theme:** "Maison Square" 0.1.0 is installed and active on staging since 16 November 2026, plus the edits listed under "Theme changes after install". The source zip is on the owner's PC at `Desktop\Maison Square\maison-square-theme.zip` and matches what is on the server (last synced 18 November 2026, 18:25).
- **Shop data, settings, contact details, policy pages, pictures and customer sign-up:** done (see below).
- **Delivery price by weight in Lebanon:** automatic since 18 November 2026: $5 under 7 kg, $10 from 7 kg (see "Delivery and payment set on staging").
- **Home banners:** three full-width banners with home-decor photos since 18 November 2026 (see "Home banners").
- **Login page and admin:** the WordPress login is at `/square` and shows the Maison Square logo; the admin bar shows the Maison Square symbol instead of the WordPress logo (see "Login page and admin").
- **Whish, Aramex and DHL logos:** in the footer since 17 November 2026, from the files the owner sent. Every payment and delivery name is a small card of one size.
- **Google and Facebook login:** the plugin is installed; it waits for the owner's Google and Facebook app keys (see "Customer accounts").
- **No blog.** The sample post and sample page are in the bin. The theme redirects any blog address to the home page, hides Posts in the admin and makes search return products only.
- **Checked on the live staging site:** home, shop, category, product (with size choice), cart, checkout, account, info and policy pages, at 390, 768, 1024 and 1366 px wide; the header and footer also at 320, 344, 360, 375 and 412 px; the home banners at 375 and 1280 px. No sideways scrolling, no script errors, no missing files. Add to cart, the cart number and the Lebanon checkout options work.
- **Visibility:** open. WooCommerce's "Coming soon" mode was switched off on 16 November 2026 at the owner's request ("Open"). Every page of staging is visible to anyone with the address. Staging is still hidden from search engines. To hide the shop again: WooCommerce → Settings → Site visibility → Coming soon. The live address www.maisonsquare.com shows a holding page ("our new shop is on its way.") since 18 November 2026, outside WordPress (see the hosting log).

## The theme

A classic WordPress theme written for this store (no page builder, no parent theme), folder `maison-square`.

- **Look:** cream page, ink text and buttons, Maison yellow for frames and highlights only, Inter in three weights served from the theme itself (no Google Fonts request), square corners everywhere except the small payment and delivery cards. Headlines are lowercase and end with a full stop.
- **Brand devices used:** the yellow frame (hover on category tiles and product photos, and the home banner when it has no photo) and the yellow line (between logo and details in the header, footer, menu and info page, down the left side of the banner panel and of the login form). Icons come from the brand kit sprite.
- **Home page, top to bottom:** top line, header (logo, search, account, cart, category menu), banners, "shop by category.", "new arrivals.", "what customers say.", footer. The strip with three service points was removed on the owner's request; its content now lives on the Shipping policy page. It shows again if the service titles are filled in under Customize.
- **Footer:** contact details, shop links, help links, "we accept." and "we ship with.". Made smaller on 18 November at the owner's request ("too much space", "decrease font and decrease height"): 13 px text, 12 px bottom line ("© 2026 Maison Square"), tighter spacing.
- **Reviews:** only real, approved product reviews with 4 or 5 stars are shown. The section stays hidden until there is one.
- **Products without a photo** show the brand icon of their category on a sand square.
- **Screens:** phone and tablet use a slide-in menu, 1024 px and wider shows the category menu row. The header fits phones down to 320 px wide (the logo is a little smaller under 360 px).
- **Info page:** the page with the address `/info` (titled "Contact us") uses its own layout: symbol, yellow line, contact details. The brand QR codes point to it.
- **WhatsApp button:** a small yellow square (44 px) in the bottom corner of every page opens a WhatsApp chat with the store's number. It disappears if the WhatsApp number is emptied.

### Home banners

On 18 November 2026 the owner asked for a banner with nice home-decor pictures at the top of the home page, then preferred a full banner picture ("maybe it is better to put a full banner picture no?"), then asked for less dead space on the phone and a smaller banner overall.

Three banners are published (admin menu "Banners"):

| Order | Headline | Text | Button and where it goes | Photo |
|---|---|---|---|---|
| 1 | good things for your home, in every season. | Things for every room and every season, delivered to your door or ready to pick up from our store. | Shop new arrivals → the shop, newest first | Light living room with a cream sofa, a wall fireplace and stacked firewood (Media item 72) |
| 2 | candles for the long evenings. | Pillar candles, scented candles and brass holders. | Shop candles → Candles category | Lit candles in a brass candelabra on a dinner table (Media item 73) |
| 3 | vases and planters for every corner. | Stoneware, glass and terracotta, in sizes for a shelf or for the floor. | Shop vases and planters → Vases and planters category | Round white vase and a marble block against a beige wall (Media item 74) |

How the banners look and behave:

- **Computer (900 px and wider):** the photo runs across the whole width of the page, 360 to 520 px high. The headline, text, button and banner squares sit on a cream panel at the bottom left, with the yellow line down its left side.
- **Phone and tablet:** the photo runs edge to edge on top (16:9), then the banner squares, then the headline, text and button. The space below fits the banner shown, so short banners leave no gap.
- The banner changes every six seconds with a soft fade. It waits while the pointer or the keyboard is on it.
- The small squares choose a banner; on a phone a sideways swipe on the photo does the same. Once a visitor has chosen, the banner stays where they put it.
- It does not move at all for visitors whose device is set to reduce motion.
- A banner only shows once its photo has loaded, so the page never jumps.

Things to know:

- **Photos:** free stock photos from Pexels (free to use, no credit required), 2400 × 1600 (landscape). Their titles in the Media Library start with "Wide banner:" and end with "(stock photo)". They are stand-ins until Maison Square has its own photos.
- **Best photo shape:** landscape, at least 2400 px wide, with the main subject on the right half, because the panel covers the bottom left on a computer. The photo is cropped to fill the space.
- **The full-width look is automatic:** it is used whenever the first banner has a photo. With no photo on the first banner, the banner goes back to the headline beside the yellow frame.
- **Left over:** the three square photos of the morning version (Media items 66, 67, 68) are still in the Media Library, not used anywhere.
- To change a banner: Banners → open it → change the headline (the title), the text, the button, the link, the photo ("Banner photo") or the Order number. Up to five published banners are shown.
- With no banner published, the home page goes back to the default headline beside the symbol (text under Appearance → Customize → Maison Square → Home page).
- The button links are full staging addresses. When the shop moves to www.maisonsquare.com they have to be changed with the rest of the staging addresses.

### Cards for "we accept" and "we ship with"

The owner asked for pictures instead of words (16 November), for neat, equal sizes (17 November), then for smaller ones on computer and phone (18 November: "icons … are big, make them smaller", "also on the mobile version").

- **Every name is a card of one size:** 48 wide and 30 high, with slightly rounded corners (3 px).
- **Fixed grid:** four cards in a row where the column is at least 210 px wide, otherwise two in a row.
- **Visa, Mastercard, American Express, Google Pay, Apple Pay:** the card pictures that come with WooCommerce fill the card.
- **Cash on delivery:** WooCommerce's square picture, centred on a light grey card.
- **Whish, Aramex and DHL:** the logos the owner sent on 16 November 2026, in the Media Library as `logo-whish` (item 64), `logo-aramex` (65) and `logo-dhl` (63), each 192 × 120 px (four times the card size, sharp on phone screens). Each logo sits on a card-shaped field of its own background colour; the logo itself was not redrawn or recoloured.
- **Any other name** shows as a white card with the name in small bold type, until its logo is added.
- **To add or replace a logo:** upload a picture to the Media Library named `logo-` plus the name, for example `logo-whish.png`. A picture of 192 × 120 fills the card exactly; any other shape is fitted inside a white card. Rename or delete the old picture first.
- **Left over:** the first versions of the three logos (items 60, 61, 62) are in the Media Library, renamed "… (first size, not used)", and can be deleted. Claude asks the owner before deleting anything; not answered yet.
- Claude does not draw or copy other companies' logos. The owner supplies the files.
- Each logo should stay only while that service is really offered. Card payments, Whish, Aramex and DHL are not connected yet. Each company also has its own rules for how partners show its logo.

### Login page and admin

Asked by the owner on 18 November 2026 ("put maison square logo instead of wp logo and inside also").

- **Login address:** `https://staging.maisonsquare.com/square` (plugin WPS Hide Login). `/wp-login.php` and `/wp-admin` show "page not found" to anyone not logged in. Details in the hosting log.
- **Login page:** cream background, the Maison Square logo (linking to the shop) instead of the WordPress logo, the form with the yellow line on its left, square corners, ink button that turns yellow on hover. The language switcher is hidden.
- **Inside the admin:** the Maison Square symbol replaces the WordPress logo at the top left of the admin bar (it links to the dashboard), and the bottom line reads "Maison Square · staging.maisonsquare.com".
- **Dashboard (18 November 2026, owner's yes):** the WordPress welcome panel, News, Quick Draft, Activity, At a Glance, Site Health, WooCommerce setup and recent-reviews boxes are removed. A "Maison Square" box (cream, yellow left line) shows three counts (orders to handle, products in the shop, out of stock; each links to its list) and ten ink buttons: Orders, Add a product, Products, Categories, Home banners, Customers, Delivery, Payments, Contact details, Pictures, plus "View the shop". WooCommerce Status stays (monthly sales, low stock).

### Where the owner changes things

| What | Where in the WordPress admin |
|---|---|
| Home banners (headline, text, button, wide photo, order; up to 5) | Banners |
| Top line, default headline, service points, number of new arrivals | Appearance → Customize → Maison Square → Home page |
| Email, phone, WhatsApp, address, opening hours, social links | Appearance → Customize → Maison Square → Contact details |
| "We accept" and "we ship with" lists, footer line | Appearance → Customize → Maison Square → Footer |
| Logos for those lists | Media → Library (pictures named `logo-` plus the name) |
| Category menu | Appearance → Menus, location "Main menu". With no menu set, the theme lists the top-level product categories, then New arrivals and Shop all |
| Footer "help." links | Appearance → Menus, menu "Footer help" |
| Category tile photo and order | Products → Categories (thumbnail, drag to sort) |
| Product weight (sets the delivery price) | Products → open a product → Shipping → Weight (kg); for items with sizes, on each size |
| Policy pages | Pages |
| Login address | Settings → General → WPS Hide Login |

### Files

- `functions.php` loads `inc/setup.php` (assets, fonts, favicons, login page and admin bar branding), `inc/template-tags.php` (helpers, payment and delivery cards, WhatsApp button), `inc/customizer.php`, `inc/banners.php`, `inc/no-blog.php`, `inc/woocommerce.php` (shop layout, sign-in button slot, delivery price by weight).
- Templates: `header.php`, `footer.php`, `front-page.php`, `page.php`, `page-info.php`, `404.php`, `index.php`.
- Styles in `assets/css/main.css`, script in `assets/js/main.js` (menu drawer and home banners), fonts in `assets/fonts`, brand files in `assets/img` and `assets/favicon`.

### Updating the theme

- Text changes to a theme file are made in Appearance → Theme File Editor. Claude can do this without the owner, checks each file against its own copy before and after saving, and keeps the zip on the Desktop in step. If a save reports an error, saving a second time has worked.
- A whole new version is a new zip uploaded under Appearance → Themes → Add Theme → Upload Theme. Claude cannot pick files in Chrome, so the owner has to choose the file each time.
- Pictures (footer logos, banner photos) Claude can add to the Media Library itself from a logged-in admin tab, without the file picker. Plugins Claude can install the same way.
- Banners are made and changed in the admin screens "Add a banner" / "Edit banner" (they are not reachable through the WordPress REST interface).
- Looking at pages: Chrome cannot take pictures of a page while Claude's Chrome window is minimised (changes still work). Claude then uses the browser pane of the Claude desktop app, which the owner allows one page load at a time; that pane is not logged in to WordPress.

### Theme changes after install (made in the Theme File Editor)

16 November 2026:

- Old price shown in graphite; no outline box around the "added to cart" message.
- Pictures for payment and delivery names, and icons beside names without a picture.
- WhatsApp button on every page, then made smaller.
- A slot under the login and sign-up forms for the Google and Facebook buttons.

17 November 2026:

- An uploaded logo fills its footer tile; then all payment and delivery names became cards of one size on a fixed grid (`assets/css/main.css`, `inc/template-tags.php`, `footer.php`, hint text in `inc/customizer.php`). `ms_marks()` takes only the list of names.
- Found while testing at 320 px: the header no longer sticks out sideways on narrow phones, and a long email address may break onto a second line.

18 November 2026:

- Morning (`assets/css/main.css`, `assets/js/main.js`): home banners rotate every six seconds, fade, wait for their photo, pause under the pointer or keyboard, stop once the visitor chooses, and can be swiped on a phone.
- Late morning (`front-page.php`, `assets/css/main.css`): full-width banner (class `ms-hero--photo` when the first banner has a photo, photo printed at full size with `sizes="100vw"`, words on a cream panel from 900 px).
- Midday and afternoon (`assets/css/main.css`): less dead space on the phone (photo 16:9, banner squares under the photo, no gap under short banners), smaller banner and headline overall, smaller footer, payment and delivery cards down from 64 × 40 to 48 × 30 (`inc/customizer.php` hint updated).
- Afternoon (`inc/setup.php`): login page and admin bar branding.
- Afternoon (`inc/woocommerce.php`): delivery price by weight (`ms_delivery_by_weight`).
- Evening (`inc/setup.php`): Maison Square dashboard start screen (`ms_dashboard_setup`, `ms_dashboard_start`, `ms_orders_url`, `ms_dashboard_style`).

### Small things to tidy in the next theme version

- Highlight the current category in the menu when no custom menu is set.
- In the cart, an item without a photo shows the plain brand symbol instead of its category icon.

## Customer accounts

- **Sign-up is on** since 16 November 2026: the My account page shows "Login" and "Register" side by side, and customers can also create an account at checkout. Customers choose their own password. Guest checkout stays allowed.
- New users get the "Customer" role.
- **Google and Facebook login:** the free plugin Nextend Social Login (3.1.27) is installed and active, with sign-up through it allowed. It cannot work until each provider has its app keys:
  - Since the login moved to `/square` on 18 November, the redirect addresses are based on `/square`. Copy the exact address from Settings → Nextend Social Login → Google (or Facebook) → Getting Started, and use it when creating the app.
  - Google: create an app in the Google Cloud console, then paste the Client ID and Client Secret under Settings → Nextend Social Login → Google.
  - Facebook: create an app at Meta for Developers, then paste the App ID and App Secret under Settings → Nextend Social Login → Facebook.
  - The owner has to create the apps and paste the keys. Claude does not type secret keys.
  - When the shop moves to www.maisonsquare.com, the same addresses with the live domain have to be added to both apps.
- The theme prints the plugin's buttons under the login and sign-up forms once a provider is switched on. To be checked after the keys are in.

## Contact details on the site

Set on 16 November 2026 from the owner's message. They show in the footer, the phone menu, the info page and the holding page on www.

- Email: hello@maisonsquare.com (mailbox works both ways, tested 18 November)
- Phone and WhatsApp: +961 79 000 000
- Address: Jounieh Highway, Keserwan, Lebanon
- Opening hours: Monday to Friday 9 am to 7 pm. Saturday 9 am to 1 pm.

## Policy pages (created 16 November 2026)

All three are published and linked in the footer under "help.".

- **Shipping policy** (`/shipping-policy/`): delivery in Lebanon ($5 under 7 kg, $10 above), free pick-up with the store address and hours, delivery outside Lebanon with Aramex and DHL priced at checkout, payment (card, Whish or cash on delivery in Lebanon; card outside Lebanon). No delivery times yet: the owner has not given any.
- **Refund and return policy** (`/refund-and-return-policy/`): adapted from the owner's Dynamic Solutions policy, with home-product wording. The owner should read it once.
- **Privacy policy** (`/privacy-policy/`): the owner's text as given.

Open points on the refund policy, raised with the owner on 16 and 17 November, not answered yet:

- It says nothing about items that arrive damaged or wrong.
- Lebanon's Consumer Protection Law 659/2005 gives consumers a right to reverse a purchase within 10 days in some cases (Article 55, per a UN ESCWA summary), so "all sales are final" may not hold for every online order. Worth a check with a lawyer before launch.

## Shop settings set on staging

- Store country Lebanon, currency US dollar, weight in kg, dimensions in cm, time zone Asia/Beirut.
- Stock management on. Low-stock email at 3, out-of-stock email at 0. Product pages show the quantity only when stock is low.
- Permalinks set to "Post name", so addresses read `/shop/`, `/product/pillar-candle/`, `/product-category/candles/`, `/info/`.
- Home is a static page called "Home".
- Site tagline: "Good things for your home, in every season."

## Delivery and payment set on staging

- Delivery zone **Lebanon** with two options: "Pick-up from our store" (free) and "Delivery".
- **Delivery price by weight** (since 18 November 2026, in the theme, no extra plugin): the weights of everything in the cart are added up (weight × quantity). Under 7 kg the Delivery option costs $5; from 7 kg it costs $10. Pick-up stays free.
  - Tested at checkout: one stoneware vase (1.3 kg) → $5; six vases (7.8 kg) → $10; vase plus floor planter (9.8 kg) → $10.
  - **Every product needs a weight.** An item with no weight counts as 0 kg.
  - The rule is tied to the Delivery option of the Lebanon zone. If that option is deleted and made again, the theme has to be pointed at the new one. The $5 typed in the option's own settings is replaced by the rule.
- **Cash on delivery** is on, and only offered with those two Lebanon options. An address outside Lebanon gets no delivery option and no payment method yet.
- Not yet done: Aramex and DHL, card payments, Whish, payment methods by country. The Shipping policy page and the footer cards already describe these.
- With the shop pages open, anyone who finds staging could place a cash-on-delivery test order. Orders on staging are tests.

## Sample catalogue (replace or delete)

Four categories with three items each. Names, prices, sizes and weights are made up for testing. Every item carries the tag `sample`, so Products → filter by tag `sample` finds them all.

| Category | Code | Item | Price | Weight |
|---|---|---|---|---|
| Candles | CNC-101 | Pillar candle, sizes CNC-101-1 / -3 / -5 | $9 / $13 / $18 | 0.35 / 0.55 / 0.8 kg |
| Candles | CNC-102 | Scented candle in amber glass | $16 | 0.45 kg |
| Candles | CNC-103 | Brass candle holder | $22 | 0.6 kg |
| Lamps | LMP-201 | Ceramic table lamp | $64 | 2.4 kg |
| Lamps | LMP-202 | Rattan pendant shade | $58, on sale $46 | 1.1 kg |
| Lamps | LMP-203 | Brass bedside lamp | $49 | 1.6 kg |
| Wall art | WAL-301 | Framed line print, 30 x 40 cm | $34 | 1.2 kg |
| Wall art | WAL-302 | Round rattan mirror | $72 | 2.8 kg |
| Wall art | WAL-303 | Woven wall hanging (2 in stock, to show the low-stock note) | $41 | 0.7 kg |
| Vases and planters | VAS-401 | Stoneware vase | $29 | 1.3 kg |
| Vases and planters | VAS-402 | Glass bud vases, set of 3 | $24 | 0.9 kg |
| Vases and planters | VAS-403 | Large terracotta floor planter (over 7 kg, to test the $10 delivery) | $85 | 8.5 kg |

CNC-101 is the model for items with sizes: one product, and each size has its own code, price, weight, dimensions and stock.

### Stock photos on the site

- All 12 sample items and the 4 categories have a photo since 16 November 2026, and the three home banners since 18 November 2026. They are free stock photos from Pexels (free to use, no credit required), with "(stock photo)" in their titles.
- They are stand-ins. The product and category photos must be replaced with real product photos before the shop opens. The banner photos can stay longer, but the owner's own photos are better.
- Pexels photo numbers: 11137699, 7004676, 5858233 (candles); 14380620, 235187, 35392792 (lamps); 4284798, 20781348, 13211211 (wall art); 7119222, 8903617, 17962521 (vases and planters); categories 8789617, 40317537, 9479072, 40167789; wide banners 7404938, 3937246, 4203098; square banners of the morning version (not used now) 13613240, 6794901, 14784254.

## Next

1. Google and Facebook keys from the owner, then check the sign-in buttons.
2. Card gateway, Whish, Aramex and DHL once the accounts and API keys exist; then payment methods by country.
3. SEO and Google Analytics, email to customers, invoices (A4 and 80 mm) and customer statements.

Done on 18 November 2026: Cloudflare proxy and Full (strict) SSL, email DNS with DMARC, holding page on www, login at `/square` with brand, delivery price by weight, admin email hello@, dashboard start screen.

## Needed from the owner

- Google and Facebook app keys, pasted into the plugin by the owner.
- A read of the refund policy, and a decision on damaged or wrong items.
- Delivery times for the Shipping policy.
- The real category list, products (with weights) and photos. Square photos work best for products.
- Own banner photos (landscape, subject on the right), when there are some.
- Social links, if they should show in the footer.
- A yes or no on deleting unused pictures from the Media Library: the three first-size logos (60, 61, 62) and the three square banner photos (66, 67, 68).
