/****************************************************
 * Objets SQL nécessaires (visiteur + création compte,
 * vues et modifications de schéma) - sans tests ni inserts
 ****************************************************/

-- Vue : événements avec places disponibles
DROP VIEW IF EXISTS VUE_EVENTS;
CREATE VIEW VUE_EVENTS AS
SELECT
    e.id_evenement,
    e.nom_evenement,
    (e.places_evenement - (
        SELECT COUNT(*) FROM INSCRIPTION i WHERE i.id_evenement = e.id_evenement
    )) AS places_disponibles,
    e.xp_evenement,
    e.reductions_evenement,
    e.places_evenement,
    e.prix_evenement,
    e.lieu_evenement,
    e.date_evenement
FROM EVENEMENT e;

-- Vue : articles disponibles
DROP VIEW IF EXISTS VUE_ARTICLES_DISPONIBLES;
CREATE VIEW VUE_ARTICLES_DISPONIBLES AS
SELECT
    id_article,
    nom_article,
    xp_article,
    reduction_article,
    prix_article,
    stock_article
FROM ARTICLE
WHERE COALESCE(stock_article,0) > 0;

-- Procédure : création de compte (silencieuse si email existe)
DROP PROCEDURE IF EXISTS creationCompte;
CREATE PROCEDURE creationCompte(
    IN _name_user VARCHAR(100),
    IN _firstName_user VARCHAR(100),
    IN _email_user VARCHAR(100),
    IN _password_user VARCHAR(100),
    IN _pp_user VARCHAR(500)
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM MEMBRE WHERE email_membre = _email_user) THEN
        INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, password_membre, pp_membre)
        VALUES (_name_user, _firstName_user, _email_user, _password_user, _pp_user);
    END IF;
END;

-- Modifications de schéma : colonnes "deleted" si inexistantes
ALTER TABLE EVENEMENT ADD COLUMN IF NOT EXISTS deleted BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE GRADE     ADD COLUMN IF NOT EXISTS deleted BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE ARTICLE   ADD COLUMN IF NOT EXISTS deleted BOOLEAN NOT NULL DEFAULT FALSE;

-- Vue : LISTE_PERMISSIONS (alias sans espaces pour usage applicatif)
DROP VIEW IF EXISTS LISTE_PERMISSIONS;
CREATE VIEW LISTE_PERMISSIONS AS
SELECT 
    m.id_membre,
    MAX(COALESCE(r.p_log_role, 0))          AS p_log,
    MAX(COALESCE(r.p_boutique_role, 0))     AS p_boutique,
    MAX(COALESCE(r.p_reunion_role, 0))      AS p_reunion,
    MAX(COALESCE(r.p_utilisateur_role, 0))  AS p_utilisateur,
    MAX(COALESCE(r.p_grade_role, 0))        AS p_grade,
    MAX(COALESCE(r.p_roles_role, 0))        AS p_role,
    MAX(COALESCE(r.p_actualite_role, 0))    AS p_actualite,
    MAX(COALESCE(r.p_evenements_role, 0))   AS p_evenement,
    MAX(COALESCE(r.p_comptabilite_role, 0)) AS p_comptabilite,
    MAX(COALESCE(r.p_achats_role, 0))       AS p_achat,
    MAX(COALESCE(r.p_moderation_role, 0))   AS p_moderation
FROM MEMBRE m
LEFT JOIN ASSIGNATION a ON m.id_membre = a.id_membre
LEFT JOIN ROLE r       ON a.id_role = r.id_role
GROUP BY m.id_membre;