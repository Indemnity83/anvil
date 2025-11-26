#!/bin/sh
set -e

ROLE_OR_CMD="$1"
shift || true

# Set timezone (if valid)
if [ -n "$TZ" ]; then
  if [ -e "/usr/share/zoneinfo/$TZ" ]; then
    echo "[anvil] Setting timezone to $TZ"
    ln -snf "/usr/share/zoneinfo/$TZ" /etc/localtime
    echo "$TZ" > /etc/timezone
  else
    echo "[anvil] WARNING: TZ '$TZ' is not a valid timezone; ignoring."
  fi
fi

# Configure user/group according to PUID/PGID
PUID=${PUID:-1000}
PGID=${PGID:-1000}

echo "[anvil] Using PUID=${PUID} PGID=${PGID}"

# Ensure permissions on app directory (numeric IDs are fine even if no named user/group exists)
chown -R "${PUID}:${PGID}" /var/www/html || true

# Drop privileges & re-exec as the requested UID/GID
exec su-exec "${PUID}:${PGID}" /usr/local/bin/anvil.sh "$ROLE_OR_CMD" "$@"