# ReviewCents

A **mobile-first platform for developers and aspiring developers** to learn web development & web design, review learning resources, prove their skills with end-of-learning tests, tackle challenges & missions, get custom tasks from mentors, chat in real time, and show off their work.

Built with **Laravel 13 · PHP 8.4 · Tailwind CSS · Alpine.js · Laravel Reverb** on **SQLite**.

## Features

- 📱 **Mobile-first UI** — sticky top nav, hamburger menu, bottom tab bar, dark mode.
- 👤 **Accounts & roles** — learner / developer / admin, editable profile with avatar, bio & headline.
- ⭐ **Resource reviews** — rate & review courses, tutorials, tools, books, bootcamps (avg rating auto-updates).
- 🗺️ **Learning roadmaps** — step-by-step paths for web dev & design with per-user progress tracking.
- 📝 **End-of-learning tests** — every roadmap ends with a quiz (**minimum 3 questions each**); scores & pass/fail are recorded and shown on your dashboard and profile.
- ⚡ **Challenges & missions** — practice by building; submit your work for mentor feedback.
- 🎯 **Admin-assigned custom tasks** — admins assign tasks/missions to specific users, then **rate them and leave feedback**.
- 💬 **Real-time chat** — 1:1 messaging powered by **Laravel Reverb** (WebSockets) with live message delivery.
- 🚀 **Showcase** — a portfolio of community projects, linked from public profiles.
- 🛠️ **Admin panel** — manage categories, resources, roadmaps, quizzes, challenges, users, submissions & assignments.

## Requirements

- PHP 8.2+ (developed on 8.4), Composer
- Node.js 18+ & npm

## Getting started

**One command** — installs deps, sets up `.env`, creates & seeds the SQLite database, links storage, and builds assets:

```bash
cp .env.example .env    # only needed the first time
composer setup
```

Then start everything (web server + queue + Reverb + Vite) with a single command:

```bash
composer dev
```

Open **http://localhost:8000**.

<details>
<summary>Prefer manual steps?</summary>

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm run build            # or: npm run dev
php artisan serve
```
</details>

### Real-time chat

`composer dev` already runs Reverb + the queue worker. If you run pieces manually, live chat needs both alongside `serve`:

```bash
php artisan reverb:start     # WebSocket server (port 8080)
php artisan queue:work       # processes queued broadcast events
```

Messages always send and appear for the sender immediately; the other participant sees them live once Reverb + the queue worker are running.

### Theme

Use the sun/moon toggle in the nav to switch light/dark — your choice is remembered, and it defaults to your OS preference.

## Demo accounts

After seeding:

| Role    | Email                      | Password   |
|---------|----------------------------|------------|
| Admin   | `admin@reviewcents.test`   | `password` |
| Learner | `demo@reviewcents.test`    | `password` |

## Testing

```bash
php artisan test
```

## Deploying to Laravel Cloud (Postgres)

The app is database-agnostic (SQLite for local, Postgres in production). Laravel Cloud
injects the database credentials automatically once you attach the Postgres database —
you do **not** set `DB_*` by hand.

**Deploy command** (Cloud → app → Deploy commands):

```bash
php artisan migrate --force
```

> First deploy only, if you want the demo content: also run `php artisan db:seed --force`.
> Never run `migrate:fresh`/`migrate --seed` against production — it wipes data.

**Environment variables** to set in the Cloud dashboard:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain

# Uploads must go to object storage (local disk is ephemeral on Cloud)
FILESYSTEM_DISK=s3        # + the S3/object-storage keys from Cloud

# Background work (queued broadcasts, mail) — run a Worker process in Cloud
QUEUE_CONNECTION=database
```

**Real-time chat (optional):** enable a Reverb process in Cloud, then set `REVERB_*`
and `BROADCAST_CONNECTION=reverb`. The `VITE_REVERB_*` vars must be present **at build
time** (they're compiled into JS). If you don't run Reverb, set `BROADCAST_CONNECTION=log`
— messages still send and persist; only live push is disabled.

**Other databases:** the same code runs on MySQL — set `DB_CONNECTION=mysql` and the
connection vars, then `php artisan migrate`.
