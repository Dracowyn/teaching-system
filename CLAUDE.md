# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A teaching/training project system (实训项目教学系统) built on the **BuildAdmin** framework. It provides a backend admin dashboard and API services for student projects (e.g., address book backend). Stack: ThinkPHP 8 (PHP ≥8.2) + Vue 3 + TypeScript + Element Plus + Pinia + Vite.

## Development Commands

### Backend (PHP - ThinkPHP 8)

```bash
# Start PHP dev server
php think run                          # Default port 8000
php think run -p 8153                  # Port 8153 — matches the frontend's VITE_AXIOS_BASE_URL

# Database migrations (Phinx-based via topthink/think-migration)
php think migrate:run                  # Run pending migrations
php think migrate:rollback             # Rollback last migration

# Clear caches
php think clear                        # Clear runtime cache
```

### Frontend (Vue 3 SPA - `web/`)

```bash
cd web
pnpm install
pnpm dev                               # Vite dev server
pnpm build                             # Production build → ../public/
pnpm lint                              # ESLint
pnpm lint-fix                          # ESLint --fix
pnpm typecheck                         # vue-tsc --noEmit
pnpm format                            # Prettier write
```

Note: `pnpm dev` and `pnpm build` both run `src/utils/build.ts` (generates icon/route metadata).

### Frontend (Nuxt 3 - `web-nuxt/`)

```bash
cd web-nuxt
pnpm install
pnpm dev
pnpm build
```

## Architecture

### Multi-App PHP Backend (`app/`)

ThinkPHP multi-app architecture (`topthink/think-multi-app`) with three apps routed by URL prefix:

- **`app/admin/`** — Admin dashboard API (`/admin/*`). Controllers grouped by domain subfolders (`auth/`, `user/`, `card/`, `routine/`, `security/`, `app/`, `crud/`, `mail/`, `music/`, `news/`, `wallpaper/`).
- **`app/api/`** — Public-facing / student API (default app, served at `/api/*`).
- **`app/common/`** — Shared code:
    - `controller/Backend.php`, `Frontend.php`, `Api.php` — base controllers carrying CRUD traits, auth init, and query-builder helpers.
    - `model/` — ThinkORM models, mirroring controller subfolders by domain.
    - `library/`, `service/`, `event/`, `facade/`, `validate/`, `middleware/`.

### Backend CRUD Convention

Admin controllers extend `app\common\controller\Backend` and configure CRUD behavior via class properties rather than overriding methods. When you read or modify a controller, these are the levers:

- `$model` — instantiated in `initialize()`.
- `$preExcludeFields` — fields stripped from create/update payloads (typically `['id', 'admin_id', 'create_time', 'update_time']`).
- `$dataLimit` — data isolation mode. `'personal'` filters rows by the current admin via `admin_id`; the table must carry that column for it to work.
- `$withJoinTable` / `$withJoinType` — eager-loaded relations rendered with `withJoin()`.
- `$quickSearchField` — fields targeted by the global quick-search input.

The base `index()` typically calls `queryBuilder()` to compose `$where/$alias/$limit/$order` from request params; subclasses extend the where clause with domain-specific filters before paginating.

### Frontend CRUD Convention

Each backend feature has a matching `web/src/views/backend/<module>/` directory containing:
- `index.vue` — table listing built on `baTable` (custom wrapper over Element Plus tables).
- `popupForm.vue` — create/edit dialog built on `baInput`.

API calls live in `web/src/api/backend/<module>/` mirroring the controller path.

### Extended Classes (`extend/ba/`)

PSR-0 autoloaded under namespace `ba\`. Available utilities: `Auth`, `Captcha`, `ClickCaptcha`, `Date`, `Depends`, `Exception`, `Filesystem`, `Random`, `TableManager`, `Terminal`, `Tree`, `Version`. These are BuildAdmin framework helpers — prefer reusing them over hand-rolling equivalents.

`composer.json` autoload: `app\` and `modules\` are PSR-4; `extend/` is PSR-0 (so `extend/ba/Auth.php` ⇒ `ba\Auth`).

### Module/Plugin System (`modules/`)

Optional installable modules registered via `app\AppService`: `alioss` (Aliyun OSS), `mail`, `wangeditor` (rich text), `workerman` (WebSocket), `area`, `nuxt`.

### Vue 3 Frontend (`web/src/`)

- **Path alias**: `/@/` maps to `web/src/` (note the leading slash — not the conventional `@/`).
- **Routing**: Static routes in `router/static/`; dynamic routes built from backend admin permission rules at runtime.
- **State**: Pinia stores in `stores/` — `config`, `adminInfo`, `userInfo`, `navTabs`, `terminal`, `memberCenter`. Persisted via `pinia-plugin-persistedstate`.
- **API layer**: `api/backend/` and `api/frontend/`, organized to mirror PHP controller paths.
- **Layouts**: Separate `backend/` (admin dashboard) and `frontend/` (member-facing) layout trees in `layouts/` and `views/`.
- **i18n**: Chinese (`zh-cn`, primary) and English (`en`) under `lang/`.
- **UI**: Element Plus + custom `baInput`, `baTable` building blocks. Icons via auto-generated SVG sprite (`svgBuilder` in `vite.config.ts`).

### Database

- MySQL 5.7+ with `utf8mb4`. Table prefix: `ba_`.
- Phinx migrations in `database/migrations/`. Admin menu/permission rules live in `ba_admin_rule` and are seeded by migrations.
- Connection config read from `.env` via `config/database.php` (see `.env-example`).

## Key Conventions

- **Language**: Project is primarily Chinese (zh-cn). Code comments, commit messages, and UI text are in Chinese; both zh-cn and en i18n are supported.
- **Commit style**: Conventional commits in Chinese, e.g. `feat(user): 用户列表新增快速筛选下拉框`.
- **Build output**: `pnpm build` in `web/` writes directly to `public/` (the PHP web root) — there is no separate frontend dist served by a CDN in dev.
- **API base URL**: Frontend calls the PHP backend at `VITE_AXIOS_BASE_URL` (default `http://localhost:8153` per `web/.env.development`). Run `php think run -p 8153` to match.
- **System config**: `config/buildadmin.php` controls CORS, token settings, captcha, terminal, and admin auth behavior.
