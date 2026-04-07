#!/bin/bash
# Init script - executes SQL files in the correct order
DB_PASS=$(cat /run/secrets/db_root_password)

echo "==> Running creation.sql..."
mysql -u root -p"$DB_PASS" "$MYSQL_DATABASE" < /tmp/sql/creation.sql

echo "==> Running insertion.sql..."
mysql -u root -p"$DB_PASS" "$MYSQL_DATABASE" < /tmp/sql/insertion.sql

echo "==> Running admin.sql..."
mysql -u root -p"$DB_PASS" "$MYSQL_DATABASE" < /tmp/sql/admin.sql

echo "==> Running membre.sql..."
mysql -u root -p"$DB_PASS" "$MYSQL_DATABASE" < /tmp/sql/membre.sql

echo "==> Running visiteur.sql..."
mysql -u root -p"$DB_PASS" "$MYSQL_DATABASE" < /tmp/sql/visiteur.sql

echo "==> Database initialization complete!"
