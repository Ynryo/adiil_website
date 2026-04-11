# SAÉ4 - Serveur de l'ADIIL

## Mettre le serv en place

### En local

> http://localhost:8000/

commandes :
```
php -S 127.0.0.1:8000 -t C:/xampp/htdocs/
```

pour Thomas :
```
php -S 127.0.0.1:8000 -t C:/xampp/htdocs/Web/sae4
```

## La bdd

### .env
```
DB_NAME=
DB_HOST=
DB_USER=
DB_PASS=
```

### Setup la bdd

**Assurer que vous la bdd est bien créer dans phpmyadmin**

aller sur ce lien
> http://localhost:8000/createDB.php


## Logins des utilisateurs par défaut 

|             email              | mot de passe |
|--------------------------------|--------------|
| gemino.ruffault@example.com    | password1    |
| axelle.hannier@example.com     | password2    |
| julien.dauvergne@example.com   | password3    |
| baptiste.delahay@example.com   | password4    |
| nathalie.vieillard@example.com | password5    |
| barnabe.havard@example.com     | password6    |
| theo.fevrier@example.com       | password7    |
| tom.gouin@example.com          | password8    |
| evann.congnard@example.com     | password9    |
| erwan.lecoz@example.com        | password10   |

## Roles

### Attribution 

| id_membre |        nom         |  prenom  |      role       |
|-----------|--------------------|--------- |-----------------|
|     1     | RUFFAULT--RAVENEL  | Gemino   | Administrateur  |
|     2     | HANNIER            | Axelle   | Membre          |
|     3     | DAUVERGNE          | Julien   | Bureau          |
|     4     | DELAYE             | Baptiste | Modérateur      |
|     5     | VIEILLARD          | Nathalie | Responsable com |
|     6     | HAVARD             | Barnabe  | Bureau          |
|     7     | FEVRIER            | Theo     | Membre          |
|     8     | GOUIN              | Tom      | Membre          |
|     9     | CONGNARD           | Evann    | Modérateur      |
|    10     | LE COZ             | Erwan    | Membre          |

### Permissions

| nom_role        | p_log | p_boutique | p_reunion | p_utilisateur | p_grade | p_roles | p_actualite | p_evenements | p_comptabilite | p_achats | p_moderation |
|-----------------|-------|------------|-----------|---------------|---------|---------|-------------|--------------|----------------|----------|--------------|
| Administrateur  | 1     | 1          | 1         | 1             | 1       | 1       | 1           | 1            | 1              | 1        | 1            |
| Bureau          | 1     | 1          | 1         | 0             | 0       | 0       | 1           | 1            | 1              | 1        | 0            |
| Responsable com | 0     | 0          | 0         | 0             | 0       | 0       | 0           | 0            | 1              | 1        | 0            |
| Modérateur      | 0     | 0          | 0         | 1             | 0       | 0       | 1           | 0            | 0              | 0        | 1            |
| Membre          | 0     | 0          | 0         | 0             | 0       | 0       | 0           | 0            | 0              | 0        | 0            |
