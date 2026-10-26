# IUHM ERP - Agent Instructions

Laravel 12 app with Livewire 4, Tailwind CSS 4, Vite. Manages candidates, projects, forms, finance, materials, HR, and content. Dual admin (`web` guard) / candidate (`candidat` guard) auth.

## Commands

| Task | Command |
|------|---------|
| First-time setup | `composer run setup` (composer install, copy `.env` from `.env.example` if missing, key:generate, migrate --force, npm install, npm run build) |
| Dev server (all) | `composer run dev` (concurrently: `php artisan serve`, `queue:listen --tries=1`, `npm run dev`) |
| Tests (all) | `composer run test` (runs `config:clear` then `php artisan test`) |
| Single test | `php artisan test --filter=<TestName>` |
| Lint (Pint) | `vendor/bin/pint` |
| Migrate | `php artisan migrate` |
| Fresh migrate + seed | `php artisan migrate:fresh --seed` |
| Clear caches | `php artisan optimize:clear` |
| Queue worker | `php artisan queue:listen --tries=1` |
| Build assets | `npm run build` |

`composer run test` runs `config:clear` first — don't skip it, cached config can change test behavior.

## Architecture

- **Auth guards**: `web` (admin, `User` model) under `/admin`; `candidat` (`Candidat` model) under `/user`. Route aliases: `admin`, `candidat`, `module`, `super_admin`.
- **Permissions**: custom `Role` + `RolePermission` (not spatie). Module keys in `config/modules.php`; to add a module, add an entry there, protect its route with `module:key`, and wrap its sidebar link. Permissions are managed via the "Gestion des Rôles" UI — no code changes.
- **Module access**: `->middleware('module:rh')` checks `Role::canAccess()`; Blade `@canModule('module')`. super_admin always has full access.
- **Development lock**: `Role::isDevelopmentAccessLocked()` (stored in `association_parameters`, cached 1hr) blocks the admin panel unless the role can bypass (`super_admin` always bypasses); the candidat area is locked out entirely. Clear cache with `Role::clearPermissionCache()`.
- **BaseModel**: auto-loads `$autoRelations` via a global scope — don't eager-load what's already in `$autoRelations`.
- **Locale**: `SetLocale` middleware is global (reads `session('locale', config('app.locale'))`). `AppServiceProvider::boot()` defaults the session locale to `'fr'` **even though `.env` has `APP_LOCALE=en`** — the site renders French by default. `GET /lang/{locale}` and `POST /locale/{locale}` store the session locale.
- **API**: `routes/api.php` is mounted inside the `web` middleware group (per `bootstrap/app.php`), with CSRF disabled for `api/v1/*`. Public read-only endpoints under `api/v1/{blog,news,deliverables,media,pages,navigation,projects,history}` (throttle 60/min); write endpoints under `api/v1/{newsletters,contacts}` (throttle 10/min). Health check: `GET /api/v1/health`.
- **PDF**: `barryvdh/laravel-dompdf` via `PrintController` (e.g. `/candidats/{id}/print/*`, `/finance/transaction/{id}/print`).

## Directories

- `app/Livewire/Admin/` — admin components (users, finance, material, rh, formulaires, ...)
- `app/Livewire/Front/` — candidate components (dashboard, projets, blog, forms)
- `app/Models/` — ~45 models + `BaseModel`, all domain models extend `BaseModel`
- `app/Http/Middleware/` — AdminMiddleware, CandidatMiddleware, EnsureCandidatProfileComplete, ModuleAccess, SetLocale, SuperAdminMiddleware
- `routes/web.php` — all web routes; `routes/api.php` — public API v1; `routes/console.php` — artisan commands
- `uploads/` — file uploads (NOT `storage/app/public`), served via `/uploads/{path}` and `/uploads/{path}/download`; both require an authenticated `web` or `candidat` guard (403 otherwise).

## Testing

- PHPUnit with SQLite `:memory:` (`phpunit.xml`). Tests extend `Tests\TestCase`.
- Feature tests either build their own schema in `setUp()` (e.g. `FormulaireSubmissionDetailTest`) or use `RefreshDatabase` (e.g. `ExampleTest`, which runs migrations). The MySQL-only enum migration is driver-guarded so migrations run on SQLite.
- Run one test: `php artisan test --filter=<TestName>`

## Frontend

- Vite entry: `resources/css/app.css`, `resources/js/app.js`; Tailwind CSS 4 via `@tailwindcss/vite`.
- Dev assets: `npm run dev` (or via `composer run dev`); production: `npm run build`.

## Environment / Gotchas

- `.env` is committed (copy from `.env.example` is a no-op if present; composer `setup` script guards with `file_exists('.env')`). Default DB is MySQL (testing uses SQLite `:memory:`).
- Queue: `database` connection. Mail driver default per `.env.example` is `log`.
- `CANDIDAT_MASTER_PASSWORD` env var exists (`.env`).
- No CI workflows and no `opencode.json` in this repo.
