/***************************************************/
/* VUE : Historique des achats */
/***************************************************/
DROP VIEW IF EXISTS HISTORIQUE;

CREATE VIEW HISTORIQUE AS
SELECT
    'Commande' AS type_transaction,
    ARTICLE.nom_article AS element,
    COMMANDE.qte_commande AS quantite,
    MEMBRE.id_membre AS id_utilisateur,
    COMMANDE.statut_commande AS recupere,
    COMMANDE.date_commande AS date_transaction,
    COMMANDE.paiement_commande AS mode_paiement,
    COMMANDE.prix_commande AS montant
FROM COMMANDE
INNER JOIN ARTICLE ON ARTICLE.id_article = COMMANDE.id_article
INNER JOIN MEMBRE ON MEMBRE.id_membre = COMMANDE.id_membre

UNION ALL

SELECT
    'Inscription' AS type_transaction,
    EVENEMENT.nom_evenement AS element,
    1 AS quantite,
    MEMBRE.id_membre AS id_utilisateur,
    1 AS recupere,
    INSCRIPTION.date_inscription AS date_transaction,
    INSCRIPTION.paiement_inscription AS mode_paiement,
    INSCRIPTION.prix_inscription AS montant
FROM INSCRIPTION
INNER JOIN EVENEMENT ON EVENEMENT.id_evenement = INSCRIPTION.id_evenement
INNER JOIN MEMBRE ON MEMBRE.id_membre = INSCRIPTION.id_membre

UNION ALL

SELECT
    'Adhesion' AS type_transaction,
    GRADE.nom_grade AS element,
    1 AS quantite,
    MEMBRE.id_membre AS id_utilisateur,
    1 AS recupere,
    ADHESION.date_adhesion AS date_transaction,
    ADHESION.paiement_adhesion AS mode_paiement,
    ADHESION.prix_adhesion AS montant
FROM ADHESION
INNER JOIN GRADE ON GRADE.id_grade = ADHESION.id_grade
INNER JOIN MEMBRE ON MEMBRE.id_membre = ADHESION.id_membre;

/***************************************************/
/* VUE : Liste des permissions par membre */
/***************************************************/
DROP VIEW IF EXISTS LISTE_PERMISSIONS;

CREATE VIEW LISTE_PERMISSIONS AS
SELECT 
    MEMBRE.id_membre,
    MAX(COALESCE(p_log_role, 0))          AS acces_logs,
    MAX(COALESCE(p_boutique_role, 0))     AS gestion_boutique,
    MAX(COALESCE(p_reunion_role, 0))      AS gestion_reunions,
    MAX(COALESCE(p_utilisateur_role, 0))  AS gestion_utilisateurs,
    MAX(COALESCE(p_grade_role, 0))        AS gestion_grades,
    MAX(COALESCE(p_roles_role, 0))        AS gestion_roles,
    MAX(COALESCE(p_actualite_role, 0))    AS gestion_actualites,
    MAX(COALESCE(p_evenements_role, 0))   AS gestion_evenements,
    MAX(COALESCE(p_comptabilite_role, 0)) AS gestion_comptabilite,
    MAX(COALESCE(p_achats_role, 0))       AS acces_achats,
    MAX(COALESCE(p_moderation_role, 0))   AS moderation
FROM MEMBRE
LEFT JOIN ASSIGNATION ON MEMBRE.id_membre = ASSIGNATION.id_membre
LEFT JOIN ROLE ON ASSIGNATION.id_role = ROLE.id_role
GROUP BY MEMBRE.id_membre;

/***************************************************/
/* PROCEDURE : Annuler une commande */
/***************************************************/
DROP PROCEDURE IF EXISTS refund_transaction;

DELIMITER $$
CREATE PROCEDURE refund_transaction(IN _id_commande INT)
BEGIN
    DECLARE _id_article INT;
    DECLARE _id_membre INT;
    DECLARE _xp_article INT;
    DECLARE _qtty_bought INT;

    SET _id_membre = (SELECT id_membre FROM COMMANDE WHERE id_commande = _id_commande);
    SET _id_article = (SELECT id_article FROM COMMANDE WHERE id_commande = _id_commande);
    SET _qtty_bought = (SELECT qte_commande FROM COMMANDE WHERE id_commande = _id_commande);
    SET _xp_article = (SELECT xp_article FROM ARTICLE WHERE id_article = _id_article);

    UPDATE MEMBRE 
        SET xp_membre = xp_membre - _xp_article * _qtty_bought 
        WHERE id_membre = _id_membre;

    UPDATE ARTICLE 
        SET stock_article = stock_article + _qtty_bought 
        WHERE id_article = _id_article;

    DELETE FROM COMMANDE WHERE id_commande = _id_commande;
END $$
DELIMITER ;

/***************************************************/
/* PROCEDURE : Supprimer un evenement et sa galerie */
/***************************************************/
DROP PROCEDURE IF EXISTS delete_event;

DELIMITER $$
CREATE PROCEDURE delete_event(IN _id_event INT)
BEGIN
    DELETE FROM MEDIA WHERE id_evenement = _id_event;
    DELETE FROM EVENEMENT WHERE id_evenement = _id_event;
END $$
DELIMITER ;

/***************************************************/
/* TRIGGER : Vérifier permissions pour créer actualité */
/***************************************************/
DROP TRIGGER IF EXISTS permissions_create_event;

DELIMITER $$
CREATE TRIGGER permissions_create_event 
AFTER INSERT ON ACTUALITE
FOR EACH ROW
BEGIN
    DECLARE _user_id INT;
    DECLARE _has_perms INT;

    SET _user_id = NEW.id_membre;
    SET _has_perms = (SELECT gestion_actualites FROM LISTE_PERMISSIONS WHERE id_membre = _user_id);

    IF (_has_perms = 0) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Vous n''avez pas les permissions pour ajouter une actualite';
    END IF;
END $$
DELIMITER ;