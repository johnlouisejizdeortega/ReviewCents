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

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database (SQLite by default)
touch database/database.sqlite
php artisan migrate --seed

# 4. Build assets
npm run build      # or: npm run dev  (for hot reload)

# 5. Run the app
php artisan serve
```

Open http://localhost:8000.

### Real-time chat

Live chat needs the Reverb WebSocket server and a queue worker running alongside `serve`:

```bash
php artisan reverb:start     # WebSocket server (port 8080)
php artisan queue:work       # processes queued broadcast events
```

Messages always send and appear for the sender immediately; the other participant sees them live once Reverb + the queue worker are running.

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

## Switching to MySQL (production)

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reviewcents
DB_USERNAME=root
DB_PASSWORD=
```

Then run `php artisan migrate --seed`.
