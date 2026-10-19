# IUHM ERP - Agent Instructions

Laravel 12 application with Livewire 4, Tailwind CSS 4, Vite. Manages candidates, projects, forms, finance, materials, HR, and content.

## Commands
| Task | Command |
|------|---------|
| First-time setup | `composer run setup` (composer install, copy `.env`, key:generate, migrate, npm install, npm run build) |
| Dev server (all) | `composer run dev` (runs `serve` + `queue:listen` + `vite` via concurrently) |
| Tests | `composer run test` (runs `config:clear` then `php artisan test`) or `php artisan test` |
| Single test | `php artisan test --filter=FormulaireSubmissionDetailTest` |
| Lint (Pint) | `vendor/bin/pint` |
| Migrate | `php artisan migrate` |
| Fresh migrate + seed | `php artisan migrate:fresh --seed` |
| Clear caches | `php artisan optimize:clear` |
| Queue worker | `php artisan queue:listen --tries=1` |
| Build assets | `npm run build` |

## Architecture
- **Dual auth guards**: `web` (admin/User model) and `candidat` (Candidat model). Admin routes live under `/admin` (guard `web`); candidate routes under `/user` (guard `candidat`).
- **Role/Permission**: custom `Role` + `RolePermission` models (not spatie/laravel-permission). Module keys are defined in `config/modules.php`.
- **Module access**: route middleware `module:key` (e.g. `module:finance`) checks `Role::canAccess()`; Blade directive `@canModule('key')`. If a module key is not in `config/modules.php`, access is granted to all admins.
- **Development lock**: `Role::isDevelopmentAccessLocked()` blocks admin/candidat access unless the role can bypass (`super_admin` always bypasses).
- **BaseModel**: auto-loads `$autoRelations` via a global scope.
- **Locale**: `SetLocale` middleware is global (registered in `bootstrap/app.php`) and uses `config('app.locale')` from `APP_LOCALE` (`.env` = `en`); `AppServiceProvider::boot()` defaults the session locale to `fr`.

## Directories
- `app/Livewire/Admin/` — admin panel components (users, finance, material, rh, formulaires, ...)
- `app/Livewire/Front/` — candidate-facing components (dashboard, projets, blog, forms)
- `app/Models/` — 50+ models, all extend `BaseModel`
- `app/Http/Middleware/` — AdminMiddleware, CandidatMiddleware, ModuleAccess, SetLocale, SuperAdminMiddleware
- `routes/web.php` — all web routes (admin + front); `routes/api.php` — public API v1; `routes/console.php` — artisan commands
- `uploads/` — file uploads (NOT `storage/app/public`), served via `/uploads/{path}` with auth check

## Testing
- PHPUnit with SQLite `:memory:` (`phpunit.xml`). Tests extend `Tests\TestCase`.
- Feature tests build their own schema in `setUp()` (no migrations run for those tables); `RefreshDatabase` trait is available.
- Run one test: `php artisan test --filter=FormulaireSubmissionDetailTest`

## Frontend
- Vite entry: `resources/css/app.css`, `resources/js/app.js`; Tailwind CSS 4 via `@tailwindcss/vite`.
- Dev assets: `npm run dev` (or via `composer run dev`); production: `npm run build`.

## PDF
- `barryvdh/laravel-dompdf` via `PrintController` (e.g. `/candidats/{id}/print/*`, `/finance/transaction/{id}/print`).

## Environment / Gotchas
- Copy `.env.example` to `.env`; default DB is MySQL (testing uses SQLite `:memory:`).
- Queue: `database` connection; mail: `log` driver by default.
- Role permission cache: 1hr TTL; clear with `Role::clearPermissionCache()`.
- `CANDIDAT_MASTER_PASSWORD` env var exists (`.env`).
- Uploads require an authenticated `web` or `candidat` guard (403 otherwise).
- No CI workflows or `opencode.json` in this repo.
