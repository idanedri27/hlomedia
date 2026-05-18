#!/bin/bash
# ─────────────────────────────────────────────────────────────────────
# /usr/local/bin/run-morning-agent.sh
#
# Wrapper that runs the existing Node-based morning-email-agent.
# Called via sudo from the `api` PHP-FPM user through the
# run-morning-agent.php endpoint, which is in turn triggered by n8n.
#
# We DO NOT touch /var/www/morning-email-agent/ — the Node project
# stays exactly as it is (keeping its 9.6KB of accumulated sender
# scores, its SQLite tracking, its Telegram voice integration).
#
# Usage:
#   sudo /usr/local/bin/run-morning-agent.sh morning   # 09:00 daily brief
#   sudo /usr/local/bin/run-morning-agent.sh evening   # 17:00 end-of-day scan
#
# Install:
#   - chmod 755 /usr/local/bin/run-morning-agent.sh
#   - chown root:root /usr/local/bin/run-morning-agent.sh
#   - /etc/sudoers.d/morning-agent must allow api user to sudo this.
# ─────────────────────────────────────────────────────────────────────

set -e

SCRIPT="${1:-morning}"
case "$SCRIPT" in
    morning)
        NODE_FILE="index.js"
        ;;
    evening)
        NODE_FILE="services/endOfDayScan.js"
        ;;
    *)
        echo "Usage: $0 [morning|evening]" >&2
        exit 1
        ;;
esac

cd /var/www/morning-email-agent
export NVM_DIR=/root/.nvm
# shellcheck source=/dev/null
. "$NVM_DIR/nvm.sh"
nvm use 20 > /dev/null

# Run the Node script. Output (stdout+stderr) is captured by the caller.
node "$NODE_FILE"
