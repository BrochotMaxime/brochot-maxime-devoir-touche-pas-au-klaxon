#!/usr/bin/env bash

set -euo pipefail

PROJECT_DIR="$HOME/repository/brochot-maxime-devoir-touche-pas-au-klaxon"
RESET_FILE="$PROJECT_DIR/database/reset_demo_database.sql"
SEED_FILE="$PROJECT_DIR/database/seed_database.sql"

echo "$(date '+%Y-%m-%d %H:%M:%S') - Starting demo database reset."

if [[ ! -f "$RESET_FILE" ]]; then
  echo "Error: reset script not found at $RESET_FILE" >&2
  exit 1
fi

if [[ ! -f "$SEED_FILE" ]]; then
  echo "Error: seed script not found at $SEED_FILE" >&2
  exit 1
fi

mysql < "$RESET_FILE"
mysql < "$SEED_FILE"

echo "$(date '+%Y-%m-%d %H:%M:%S') - Demo database reset completed successfully."