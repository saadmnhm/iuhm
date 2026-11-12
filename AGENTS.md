# IUHM ERP — Agent Instructions

Laravel 12 + Livewire 4 (class components) + Tailwind 4/Vite, French-first. Candidates use the `candidat` guard under `/user`; staff use the `web` guard under `/admin`. Domain: programmes/submissions, dynamic forms, finance, equipment, HR, blog/news, roles.

## Commands

| Task | Command |
|------|---------|
| First-time setup | `composer run setup` (copies `.env` only if missing, `migrate --force`, `npm install`, `npm run build`) |
| Dev (all) | `composer run dev` (serve + `queue:listen --tries=1` + Vite via concurrently) |
| Tests (all) | `composer run test` (runs `config:clear` first, then `php artisan test`; ~38 tests/11s) |
| Single test | `php artisan test --filter=<ClassName>` or `--filter='<test name>'` |
| Lint | `vendor/bin/pint` |
| Migrate | `php artisan migrate` |
| Fresh migrate + seed | `php artisan migrate:fresh --seed` |
| Clear caches | `php artisan optimize:clear` |
| Queue worker | `php artisan queue:listen --tries=1` |
| Build assets | `npm run build` |

`composer run setup` does **not** create the MySQL database — `.env.example` points at DB `iuhm`, which must already exist. Use `composer run test` rather than bare `artisan test`: the `config:clear` step avoids stale local config overriding `phpunit.xml` env.

## Auth, Roles, Modules

- Guard/prefix split: admin login `/admin/login` (`User`), candidate login `/user/login` (`Candidat`). Middleware aliases in `bootstrap/app.php`: `admin`, `super_admin`, `candidat`, `profile.complete`, `module`.
- Candidate project/form routes require `profile.complete`; admin modules require `module:<key>`.
- Permissions are custom `Role` + `RolePermission` (not spatie), checked by `Role::canAccess()`; `super_admin` always passes. `module:users` has an exception allowing a user to view their own record.
- The "Gestion des Rôles" UI only lists keys from `config/modules.php` `definitions`. `ModuleAccess` still calls `Role::canAccess()` for every key, so a route using an undefined key is effectively super_admin-only until the definition is added. Currently undefined but used in routes: `media`, `article`, `actualite`, `publication`, `apropos`.
- Blade: `@canModule('rh') ... @endCanModule`.
- Dev access lock: `AdminMiddleware` logs out roles that cannot bypass; while enabled, `ModuleAccess` short-circuits module permission checks for bypass roles. Cached 1h under `dev_access.settings` (`setDevelopmentAccessSettings()` clears it). `Role::clearPermissionCache()` clears only the role permission/admin-access caches.

## Layout & Conventions

- Models extend `BaseModel`, which has an `$autoRelations` global scope that eager-loads listed relations (no model sets it today) — don't add redundant `with()` if you set it.
- Livewire classes live in `app/Livewire/<Area>/X.php`, views mirror at `resources/views/livewire/<area>/x.blade.php`. Full-page components declare `#[Layout('layouts.admin', ['header' => '...'])]` or `->layout(...)`; `header` drives the admin page header. Front/candidate pages use `layouts.app`, public site uses `layouts.site`.
- Uploads go to `base_path('uploads/...')` directly (not `Storage`); `/uploads` is gitignored. `GET /uploads/{path}` is public; `GET /uploads/{path}/download` requires a `web` or `candidat` session.
- Locale: `AppServiceProvider::boot()` forces the app locale to `fr` when the session has none, even though `.env` sets `APP_LOCALE=en`. `GET /lang/{locale}` accepts `en|ar|fr`, but `POST /locale/{locale}` uses `config('app.supported_locales')` which is only `['ar','fr']`.
- API: `routes/api.php` is registered inside the `web` group (so sessions apply), CSRF is excluded for `api/v1/*`. Public read endpoints `blog|news|deliverables|media|pages|navigation|projects|history` at 60/min; writes `newsletters|contacts` at 10/min. Health: `GET /api/v1/health` (plus Laravel's `/up`).
- PDFs: `barryvdh/laravel-dompdf` through `PrintController`; routes named `*.print.*` under `/admin/...`.

## Testing

- `phpunit.xml` forces SQLite `:memory:`, array cache/session, sync queue, array mail.
- Only `UserFactory` exists. Tests either use `RefreshDatabase` or create the schema they need in `setUp()` with `Schema::create` and instantiate models directly (see `FormulaireSubmissionDetailTest`).
- Enum column changes are plain `->change()` migrations; Laravel's SQLite support handles them, so `RefreshDatabase` works.

## Gotchas

- `.env` is **not** committed (`.env`/`.env.*` are ignored; only `.env.example` is tracked). `.env.example` has no `CANDIDAT_MASTER_PASSWORD`; `FrontAuthController` reads `CANDIDAT_MASTER_PASSWORD` / `CANDIDAT_MASTER_PASSWORD_HASH` via `env()` (not `config()`), so add them to `.env` and note `config:cache` would make them null.
- Queue is `database`; mail defaults to `log`.
- No CI workflows and no `opencode.json` in this repo.
