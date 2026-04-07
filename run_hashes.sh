#!/bin/bash
export DB_PASS=$(cat /run/secrets/db_root_password)
mysql -u root -p"$DB_PASS" adiil < /tmp/hash_updates.sql
