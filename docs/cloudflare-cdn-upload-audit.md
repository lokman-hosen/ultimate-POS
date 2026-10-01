# File upload audit — Cloudflare CDN / R2 migration

_Read-only investigation, 2026-10-01. No code, config, `.env` or data was changed._

## 1. Short answer

- **Writes are almost centralised; reads and deletes are not.** About 40 of the ~45 write paths go through two helpers (`Util::uploadFile()` and `Media::uploadFile()`), and both use the **default disk**. But URLs are built in **~25 places** with hard-coded `asset('/uploads/…')` / `url('uploads/…')`, deletes use `unlink(public_path(…))`, and invoice/receipt logos are gated by `file_exists(public_path(…))`.
- **Switching `FILESYSTEM_DISK` to an R2 disk on its own would break the app.** New files would go to R2 while every link still points to `/uploads/…` on the server. Invoice logos would silently disappear, media deletes would leave files behind on R2, and sales import would fail.
- **The DB stores bare filenames only**, and the folder is fixed per feature. So once URL building is centralised, R2 URLs can be rebuilt **without a data migration**.
- **Recommendation:** do **A (Cloudflare CDN in front of the server) now** for public images. That needs almost no code, **but first move backups and private documents out of `public/uploads`** (see §5.1). Plan **C (hybrid R2)** behind a small `UploadStorage` service (§7); it touches ~30 files once, after which switching storage is config only.

## 2. Current storage configuration

### Disks (`config/filesystems.php`)

| Disk | Driver | Root | URL | Notes |
|---|---|---|---|---|
| `local` (**default**) | local | `public_path('uploads')` | _(none)_ | `'default' => env('FILESYSTEM_DISK', 'local')`. `.env` doesn't set `FILESYSTEM_DISK`, so **every `storeAs()` without a disk writes into the public web root**. |
| `public` | local | `storage_path('app/public')` | `APP_URL/storage` | Not used by app code. `public/storage` symlink **does not exist**. |
| `s3` | s3 | — | `AWS_URL` | Configured from `AWS_*` env (`endpoint`, `use_path_style_endpoint` already wired). Not used. |
| `dropbox` | dropbox (custom, `AppServiceProvider:61`) | — | — | For backups (UltimatePOS default); not active. |

### Env keys (names only; values not printed)

- **`.env`:** `APP_URL`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET` (AWS values empty), `BACKUP_DISK="local"`, `FILESYSTEM_DISK` and `FILESYSTEM_DRIVER` present but empty, so the default `local` applies.
- **`.env.example`:** `APP_URL` and the four `AWS_*` keys.
- **Missing everywhere:** `ASSET_URL`, `AWS_URL`, `AWS_ENDPOINT`.

### Packages

| Package | Version |
|---|---|
| Laravel | 12.x (`laravel/framework` v12.68 in lock) |
| Flysystem | 3.35 |
| `league/flysystem-aws-s3-v3` | 3.35 (**already installed**, enough for R2) |
| `mpdf/mpdf` | 8.3 |
| `barryvdh/laravel-dompdf` | ^3.0 (installed but **not used** anywhere in `app/` or `Modules/`) |
| `spatie/laravel-backup` | 10.3 |
| `maatwebsite/excel` | 3.1 |

### What's on disk now (`public/uploads`, local dev copy)

| Folder | Files | Size | Written by |
|---|---|---|---|
| `img/` | 2 | 1.0 MB | product images (`constants.product_img_path = 'img'`) |
| `media/` | 4 | 2.2 MB | `Media` model (profile photos, variation images, brochures, shipping/sell/account docs, notes) |
| `carousel_images/` | 4 | 2.3 MB | customer-display carousel (POS settings) |
| `business_logos/` | 2 | 44 KB | business logo |
| `cms/` | 1 | 348 KB | **nothing writes here any more** (leftover from the Cms module) |
| `invoice_logos/`, `documents/`, `temp/` | — | — | created on first use |
| `index.html` | 1 | 0 | directory-listing guard (root only) |

Total 5.8 MB locally. **Production volume is unknown; please measure it there (§8).**

### Modules

`modules_statuses.json` enables 22 modules (Connector, Essentials, Cms, Repair, Gym…), but **only `Modules/Superadmin` exists on disk**. There is **no Connector/API module**, and `routes/api.php` only has the default `/user` route, so **no API or mobile responses return image URLs today**.

`Modules/` also contains a stray copy of Superadmin at its root (`Modules/Http`, `Modules/Resources`, `Modules/module.json`…). nwidart doesn't load it; it's ignored in this audit but should be deleted.

## 3. Central helpers

### `App\Utils\Util::uploadFile($request, $file_name, $dir_name, $file_type = 'document')` — `app/Utils/Util.php:710`

- Inherited by every `*Util` (`BusinessUtil`, `ProductUtil`, `TransactionUtil`…), so all `$this->xxxUtil->uploadFile()` calls are this one method.
- **Writes** `$file->storeAs($dir_name, $name)` to the **default disk**, i.e. `public/uploads/{dir}/{name}`.
- **Filename** `time().'_'.Str::slug(original name).'.'.clientExtension`.
- **Validation:** for `image`, the mime type must start with `image/`. For `document`, the mime type must be in `constants.document_upload_mimes_types`. Size ≤ `document_size_limit` (5 MB). In the `demo` env it returns `null`.
- **Returns / stores in DB:** the **bare filename** (no folder). The folder is implied by the caller (`img`, `business_logos`, `invoice_logos`, `carousel_images`, `documents`).
- **No delete counterpart:** replaced logos and documents are never deleted.

### `App\Media` — `app/Media.php`

| Member | Behaviour |
|---|---|
| `uploadMedia($business_id, $model, $request, $field, $is_single, $type)` (:81) | Handles single or multiple files and base64 strings, then `attachMediaToModel()`. |
| `uploadFile($file)` (:139) | `storeAs('/media', time().'_'.mt_rand().'_'.originalName)` on the **default disk**. The original client name is **not sanitised**. Returns the bare filename. |
| `uploadBase64Image()` (:152) | **Bypasses Storage:** `fopen(public_path('uploads').'/media/…')`. |
| `getDisplayUrlAttribute` (:40) | `asset('/uploads/media/'.rawurlencode(file_name))`. **Hard-coded.** |
| `getDisplayPathAttribute` (:50) | `public_path('uploads/media/…')`. **Local path.** |
| `thumbnail()` (:60) | `<img src=display_url>`, no resizing. |
| `deleteMedia()` (:172) | `file_exists` + `unlink(public_path('uploads/media/…'))`. **Local only.** |
| `attachMediaToModel()` (:190) | Single mode calls `$model->media()->delete()`, which deletes **rows only** and orphans the files. |

- **DB:** `media.file_name` holds the bare filename.

### Other model accessors (URL builders)

| Accessor | Builds | Notes |
|---|---|---|
| `Product::getImageUrlAttribute` (`app/Product.php:32`) | `asset('/uploads/img/'.rawurlencode(image))` | Falls back to `/img/default.png`. |
| `Product::getImagePathAttribute` (`app/Product.php:48`) | `public_path('uploads/img/…')` | Used for the delete in `ProductController:830`. |
| `Transaction::getDocumentPathAttribute` (`app/Transaction.php:137`) | `asset('/uploads/documents/'.document)` | Returns a URL despite the name. |
| `TransactionPayment::getDocumentPathAttribute` (`app/TransactionPayment.php:61`) | `asset('/uploads/documents/'.document)` | Same. |
| `User::getImageUrlAttribute` (`app/User.php:356`) | `media->display_url` | Falls back to ui-avatars.com, so it's **covered by `Media`**. |

There are **no** `app/Helpers` or `app/Traits` upload helpers. `app/Http/helpers.php` only has `isFileImage()` (it checks the URL extension, which still works with remote URLs).

## 4. Call sites (grouped by feature)

Legend for **Op**:

- `store` — writes a file
- `url` — builds a URL
- `path` — builds a local path
- `del` — deletes a file
- `exists` — `file_exists` / `is_file` check
- `read` — reads the file contents

**Helper** = goes through `Util::uploadFile` / `Media::*` / an accessor. **HC** = path hard-coded.

### 4.1 Product images (`uploads/img`) — public

| File:line | Op | Helper | HC |
|---|---|---|---|
| `app/Http/Controllers/ProductController.php:550` (store), `:826` (update) | store | Y | N |
| `app/Http/Controllers/ProductController.php:830-831` | exists + del (`unlink($product->image_path)`) | N | Y |
| `app/Product.php:35` / `:51` | url / path | (accessor) | Y |
| `app/Http/Controllers/ImportProductsController.php:151-156` | read (`file_get_contents($url)`) + store (`file_put_contents(public_path…)`) | N | Y |
| `resources/views/sale_pos/product_row.blade.php:36` | url `asset('/uploads/img/'…)` | N | Y |
| `resources/views/sale_pos/partials/product_list.blade.php:12` | url `asset('/uploads/img/'…)` | N | Y |
| `resources/views/sale_pos/display.blade.php:410` | url, JS string `${base_path}/uploads/img/…` | N | Y |
| `resources/views/import_products/index.blade.php:237` | help text mentions `public/uploads/img` (users FTP images there) | N | Y |
| `app/Http/Controllers/BusinessController.php:577` (`getEcomSettings`) | url `url('uploads/img/'…)` for e-com slides | N | Y |

### 4.2 Media model: profile photos, variation images, brochures, shipping/sell/account documents, notes (`uploads/media`) — mixed public/private

| File:line | Op | Helper | HC |
|---|---|---|---|
| `app/Media.php:144` | store | Y | N |
| `app/Media.php:156-164` (base64) | store via `fopen` | Y | **Y** |
| `app/Media.php:42` / `:52` | url / path | Y | **Y** |
| `app/Media.php:177-180` | exists + del | Y | **Y** |
| `app/Http/Controllers/UserController.php:87` (profile photo) | store | Y | N |
| `app/Http/Controllers/HomeController.php:762` (`attachMediasToGivenModel`) | store | Y | N |
| `app/Utils/ProductUtil.php:66, 181, 230, 282` (variation images) | store | Y | N |
| `app/Http/Controllers/ProductController.php:613, 934` (brochure), `:876` (variation) | store | Y | N |
| `app/Http/Controllers/AccountController.php:723` (fund-transfer doc) | store | Y | N |
| `app/Http/Controllers/PurchaseOrderController.php:407, 696` (shipping docs) | store | Y | N |
| `app/Http/Controllers/SellPosController.php:498, 610, 1417, 1462` (shipping/sell docs) | store | Y | N |
| `app/Http/Controllers/DocumentAndNoteController.php:425` | store | Y | N |
| `app/Http/Controllers/ProductController.php:2087` | del via `Media::deleteMedia` | Y | (inherits HC) |
| Views using `display_url` / `thumbnail()` (`components/avatar`, `sell/partials/media_table`, `sale_pos/product_row:34`, `product_list:10`, `featured_products:16`, `display.blade.php:408`, `service_staff_availability_modal:20`) | url | Y | N |

### 4.3 Business logo (`uploads/business_logos`) — public

| File:line | Op | Helper | HC |
|---|---|---|---|
| `app/Http/Controllers/BusinessController.php:205` (register), `:428` (settings) | store | Y | N |
| `Modules/Superadmin/Http/Controllers/BusinessController.php:305` | store | Y | N |
| `app/Utils/Util.php:877` (`{business_logo}` placeholder) | url `url('uploads/business_logos/…')` | N | Y |
| `app/Utils/NotificationUtil.php:267, 340` | url `url('storage/business_logos/…')` | N | Y — **broken today**: no `public/storage` link, and logos aren't in `storage/` |
| `resources/views/sale_pos/partials/guest_payment_form.blade.php:15` | url | N | Y |
| `Modules/Superadmin/Resources/views/business/show.blade.php:127` | url | N | Y |
| `Modules/Superadmin/Resources/views/subscription/partials/pay_flutterwave.blade.php:44` | url (sent to the Flutterwave checkout) | N | Y |

### 4.4 Invoice logo and letter head (`uploads/invoice_logos`) — public, used in PDFs

| File:line | Op | Helper | HC |
|---|---|---|---|
| `app/Http/Controllers/InvoiceLayoutController.php:85, 90, 202, 208` | store | Y | N |
| `app/Utils/TransactionUtil.php:1008-1009` (letter head), `:1013` (logo) → `$receipt_details` used by all `sale_pos/receipts/*.blade.php` and sell PDFs | exists + url | N | **Y** |
| `app/Utils/TransactionUtil.php:6593` (purchase PDF) | exists + url | N | **Y** |
| `app/Http/Controllers/PurchaseOrderController.php:836` (PO PDF) | exists + url | N | **Y** |

### 4.5 Customer-display carousel (`uploads/carousel_images`) — public

| File:line | Op | Helper | HC |
|---|---|---|---|
| `app/Http/Controllers/BusinessController.php:464` | store | Y | N |
| `resources/views/sale_pos/display.blade.php:172` | url | N | Y |

### 4.6 Transaction documents: sell, purchase, PO, purchase return, expense, payment (`uploads/documents`) — **private financial documents, currently public**

| File:line | Op | Helper | HC |
|---|---|---|---|
| `app/Http/Controllers/SellPosController.php:493, 1289` | store | Y | N |
| `app/Http/Controllers/PurchaseController.php:356, 711` | store | Y | N |
| `app/Http/Controllers/PurchaseOrderController.php:393, 682` | store | Y | N |
| `app/Http/Controllers/CombinedPurchaseReturnController.php:107, 273` | store | Y | N |
| `app/Utils/TransactionUtil.php:6075` (`createExpense`), `:6151` (`updateExpense`), `:6267` (`payContact`) | store | Y | N |
| `app/Http/Controllers/TransactionPaymentController.php:117, 294` | store | Y | N |
| `app/Http/Controllers/ExpenseController.php:769-774` (expense import from URL) | read + store via `file_put_contents` | N | Y |
| `app/Transaction.php:139`, `app/TransactionPayment.php:63` | url | (accessor) | Y |
| `app/Http/Controllers/SellController.php:306, 308` | url | N | Y |
| `app/Http/Controllers/PurchaseController.php:131, 133` | url | N | Y |
| `app/Http/Controllers/PurchaseOrderController.php:182, 184` | url | N | Y |
| `app/Http/Controllers/ExpenseController.php:195, 198` | url (inline Blade in a DataTable column) | N | Y |
| `app/Http/Controllers/TransactionPaymentController.php:767` | url | N | Y |
| `app/Http/Controllers/ReportController.php:2424, 2632` | url | N | Y |

Old documents are **never deleted** when replaced or when the transaction is deleted.

### 4.7 Temp, import and PDF scratch files (must stay local)

| File:line | Op | Notes |
|---|---|---|
| `app/Http/Controllers/ImportSalesController.php:105` | store `storeAs('temp', …)` on the **default disk** | Moves to R2 if the default disk changes… |
| `app/Http/Controllers/ImportSalesController.php:137, 178, 202, 207` | read `public_path('uploads/temp/…')` + `unlink` | …but is read back from local, so it **breaks**. |
| `Controller.php:100`, `LabelsController.php:343`, `PurchaseOrderController.php:849`, `SellPosController.php:2851, 2890, 2929`, `TransactionUtil.php:6491, 6605` | mPDF `tempDir => public_path('uploads/temp')` | mPDF cache inside the web root. |
| `ContactController.php:1539-1553` | ledger PDF written to `constants.mpdf_temp_path` (`storage/app/pdf`), mailed, `unlink` | Already local and outside public. OK. |

### 4.8 Backups — **critical**

| File:line | Op | Notes |
|---|---|---|
| `config/backup.php:166` `'disks' => ['local']`, `include => base_path()` | store | Daily `backup:run` (`app/Console/Kernel.php:25`) writes **zip archives of the whole app (including `.env` and the DB dump) to `public/uploads/<APP_NAME>/`**, which is reachable over HTTP. |
| `app/Http/Controllers/BackUpController.php:33, 119-121, 156` | list / stream / delete via `Storage::disk('local')` | Already disk-abstracted. |

### 4.9 Not upload-related (for completeness)

These matched the searches but don't involve user uploads:

- `layouts/partials/logo.blade.php:2-4` (`/uploads/logo.png`, an optional static site logo)
- `NewBusiness*Notification` (`resources/documents/YAIGO_customer_agreement.pdf`, a static repo file)
- `Install/*`, `BuildSpainLocationData`, `SpainLocationUtil`, `config/barcode.php`, `javascripts.blade.php:120`

**Totals:**

- **~45 write paths**: 40 through the helpers, 5 bypassing them (base64, two URL imports, sales-import temp, backups).
- **~25 hard-coded URL builders** across 15 files.
- **4 local deletes.**
- **5 `file_exists` gates.**
- **9 mPDF `tempDir`s.**
- **No custom JS** under `public/js` or `resources/js` builds upload URLs. The only JS one is inline in `sale_pos/display.blade.php`.

## 5. Local-filesystem dependencies that break with remote storage

1. **Security, independent of Cloudflare: backups and private documents are publicly reachable.**
   - Backups (§4.8) sit under the web root, and the folder name is just `APP_NAME`.
   - Sell, purchase, expense and payment documents and `media/` attachments are served by guessable names (`time()_slug.ext`).
   - Putting Cloudflare cache rules on `/uploads/*` would **cache and spread these further**, so they must move before option A goes live.
2. **Invoice and receipt logos vanish silently.** `TransactionUtil:1008-1013`, `:6593` and `PurchaseOrderController:836` show the logo only if `file_exists(public_path(…))`. With R2 this is always false, so every receipt, invoice PDF and PO PDF loses its logo and letter head without any error.
3. **Deletes do nothing remotely.** `Media::deleteMedia` and `ProductController:830` `unlink` local paths, so files stay on R2 forever. (They are already orphaned today in several flows, see §3 and §4.6.)
4. **Writes that bypass Storage:** `Media::uploadBase64Image` (`fopen`), and product/expense imports (`file_put_contents(public_path…)`). These keep writing locally.
5. **Sales import:** temp is written through the default disk but read with `public_path`, so it breaks if the default disk changes.
6. **mPDF:**
   - `tempDir` must stay local. It works today, but it's in the web root; move it to `storage/app/mpdf`.
   - Images are passed as **absolute URLs** (`asset(...)`), so mPDF downloads them over HTTP even today. With R2 public URLs this still works if the server can reach the CDN domain (outbound HTTPS, valid TLS), but every PDF does network fetches.
   - More robust options: pass a local temp copy (`Storage::get()` to a temp file, then a `file://` path), or a `data:` base64 URI for small logos. mPDF 8 supports both.
7. **Image resizing and thumbnails:** none. Intervention isn't installed, and `Media::thumbnail()` only sets `width`/`height` attributes. Nothing breaks, but there's an easy win later with Cloudflare Image Resizing.
8. **Imports and exports:** Excel exports stream directly (no upload storage). The **product-import manual** tells users to FTP images into `public/uploads/img`, and that workflow ends with R2.
9. **Backups:** `base_path()` currently includes `public/uploads`, so user files are inside the backup. Once files live in R2 they **won't** be backed up any more. You'll need R2 versioning or bucket replication, or a separate `rclone` job.

## 6. Options

### A. Cloudflare proxy and cache in front of the existing server

- **What:** orange-cloud the domain and add a cache rule for `/uploads/img/*`, `/uploads/business_logos/*`, `/uploads/invoice_logos/*`, `/uploads/carousel_images/*` (long Edge TTL). Optionally a separate `cdn.` hostname plus `ASSET_URL`.
  - ⚠️ `ASSET_URL` also rewrites `/css`, `/js` and `/img` assets. That's fine on the same origin, but every `asset()` call changes.
- **Files stay on the server.** Code changes are none to minimal.
- **Must do first:** move backups out of `public/uploads`, and **exclude** `/uploads/documents/*`, `/uploads/media/*`, `/uploads/temp/*` and `/uploads/<APP_NAME>/*` from caching (better still, block them; see the C plan).
- **Effort:** hours. **Risk:** low.
- **Benefit:** faster images and less bandwidth. **Doesn't solve:** disk usage, multiple servers, or private-file exposure.
- **Files touched:** `config/backup.php` (+ `.env` `BACKUP_DISK`); optionally `.env` `ASSET_URL`. Cloudflare dashboard rules.

### B. R2 as the default S3 disk (everything at once)

- **What:**
  - Add an `r2` disk: `driver s3`, `endpoint https://<account>.r2.cloudflarestorage.com`, `region auto`, `use_path_style_endpoint true`, `url https://cdn.example.com`.
  - Point all reads, writes and deletes at it.
  - `rclone` all existing files; flip the switch.
- **Are the helpers enough?** **No.** They cover ~90% of **writes**, but you must also change:
  - all ~25 URL builders (§4.1, §4.3–4.6),
  - 4 deletes,
  - 5 `file_exists` gates,
  - 3 bypass writers,
  - the sales-import temp path.

  Private documents also need a non-public bucket and signed URLs, so §4.6 and the document part of §4.2 need a route or controller, not just `Storage::url()`.
- **DB:** no data migration (bare filenames; folder fixed per feature, and object key = `{folder}/{filename}`).
  - **Keep `rawurlencode()`.** `Storage::url()` with a custom `url` does **not** encode, and `media` names contain raw client filenames (spaces, unicode).
- **Effort:** 2–4 dev days + QA. **Risk:** medium (big-bang cut-over).

### C. Hybrid (recommended long-term)

- Same refactor as B, but behind an `UPLOADS_DISK` flag with a **read fallback**.
- New uploads go to R2. URL resolution checks a cheap marker (or tries R2 and falls back to `/uploads/…`) until existing files are copied.
  - Alternatively, skip per-file logic: `rclone copy` everything to R2 first, switch writes, then run a final `rclone sync`. With bare filenames there's nothing to rewrite in the DB.
- **Public bucket** (via custom domain) for `img`, `business_logos`, `invoice_logos`, `carousel_images`, and product-variation media.
- **Private bucket** (signed URLs, `Storage::temporaryUrl()`, ~5–15 min) for `documents/`, shipping/sell/account `media`, and future contracts.
  - The URL accessors would return a route such as `route('uploads.show', $id)` that authorises and then redirects to the signed URL.
- **Effort:** 3–5 dev days + one-off migration. **Risk:** low–medium (reversible with the flag).

**Migration for B/C:**

- Set up `rclone` with an R2 remote (S3 provider = Cloudflare).
- `rclone copy public/uploads/<folder> r2:<bucket>/<folder>` for each folder, excluding `temp/` and backups.
- Verify with `rclone check`.
- Final `rclone sync` during a short write freeze.
- No DB changes needed.

**PDFs:** keep mPDF `tempDir` local (`storage/app/mpdf`). For logos, change the gate from `file_exists(public_path)` to `Storage::exists()` (cached), and pass either the public CDN URL or a temp local copy. Prefer the local copy for receipts printed offline or behind firewalls.

**Cache-busting:** names are already unique (`time()_…`), so long immutable caching is safe. Replacing a logo uploads a new name. If you ever overwrite in place, add `?v=updated_at`.

**CORS:**
- Not needed for `<img>` / `<a download>`.
- Needed only if JS fetches files: canvas, `fetch()` for previews, or mPDF via JS. In that case, set R2 CORS `GET` for the app origin.
- `download="…"` attributes are **ignored for cross-origin URLs**. For documents, set `Content-Disposition` on the object or on the signed URL (`ResponseContentDisposition`) to keep the original download names.

## 7. Proposed refactor (later — not implemented)

1. **Config:**
   - New `uploads` disk = `env('UPLOADS_DISK', 'public_uploads')`.
   - `public_uploads` = the current local `public_path('uploads')` with `url => APP_URL/uploads`.
   - `r2_public` and `r2_private` S3 disks.
   - **Leave the framework default disk alone** so packages and backups don't move unexpectedly.
2. **`App\Services\UploadStorage`** (one class):
   - `store(UploadedFile $f, string $folder, string $type): string` holds today's `Util::uploadFile` logic. It also sanitises the `Media` original name.
   - `storeContents(string $folder, string $name, string $bytes)` for base64 and URL imports.
   - `url(string $folder, ?string $file, bool $private = false): ?string` uses `rawurlencode`, `Storage::url` or `temporaryUrl`.
   - `exists()`, `delete()`.
   - `localCopy(string $folder, string $file): ?string` gives a temp file for mPDF.
3. **Route through it:**
   - `Util::uploadFile` and `Media::uploadFile` / `uploadBase64Image` / `deleteMedia` / `display_url` / `display_path` delegate to it.
   - The `Product`, `Transaction` and `TransactionPayment` accessors use it.
   - Add `Business::logo_url` and `InvoiceLayout::logo_url` / `letter_head_url` accessors.
4. **Replace the ~25 hard-coded builders** (§4) with those accessors, and the 5 `file_exists` gates with `UploadStorage::exists()`.
5. **Imports:** use `storeContents()`. Restrict URL fetching (scheme allow-list, size limit, timeout, block private IPs).
6. **Sales import:** use `$request->file()->getRealPath()` directly, or an explicit `local` temp disk.
7. **mPDF:** `tempDir => storage_path('app/mpdf')` in one shared factory (there are 9 copies today).
8. **Backups:** `BACKUP_DISK` → a private disk (R2 private or `storage/app/backups`). Exclude `public/uploads` once it is in R2, and add an R2 backup or replication strategy.
9. **Private documents:** an `uploads.show` route with auth and business-ownership checks that redirects to a short-lived signed URL.

After this, switching storage is **`.env` only** (`UPLOADS_DISK=r2_public`, `UPLOADS_PRIVATE_DISK=r2_private`).

## 8. Open questions / decisions for you

1. **Production volume:** please run `du -sh public/uploads/*` and `find public/uploads -type f | wc -l` on the server. This decides how long the `rclone` copy takes and what R2 costs.
2. **Backups in `public/uploads/<APP_NAME>/`:** do they exist in production? If so, treat this as **urgent** (move them, block the path, rotate secrets if any were ever reachable).
3. **Private vs public:** should sell, purchase, expense and payment documents and `media` attachments become private (signed URLs)? This is recommended; it affects the effort for B/C.
4. **Domain:** which CDN hostname (e.g. `cdn.yaigo…`)? Do you want `ASSET_URL` for CSS/JS too, or only uploads?
5. **Product-import workflow:** users currently FTP images into `public/uploads/img`. Keep supporting that (a sync job), or require image URLs or a ZIP upload?
6. **R2 bucket layout:** one bucket with `public/` and `private/` prefixes, or two buckets? (Two buckets are simpler for public access settings.)
7. **Cleanup:**
   - OK to delete orphaned files (old logos and documents never removed)?
   - OK to remove the unused `uploads/cms`?
   - OK to remove the stray `Modules/{Http,Resources,…}` copy?
8. **Existing bugs found during the audit** (fix independently of the migration?):
   - `NotificationUtil:267/340` builds `storage/business_logos/…`, so the logo is broken in notification templates.
   - Product/expense URL imports allow unrestricted server-side fetches (SSRF).
