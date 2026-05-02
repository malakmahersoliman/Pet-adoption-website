# The Stray Station — Pet Adoption Web Application

A full-stack pet adoption website with **PHP session authentication**, **MySQL**, **relational database design**, **server-side validation**, and **full CRUD** for shelter pet listings. The front end uses HTML, CSS (Bootstrap-based theme), and JavaScript that talks to PHP JSON endpoints where needed.

---

## Table of contents

- [Features](#features)
- [Tech stack](#tech-stack)
- [Architecture](#architecture)
- [Database schema](#database-schema)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running locally](#running-locally)
- [Project structure](#project-structure)
- [Pages overview](#pages-overview)
- [HTTP / JSON API](#http--json-api)
- [Security notes](#security-notes)
- [Troubleshooting](#troubleshooting)
- [Credits](#credits)

---

## Features

| Area | Details |
|------|---------|
| **Authentication** | Register and log in; passwords hashed with `password_hash()` / verified with `password_verify()`; PHP sessions with cookie-based state. |
| **Authorization** | Pet **create / update / delete** require a logged-in user plus **CSRF token** (issued when signed in via `session_info.php`). |
| **Full CRUD (pets)** | List (public), create, update, delete (authenticated) via JSON APIs under `php/api/`. |
| **Adoption requests** | HTML form POST to `php/submit_form.php`; rows stored in `adoption_submissions`; optional `pet_id` must reference an existing pet (**foreign key**). |
| **Validation** | Shared rules in `php/validate.php` for emails, passwords, names, pet types, URLs, etc.; applied on signup, adoption POST, and pet APIs. |
| **Responsive UI** | Bootstrap-based layout; browse pets with filters; shelter dashboard with modal edit form. |

---

## Tech stack

- **Front end:** HTML5, CSS (`css/styles.css` — includes Bootstrap-based theme), JavaScript (ES5-style for broad compatibility).
- **Back end:** PHP 7.4+ (uses typed patterns compatible with PHP 8).
- **Database:** MySQL / MariaDB with InnoDB, UTF-8 (`utf8mb4`).
- **Server:** Any PHP-capable host (Apache, nginx + PHP-FPM, or PHP’s built-in development server).

---

## Architecture

```
Browser (HTML/JS)
       │
       ├── Form POST ─────────────► php/Login.php, signup.php, submit_form.php
       │
       └── fetch(..., { credentials: 'same-origin' })
              │
              ├── php/session_info.php   (JSON: session + CSRF)
              └── php/api/*.php          (JSON: pets CRUD)
                      │
                      ▼
               MySQL (database: stray_station)
```

Static assets (`*.html`, `css/`, `js/`) are served from the **document root**, which must be the **repository root** so paths like `php/Login.html` and `php/api/pets_list.php` resolve correctly.

---

## Database schema

Single database: **`stray_station`** (see [`sql/stray_station.sql`](sql/stray_station.sql)).

| Table | Purpose |
|-------|---------|
| **`adopters`** | Registered users: name, email (unique), phone, password hash. |
| **`pets`** | Animals available for adoption: name, type (`dogs` / `cats` / `diff`), description, image URL. |
| **`adoption_submissions`** | Applications: contact fields + optional **`pet_id`** → **`FOREIGN KEY (pet_id) REFERENCES pets(id)`** `ON DELETE SET NULL`. |

The SQL file also seeds a few sample pets so the browse page works immediately after import.

---

## Prerequisites

- **PHP** 7.4 or newer with extensions: `mysqli`, `json`, `session` (standard on most installs).
- **MySQL** or **MariaDB**.
- A terminal (for importing SQL and optionally running `php -S`).

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/<your-username>/Pet-adoption-website.git
   cd Pet-adoption-website
   ```

2. **Create the database**

   ```bash
   mysql -u root -p < sql/stray_station.sql
   ```

   This creates the database `stray_station`, tables, constraints, and seed data.

3. **Verify PHP can connect**

   Default credentials in [`php/config.php`](php/config.php) assume:

   - Host: `localhost`
   - User: `root`
   - Password: *(empty)*
   - Database: `stray_station`

   Adjust via [environment variables](#configuration) if your setup differs.

---

## Configuration

Optional environment variables (e.g. Apache `SetEnv`, nginx `fastcgi_param`, Docker, or a `.env` loader if you add one):

| Variable | Default | Description |
|----------|---------|-------------|
| `STRAY_DB_HOST` | `localhost` | MySQL host |
| `STRAY_DB_USER` | `root` | MySQL user |
| `STRAY_DB_PASS` | *(empty)* | MySQL password |
| `STRAY_DB_NAME` | `stray_station` | Database name |

Do **not** commit real production passwords. Use your host’s secret management or `.env` (and keep `.env` in `.gitignore` — already ignored in this repo).

---

## Running locally

### Option A — PHP built-in server (quickest)

From the **project root** (where `index.html` lives):

```bash
php -S localhost:8080
```

Open **http://localhost:8080/** in your browser.

### Option B — XAMPP / MAMP / WAMP

1. Point the virtual host **document root** to this project folder (the folder that contains `index.html` and the `php/` directory).
2. Ensure PHP `mysqli` is enabled.
3. Import `sql/stray_station.sql` via phpMyAdmin or the MySQL CLI.

### Option C — Apache / nginx

Configure the site root to this repository and ensure PHP executes `.php` files under `php/` and `php/api/`.

---

## Project structure

```
Pet-adoption-website/
├── index.html              # Landing page + signup form → php/signup.php
├── login.html              # Sign in → php/Login.php
├── register.html           # Register → php/signup.php
├── pets.html               # Browse pets (loads from php/api/pets_list.php)
├── adopt.html              # Adoption application → php/submit_form.php
├── dashboard.html          # Shelter CRUD (authenticated APIs)
├── css/
│   └── styles.css          # Theme + Bootstrap-based styles
├── js/
│   ├── auth-ui.js          # Nav state via php/session_info.php
│   ├── pets-page.js        # Pet grid + filters
│   ├── dashboard-page.js   # Dashboard table + modal CRUD
│   ├── adopt-page.js       # Success / error query params + pet hint
│   ├── login-page.js       # ?next= and ?error= handling
│   ├── register-page.js    # Error messages + password match
│   └── scripts.js          # Theme behaviors (Swiper, etc.)
├── php/
│   ├── config.php          # DB connection + env overrides
│   ├── validate.php        # Server-side validation helpers
│   ├── auth.php            # Session, CSRF, JSON auth guard
│   ├── http.php            # JSON response helper
│   ├── Login.php           # POST login
│   ├── signup.php          # POST registration
│   ├── signup_success.php  # Success page after signup
│   ├── submit_form.php     # POST adoption application
│   ├── session_info.php    # GET JSON session + CSRF
│   ├── logout.php          # End session
│   ├── db_connection.php   # Legacy mysqli include ($conn)
│   └── api/
│       ├── pets_list.php   # GET JSON — public pet list
│       ├── pet_create.php  # POST JSON — create pet (auth + CSRF)
│       ├── pet_update.php  # POST JSON — update pet (auth + CSRF)
│       └── pet_delete.php  # POST JSON — delete pet (auth + CSRF)
└── sql/
    └── stray_station.sql   # Full schema + seeds (import this)
```

---

## Pages overview

| Page | Role |
|------|------|
| `index.html` | Marketing content; home signup posts to `php/signup.php`. |
| `register.html` | Full registration form → `php/signup.php`. |
| `login.html` | Login → `php/Login.php`; optional hidden `next` for redirect after login. |
| `pets.html` | Lists pets from the API; links to `adopt.html?pet=<id>`. |
| `adopt.html` | Application form POST to `php/submit_form.php`; hidden `pet_id` when applying for a specific pet. |
| `dashboard.html` | CRUD UI for staff (requires login); uses `php/api/*` with session cookie + CSRF. |

---

## HTTP / JSON API

All API responses are JSON with `Content-Type: application/json`. Mutating endpoints expect **`credentials: 'same-origin'`** (cookies) and a **`csrf`** field in the JSON body (value from `session_info.php` when logged in).

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `php/session_info.php` | No | `{ loggedIn, email?, name?, csrf? }` |
| GET | `php/api/pets_list.php` | No | `{ ok, pets: [{ id, name, type, description, image }] }` |
| POST | `php/api/pet_create.php` | Yes + CSRF | Create pet (JSON body: name, type, description, image). |
| POST | `php/api/pet_update.php` | Yes + CSRF | Update pet (JSON includes `id`). |
| POST | `php/api/pet_delete.php` | Yes + CSRF | Delete pet (JSON includes `id`). |

Traditional form POST endpoints:

- `php/Login.php` — fields: `email`, `password`, optional `next`
- `php/signup.php` — fields: `name`, `email`, `phone`, `password`
- `php/submit_form.php` — adoption fields + optional `pet_id`

---

## Security notes

- Passwords are **never** stored in plain text.
- SQL uses **prepared statements** for authentication and parameterized inserts where implemented.
- Pet mutations require **session** + **CSRF** token.
- For production, deploy **HTTPS**, restrict database users, and keep PHP updated.

---

## Troubleshooting

| Issue | What to check |
|-------|----------------|
| Blank page / 500 on PHP | PHP error log; `mysqli` enabled; database exists. |
| “Database unavailable” | Import `sql/stray_station.sql`; credentials in `php/config.php` / env vars. |
| Login works but APIs return 401 | Same-origin URL (don’t mix `localhost` vs `127.0.0.1`); cookies enabled. |
| CSRF errors on dashboard | Sign in again so `session_info.php` returns a fresh `csrf`; use one tab/domain consistently. |
| `pets.html` shows warning | Site must be served through **PHP** (not `file://`); MySQL must contain seed `pets` rows. |

---

## Credits

- **The Stray Station** — university / portfolio project demonstrating relational database design, server-side validation, authentication, and CRUD operations.
- UI builds on a **Start Bootstrap–style** theme (`css/styles.css`).

---

## License

If no license file is present in the repository, default copyright applies. Add a `LICENSE` file (e.g. MIT) when you are ready to publish under explicit terms.
