# **Anvil**

> **Package and run simple Laravel apps—cleanly, predictably, and without server-side build tools.**

Anvil is a small system that helps you **package a Laravel application into a deployable ZIP artifact**, and then **run that artifact** in a lightweight Docker runtime.

It’s designed for homelabs, Unraid users, hobby projects, and anyone who wants a **Forge-like deploy workflow**—without needing Composer, Node, or build tooling on the server.

---

## **Why Anvil?**

Laravel has an excellent local development experience, but deploying simple apps often requires:

- PHP + Composer installed on the server  
- Node/Vite for asset compilation  
- Managing versions of PHP extensions  
- Build steps during deploy  
- App-specific Dockerfile maintenance  

**Anvil eliminates all of that.**

With Anvil:

### 🎁 Build an app artifact (locally or in CI)

```bash
php artisan anvil:build
```

This produces a ZIP containing:

- Your Laravel app source  
- `vendor/` dependencies  
- Built frontend assets  
- Cached config/routes (optional)  

Ready to deploy anywhere.

### 🚀 Run the artifact using the Anvil runtime image

Anvil includes a minimal Docker image that:

- mounts your packaged app  
- runs migrations  
- runs `artisan serve`  
- can run queue workers (including Horizon)  
- can run scheduled tasks  

You get a simple, predictable, standalone environment.

If you want a bit more power, Anvil also works seamlessly with external services like **MySQL/PostgreSQL** or **Redis**.  
Just point your `.env` values (e.g., `DB_HOST`, `DB_DATABASE`, `REDIS_HOST`) at the services you’re running.  
No special flags, no proprietary configuration layers, and no container magic glue required—Anvil always respects your application's own `.env` file.

---

# **How It Works**

Anvil is actually two pieces:

## **1. The Laravel Package (this repo)**

When installed in a Laravel app:

```bash
composer require --dev indemnity83/anvil
```

…it provides the command:

```bash
php artisan anvil:build
```

That command:

- runs `composer install` (prod deps)
- runs your JS build (`npm ci && npm run build`)
- warms caches
- stages the app
- creates a ZIP at:

```
storage/app/anvil/anvil-package.zip
```

This artifact contains everything needed to run your app on any PHP host—including Anvil.

---

## **2. The Anvil Runtime (docker/ directory)**

Inside `docker/` you’ll find:

- `Dockerfile` – small PHP-Alpine runtime  
- `bin/entrypoint.sh` – handles UID/GID, roles  
- `bin/anvil.sh` – artisan shim and server/worker logic  

The runtime supports three modes:

### **Server**

```bash
docker run anvil:php-8.3 server
```

- runs migrations (optional)
- runs scheduler
- runs the built-in Laravel server

### **Worker**

```bash
docker run anvil:php-8.3 worker
```

- runs `queue:work` or `horizon` if installed  
- integrates with `queue:restart` / `horizon:terminate` for zero-downtime updates

### **Passthrough (artisan)**

```bash
docker run anvil:php-8.3 migrate --force
```

Any other command is forwarded to `php artisan ...`. This passthrough mode isn’t something you’ll typically need during normal deployments, but it’s available for special cases—for example, when your host system doesn’t have PHP installed (very common on Unraid and other homelab setups) or when you need to run an occasional maintenance or diagnostic artisan command directly against your app.

---

# **Deploy Workflow**

A typical homelab/Unraid deployment looks like:

### **Step 1 – Build the artifact locally**

```bash
php artisan anvil:build
```

You can run this command directly on your development machine, or you can automate it using CI/CD tools such as GitHub Actions, GitLab CI, or any other workflow runner. Anvil is designed so that artifact creation can happen anywhere that can run PHP, Composer, and Node.

### **Step 2 – Copy the ZIP to your server**

(Use whatever: SMB share, SFTP, Unraid file copy, etc.)

### **Step 3 – Unzip into a local directory**

Example:

```
/mnt/user/appdata/myapp
```

### **Step 4 - Start the Docker server container and point it to your local directory

```bash
docker run -d \
  --name myapp-server \
  -p 8000:8000 \
  -v /mnt/user/appdata/myapp:/var/www/html \
  -e PUID=99 \
  -e PGID=100 \
  indemnity83/anvil:php-8.3 server
```

That’s it.  
No Node. No Composer. No build tooling on the server—ever.

---

# **Directory Structure**

```
anvil/
  composer.json              # Laravel package definition
  src/                       # Anvil build command + helpers
  config/                    # publishable config
  docker/
    Dockerfile               # Anvil runtime environment
    bin/
      entrypoint.sh
      anvil.sh
  .gitattributes             # excludes docker/ from Composer dist
  .gitignore
  README.md
```

---

# **Configuration**

After installing in your app:

```bash
php artisan vendor:publish --tag=anvil-config
```

You’ll get:

```
config/anvil.php
```

Where you can customize:

- build output location  
- excluded directories  
- NPM binary and script name  
- whether to warm caches  
- composer flags  

---

# **Roadmap**

### ✔️ Initial package build  
### ✔️ Docker runtime  
### ✔️ Worker/server role split  
### ⬜ `anvil:deploy` (end-to-end build + release + restart)  
### ⬜ GitHub Actions template for automatic artifact builds  
### ⬜ Optional “build image” with Composer + Node baked in  
### ⬜ First public release  
### ⬜ Anvil homepage / docs  

---

# **License**

MIT © 2025 Kyle Klaus