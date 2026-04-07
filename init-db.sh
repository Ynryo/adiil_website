#!/bin/bash
# Init script for Docker MySQL - runs script.sql with DELIMITER support
# Read password from secret file
DB_PASS=$(cat /run/secrets/db_root_password)
mysql -u root -p"$DB_PASS" "$MYSQL_DATABASE" < /tmp/script.sql
