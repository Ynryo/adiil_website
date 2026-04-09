/****************************************************
 * Objets SQL nécessaires (procédures, trigger, vues,
 * et modifications de schéma) - sans tests ni inserts
 ****************************************************/

-- Procédure : achat_article
DROP PROCEDURE IF EXISTS achat_article;
DELIMITER $$
CREATE PROCEDURE achat_article(
    IN _id_membre_acheteur INT,
    IN _id_article_achat INT,
    IN _quantite INT,
    IN _mode_paiement VARCHAR(50)
)
BEGIN
    DECLARE _prix_art INT;
    DECLARE _reduc_grade FLOAT;
    DECLARE _xp_gagne INT;
    DECLARE _is_reductible BOOL;

    SET _xp_gagne = (SELECT xp_article FROM ARTICLE WHERE id_article = _id_article_achat);
    SET _prix_art = (SELECT prix_article FROM ARTICLE WHERE id_article = _id_article_achat);
    SET _is_reductible = (SELECT reduction_article FROM ARTICLE WHERE id_article = _id_article_achat);

    SET _reduc_grade = (
        SELECT GRADE.reduction_grade 
        FROM GRADE
        JOIN ADHESION ON ADHESION.id_grade = GRADE.id_grade
        WHERE ADHESION.date_adhesion = (
            SELECT MAX(date_adhesion) 
            FROM ADHESION 
            WHERE id_membre = _id_membre_acheteur
        )
        AND ADHESION.id_membre = _id_membre_acheteur
        LIMIT 1
    );

    IF (_reduc_grade IS NULL OR _reduc_grade = 0 OR _is_reductible = 0) THEN
        SET _reduc_grade = 1;
    ELSE
        SET _reduc_grade = 1 - _reduc_grade / 100;
    END IF;

    INSERT INTO COMMANDE (
        statut_commande, prix_commande, paiement_commande, date_commande, qte_commande, id_membre, id_article
    ) VALUES (
        0, (_prix_art * _reduc_grade) * _quantite, _mode_paiement, NOW(), _quantite, _id_membre_acheteur, _id_article_achat
    );

    UPDATE MEMBRE 
    SET xp_membre = COALESCE(xp_membre,0) + (_quantite * COALESCE(_xp_gagne,0))
    WHERE id_membre = _id_membre_acheteur;

    UPDATE ARTICLE 
    SET stock_article = COALESCE(stock_article,0) - _quantite
    WHERE id_article = _id_article_achat;
END $$
DELIMITER ;

-- Procédure : suppressionCompte
DROP PROCEDURE IF EXISTS suppressionCompte;
DELIMITER $$
CREATE PROCEDURE suppressionCompte(IN _id_utilisateur_supprime INT)
BEGIN
    UPDATE MEMBRE 
    SET nom_membre = 'N/A',
        prenom_membre = 'N/A',
        email_membre = 'N/A',
        password_membre = 'N/A',
        xp_membre = 0,
        discord_token_membre = 'N/A',
        pp_membre = 'N/A'
    WHERE id_membre = _id_utilisateur_supprime;

    DELETE FROM MEDIA WHERE id_membre = _id_utilisateur_supprime;
    DELETE FROM ASSIGNATION WHERE id_membre = _id_utilisateur_supprime;
END $$
DELIMITER ;

-- Procédure : creationCompte
DROP PROCEDURE IF EXISTS creationCompte;
DELIMITER $$
CREATE PROCEDURE creationCompte(
    IN _name_user VARCHAR(100),
    IN _firstName_user VARCHAR(100),
    IN _email_user VARCHAR(100),
    IN _password_user VARCHAR(100)
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM MEMBRE WHERE email_membre = _email_user) THEN
        INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, password_membre) 
        VALUES (_name_user, _firstName_user, _email_user, _password_user);
    END IF;
END $$
DELIMITER ;

-- Trigger : verifier places restantes sur inscription
DROP TRIGGER IF EXISTS verif_places_event;
DELIMITER $$
CREATE TRIGGER verif_places_event
AFTER INSERT ON INSCRIPTION
FOR EACH ROW
BEGIN
    DECLARE _id_evenement_inscription INT;
    DECLARE _places_restantes INT;

    SET _id_evenement_inscription = NEW.id_evenement;

    SET _places_restantes = (
        SELECT EVENEMENT.places_evenement - COUNT(*)
        FROM EVENEMENT
        JOIN INSCRIPTION ON INSCRIPTION.id_evenement = EVENEMENT.id_evenement
        WHERE EVENEMENT.id_evenement = _id_evenement_inscription
        GROUP BY EVENEMENT.id_evenement, EVENEMENT.places_evenement
    );

    IF _places_restantes IS NULL OR _places_restantes <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il n''y a plus de places disponibles pour cet evenement';
    END IF;
END $$
DELIMITER ;

-- Modifications de schéma (colonnes "deleted")
ALTER TABLE EVENEMENT ADD COLUMN deleted BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE GRADE ADD COLUMN deleted BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE ARTICLE ADD COLUMN deleted BOOLEAN NOT NULL DEFAULT FALSE;

-- Vue : LISTE_PERMISSIONS (simplifiée, alias sans espaces pour usage en application)
DROP VIEW IF EXISTS LISTE_PERMISSIONS;
CREATE VIEW LISTE_PERMISSIONS AS
SELECT 
    MEMBRE.id_membre,
    MAX(COALESCE(p_log_role, 0))          AS p_log,
    MAX(COALESCE(p_boutique_role, 0))     AS p_boutique,
    MAX(COALESCE(p_reunion_role, 0))      AS p_reunion,
    MAX(COALESCE(p_utilisateur_role, 0))  AS p_utilisateur,
    MAX(COALESCE(p_grade_role, 0))        AS p_grade,
    MAX(COALESCE(p_roles_role, 0))        AS p_role,
    MAX(COALESCE(p_actualite_role, 0))    AS p_actualite,
    MAX(COALESCE(p_evenements_role, 0))   AS p_evenement,
    MAX(COALESCE(p_comptabilite_role, 0)) AS p_comptabilite,
    MAX(COALESCE(p_achats_role, 0))       AS p_achat,
    MAX(COALESCE(p_moderation_role, 0))   AS p_moderation
FROM MEMBRE
LEFT JOIN ASSIGNATION ON MEMBRE.id_membre = ASSIGNATION.id_membre
LEFT JOIN ROLE ON ASSIGNATION.id_role = ROLE.id_role
GROUP BY MEMBRE.id_membre;