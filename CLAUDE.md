# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A teaching/training project system (实训项目教学系统) built on the **BuildAdmin** framework. It provides a backend admin dashboard and API services for student projects (e.g., address book backend). Based on ThinkPHP 8 (PHP) + Vue 3 + TypeScript + Element Plus.

## Development Commands

### Backend (PHP - ThinkPHP 8)

```bash
# Start PHP dev server
php think run                          # Default port 8000
php think run -p 8153                  # Custom port (matches frontend proxy)

# Database migrations (Phinx-based)
php think migrate:run                  # Run pending migrations
php think migrate:rollback             # Rollback last migration

# Clear caches
php think clear                        # Clear runtime cache
```

### Frontend (Vue 3 SPA - `web/`)

```bash
cd web
pnpm install                           # Install dependencies
pnpm dev                               # Vite dev server (port from .env.development)
pnpm build                             # Production build (outputs to ../public/)
```

### Frontend (Nuxt 3 - `web-nuxt/`)

```bash
cd web-nuxt
pnpm install
pnpm dev                               # Nuxt dev server
pnpm build                             # Production build
```

## Architecture

### Multi-App PHP Backend (`app/`)

ThinkPHP multi-app architecture with three apps routed by URL prefix:

- **`app/admin/`** - Admin dashboard API (`/admin/*`). Controllers handle CRUD for all backend management features.
- **`app/api/`** - Public-facing API (`/api/*`, default app). Student-facing endpoints.
- **`app/common/`** - Shared code across apps:
    - `controller/Backend.php`, `Frontend.php`, `Api.php` - Base controllers with auth, CRUD traits
    - `model/` - Shared Eloquent-style models (ThinkORM)
    - `library/` - Utilities: Auth, Token, SnowFlake ID generator
    - `middleware/` - HTTP middleware (auth checks, CORS)

### Extended Classes (`extend/ba/`)

PSR-0 autoloaded utilities outside the app directory: `Auth`, `Token`, `Tree`, `Captcha`, `Random`, `Terminal`. These are BuildAdmin framework utilities.

### Module/Plugin System (`modules/`)

Optional installable modules: `alioss` (Aliyun OSS), `mail`, `wangeditor` (rich text), `workerman` (WebSocket), `area`, `nuxt`.

### Vue 3 Frontend (`web/src/`)

- **Path alias**: `/@/` maps to `web/src/` (note: not `@/`, uses `/@/` throughout)
- **Routing**: Static routes in `router/static/`, dynamic routes loaded from backend admin permission rules
- **State**: Pinia stores in `stores/` - `config`, `adminInfo`, `userInfo`, `navTabs`, `terminal`, `memberCenter`
- **API layer**: `api/backend/` and `api/frontend/` - organized matching backend controller structure
- **Layouts**: Separate `backend/` (admin dashboard) and `frontend/` (member-facing) layouts
- **i18n**: Chinese (`zh-cn`) and English (`en`) in `lang/`
- **UI**: Element Plus components + custom `baInput`, `baTable` components built on top

### Database

- MySQL 5.7+ with `utf8mb4` charset
- Table prefix: `ba_`
- Migrations in `database/migrations/` (Phinx format)
- Config in `.env` file (see `.env-example`)

## Key Conventions

- **Language**: The project is primarily Chinese (zh-cn). Code comments, commit messages, and UI text are in Chinese. Both Chinese and English i18n are supported.
- **CRUD pattern**: Backend views follow a consistent pattern: `index.vue` (table listing) + `popupForm.vue` (create/edit dialog) per feature module.
- **Build output**: `pnpm build` in `web/` outputs directly to `public/` directory, which is the PHP web root.
- **API base URL**: Frontend dev server proxies to PHP backend. Configured via `VITE_AXIOS_BASE_URL` in `web/.env.development`.
- **Config**: `config/buildadmin.php` controls CORS, token settings, captcha, and system-level config. `config/database.php` reads from `.env`.
