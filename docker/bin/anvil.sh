#!/bin/sh
set -e

ROLE_OR_CMD="$1"
shift || true

# Ensure we're in the app directory
cd /var/www/html

# Basic .env sanity check
if [ ! -f .env ]; then
  echo "ERROR: .env file is missing."
  echo "Copy from .env.example or .env.default and set APP_KEY before running Anvil."
  exit 1
fi

# TODO: optional check that APP_KEY is set (non-empty)
APP_KEY_LINE="$(grep '^APP_KEY=' .env 2>/dev/null || true)"
if [ -z "$APP_KEY_LINE" ] || [ "$APP_KEY_LINE" = "APP_KEY=" ]; then
  echo "WARNING: APP_KEY is not set in .env. Laravel will complain about this."
fi

case "$ROLE_OR_CMD" in
  server)
    echo "[anvil] Starting in SERVER role"

    if [ "${ANVIL_RUN_MIGRATIONS}" = "true" ]; then
      echo "[anvil] Running migrations..."
      php artisan migrate --force || {
        echo "[anvil] Migrations failed"; exit 1;
      }
    fi

    # Scheduler: use schedule:work (Laravel 11+) or cron later if you prefer
    echo "[anvil] Starting scheduler (schedule:work) in background..."
    php artisan schedule:work &

    echo "[anvil] Starting web server (artisan serve) on port ${ANVIL_PORT:-8000}..."
    exec php artisan serve --host=0.0.0.0 --port="${ANVIL_PORT:-8000}"
    ;;

  worker)
    echo "[anvil] Starting in WORKER role"
    if php artisan list | grep -q "horizon"; then
      echo "[anvil] Horizon detected, running horizon..."
      exec php artisan horizon
    else
      echo "[anvil] Horizon not found, running queue:work..."
      exec php artisan queue:work --verbose --tries=3 --timeout=90
    fi
    ;;

  *)
    echo "[anvil] Passthrough to artisan: $ROLE_OR_CMD $*"
    exec php artisan "$ROLE_OR_CMD" "$@"
    ;;
esac