DROP TABLE IF EXISTS ASSIGNATION;
DROP TABLE IF EXISTS INSCRIPTION;
DROP TABLE IF EXISTS MEDIA;
DROP TABLE IF EXISTS REUNION;
DROP TABLE IF EXISTS ACTUALITE;
DROP TABLE IF EXISTS COMPTABILITE;
DROP TABLE IF EXISTS ADHESION;
DROP TABLE IF EXISTS GRADE;
DROP TABLE IF EXISTS COMMANDE;
DROP TABLE IF EXISTS ARTICLE;
DROP TABLE IF EXISTS EVENEMENT;
DROP TABLE IF EXISTS ROLE;
DROP TABLE IF EXISTS MEMBRE;

CREATE TABLE MEMBRE(
    id_membre INT AUTO_INCREMENT,
    nom_membre VARCHAR(100) NOT NULL,
    prenom_membre VARCHAR(100) NOT NULL,
    email_membre VARCHAR(100) NOT NULL,
    password_membre VARCHAR(100) NOT NULL,
    xp_membre INT NOT NULL DEFAULT 0,
    discord_token_membre VARCHAR(500),
    pp_membre VARCHAR(500) NOT NULL,
    tp_membre VARCHAR(3),
    PRIMARY KEY(id_membre)
);

CREATE TABLE ROLE(
    id_role INT AUTO_INCREMENT,
    nom_role VARCHAR(100) NOT NULL,
    p_log_role BIT NOT NULL,
    p_boutique_role BIT NOT NULL,
    p_reunion_role BIT NOT NULL,
    p_utilisateur_role BIT NOT NULL,
    p_grade_role BIT NOT NULL,
    p_roles_role BIT NOT NULL,
    p_actualite_role BIT NOT NULL,
    p_evenements_role BIT NOT NULL,
    p_comptabilite_role BIT NOT NULL,
    p_achats_role BIT NOT NULL,
    p_moderation_role BIT NOT NULL,
    PRIMARY KEY(id_role)
);

CREATE TABLE ARTICLE(
    id_article INT AUTO_INCREMENT,
    xp_article INT NOT NULL DEFAULT 1,
    nom_article VARCHAR(100) NOT NULL,
    stock_article INT NOT NULL,
    image_article VARCHAR(500) NOT NULL,
    reduction_article BIT NOT NULL DEFAULT 1,
    prix_article FLOAT NOT NULL CHECK (prix_article >= 0),
    PRIMARY KEY(id_article)
);

CREATE TABLE COMMANDE(
    id_commande INT AUTO_INCREMENT,
    statut_commande BIT NOT NULL,
    prix_commande FLOAT NOT NULL CHECK (prix_commande >= 0),
    paiement_commande VARCHAR(50) NOT NULL,
    date_commande DATETIME NOT NULL,
    qte_commande INT NOT NULL,
    id_membre INT NOT NULL,
    id_article INT NOT NULL,
    PRIMARY KEY(id_commande),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY(id_article) REFERENCES ARTICLE(id_article)
);

CREATE TABLE EVENEMENT(
    id_evenement INT AUTO_INCREMENT,
    nom_evenement VARCHAR(100) NOT NULL,
    xp_evenement INT NOT NULL DEFAULT 10,
    places_evenement INT NOT NULL,
    prix_evenement INT NOT NULL,
    reductions_evenement BIT NOT NULL DEFAULT 1,
    lieu_evenement VARCHAR(50) NOT NULL,
    date_evenement DATETIME NOT NULL,
    image_evenement VARCHAR(255),
    description_evenement TEXT,
    PRIMARY KEY(id_evenement)
);

CREATE TABLE COMPTABILITE(
    id_comptabilite INT AUTO_INCREMENT,
    date_comptabilite DATETIME NOT NULL,
    nom_comptabilite VARCHAR(100) NOT NULL,
    url_comptabilite VARCHAR(500) NOT NULL,
    id_membre INT NOT NULL,
    PRIMARY KEY(id_comptabilite),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre)
);

CREATE TABLE GRADE(
    id_grade INT AUTO_INCREMENT,
    reduction_grade INT NOT NULL,
    image_grade VARCHAR(500) NOT NULL,
    prix_grade INT NOT NULL CHECK (prix_grade >= 0),
    description_grade VARCHAR(500),
    nom_grade VARCHAR(100) NOT NULL,
    PRIMARY KEY(id_grade)
);

CREATE TABLE ADHESION(
    id_adhesion INT AUTO_INCREMENT,
    date_adhesion DATETIME NOT NULL,
    prix_adhesion INT NOT NULL,
    paiement_adhesion VARCHAR(50) NOT NULL,
    id_membre INT NOT NULL,
    id_grade INT NOT NULL,
    PRIMARY KEY(id_adhesion),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY(id_grade) REFERENCES GRADE(id_grade)
);

CREATE TABLE MEDIA(
    id_media INT AUTO_INCREMENT,
    url_media VARCHAR(500) NOT NULL,
    date_media DATETIME NOT NULL,
    id_membre INT NOT NULL,
    id_evenement INT NOT NULL,
    PRIMARY KEY(id_media),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY(id_evenement) REFERENCES EVENEMENT(id_evenement)
);

CREATE TABLE REUNION(
    id_reunion INT AUTO_INCREMENT,
    date_reunion DATETIME NOT NULL,
    fichier_reunion VARCHAR(300),
    id_membre INT NOT NULL,
    PRIMARY KEY(id_reunion),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre)
);

CREATE TABLE ACTUALITE(
    id_actualite INT AUTO_INCREMENT,
    image_actualite VARCHAR(300),
    titre_actualite VARCHAR(100) NOT NULL,
    contenu_actualite VARCHAR(1000),
    date_actualite DATETIME NOT NULL,
    id_membre INT NOT NULL,
    PRIMARY KEY(id_actualite),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre)
);

CREATE TABLE ASSIGNATION(
    id_membre INT,
    id_role INT,
    PRIMARY KEY(id_membre, id_role),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY(id_role) REFERENCES ROLE(id_role)
);

CREATE TABLE INSCRIPTION(
    id_membre INT,
    id_evenement INT,
    date_inscription DATETIME NOT NULL,
    paiement_inscription VARCHAR(50) NOT NULL,
    prix_inscription DECIMAL(15,2) NOT NULL,
    PRIMARY KEY(id_membre, id_evenement),
    FOREIGN KEY(id_membre) REFERENCES MEMBRE(id_membre),
    FOREIGN KEY(id_evenement) REFERENCES EVENEMENT(id_evenement)
);