Lancer pour la première fois :
```bash
git clone https://github.com/Ynryo/adiil_website.git
```

Créer le dossier `secrets` et y mettre le fichier `db_root_password.txt` avec le mot de passe root de la base de données.

```bash
docker compose build --no-cache
docker compose up -d
```

Accéder au site :
```bash
http://localhost:8080
```

Pour arrêter les conteneurs :
```bash
docker compose down
```

Lancer phpmyadmin :
```bash
docker compose --profile pma up -d
```

Accéder à phpmyadmin :
```bash
http://localhost:8081
```

Pour arrêter phpmyadmin :
```bash
docker compose --profile pma down
```