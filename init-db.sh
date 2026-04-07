#!/bin/bash
# Init script for Docker MySQL - runs script.sql with DELIMITER support
mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" < /tmp/script.sql
