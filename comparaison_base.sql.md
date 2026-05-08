# Comparaison entre `Diet/base.sql` et `new_Base.sql`

## Résumé rapide
`new_Base.sql` est une version plus orientée application métier. Elle enrichit la table `objectif`, ajoute un champ métier à `sport`, réduit le nombre de régimes, et supprime les tables de suggestion calculées en base.

## Changements principaux

### 1. Table `objectif`
Dans `Diet/base.sql`, la table `objectif` est minimaliste :
- `id_utilisateur`
- `id_type_objectif`
- `valeur_poids`

Dans `new_Base.sql`, elle devient beaucoup plus complète :
- `poids_initial`
- `objectif_poids`
- `regime_id`
- `sport_id`
- `IMC_initial`
- `duree_objectif`
- `prix_total`
- `date_debut`

Conséquence : le nouveau schéma stocke davantage de données prêtes à l'emploi pour le backend.

### 2. Suppression des tables de suggestion
`Diet/base.sql` contient deux tables supplémentaires :
- `suggestion_programme`
- `suggestion_programme_detail`

Ces tables n'existent plus dans `new_Base.sql`.

Conséquence : la logique de suggestion n'est plus persistée en base, elle est censée être calculée côté application.

### 3. Table `sport`
Dans `Diet/base.sql`, `sport` contient seulement :
- `id`
- `nom`

Dans `new_Base.sql`, un champ est ajouté :
- `apport_poids`

Conséquence : chaque sport a maintenant un effet explicite sur la prise ou la perte de poids.

### 4. Tables `regime` et `regime_detail`
Les deux fichiers gardent les mêmes tables, mais les données changent fortement.

Dans `Diet/base.sql` :
- 5 régimes
- plusieurs lignes de `regime_detail` par régime avec des durées variées

Dans `new_Base.sql` :
- 10 régimes
- des durées et prix différents
- des variations de poids plus hétérogènes

Conséquence : le jeu de données a été complètement réorganisé.

### 5. Données initiales des utilisateurs
C'est un point important.

Dans `Diet/base.sql`, les utilisateurs ont des `id_role` différents :
- admin = 1
- Alice = 2
- Bob = 3
- Clara = 4
- David = 5
- Eva = 6

Dans `new_Base.sql`, tous les utilisateurs non-admin ont `id_role = 2`.

Problème : la colonne `id_role` est définie avec `UNIQUE` dans les deux fichiers, donc `new_Base.sql` risque de provoquer une erreur d'insertion.

### 6. Table `user_role`
`Diet/base.sql` insère 6 lignes dans `user_role` :
- 1 admin
- 5 utilisateurs

`new_Base.sql` n'insère que 2 lignes :
- admin
- utilisateur

Conséquence : le mapping des rôles a été simplifié, mais il faut alors adapter les valeurs `id_role` dans `utilisateur`.

### 7. Table `code_argent`
Le contenu a aussi changé.

Dans `Diet/base.sql`, plusieurs montants sont plus faibles et plus réguliers.
Dans `new_Base.sql`, certains codes ont des montants plus élevés, par exemple :
- `250.00`
- `100.00`

Conséquence : le système de recharge semble avoir été recalibré.

## Différences fonctionnelles
- `Diet/base.sql` stocke davantage la logique de suggestion dans la base.
- `new_Base.sql` déplace une partie de cette logique vers l'application.
- `new_Base.sql` transforme `objectif` en table centrale du processus métier.
- `new_Base.sql` semble plus proche d'un backend qui calcule les programmes à la volée.

## Point à corriger dans `new_Base.sql`
Le seed des utilisateurs n'est pas cohérent avec la contrainte `UNIQUE` sur `id_role`.

Il faut soit :
- supprimer `UNIQUE` sur `utilisateur.id_role`,
- soit attribuer un `id_role` différent à chaque utilisateur,
- soit garder un seul rôle utilisateur et ajuster la structure métier.

## Conclusion
`new_Base.sql` est une version plus simple côté base, mais plus riche côté métier. Elle retire la logique de suggestion stockée en base et la remplace par des champs calculables dans `objectif`. Le seul défaut bloquant visible est la cohérence des rôles dans les données d'exemple.
