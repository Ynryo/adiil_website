#!/bin/bash
# Read the DB password from Docker secret and export it
export DB_PASS=$(cat /run/secrets/db_root_password)

# Start Apache in foreground
exec apache2-foreground
