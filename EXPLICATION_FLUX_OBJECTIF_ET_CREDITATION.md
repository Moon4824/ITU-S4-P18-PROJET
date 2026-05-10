# Explication du flux objectif et de la suite portefeuille/créditation

Ce document résume ce qui a déjà été intégré dans le projet pour le choix d’objectif, la suggestion de régime, et l’endroit où il faut brancher la partie portefeuille/créditation avec code.

## 1. Ce qui a déjà été fait

### 1.1 Redirection quand l’utilisateur n’a pas encore d’objectif

Le contrôleur `UserController` a été modifié pour vérifier si l’utilisateur connecté a déjà un objectif enregistré.

Si aucun objectif n’existe, il ne reste pas sur le dashboard: il est redirigé vers `/objectifs/choose`.

Fichier concerné: [app/Controllers/UserController.php](app/Controllers/UserController.php)

### 1.2 Flux successif dans `ObjectifController`

Le contrôleur `ObjectifController` gère maintenant le parcours en deux étapes:

1. affichage du type d’objectif, du poids objectif, et de la date de début;
2. affichage des régimes possibles triés par durée calculée;
3. enregistrement final dans la base quand l’utilisateur valide un régime.

Fichier concerné: [app/Controllers/ObjectifController.php](app/Controllers/ObjectifController.php)

### 1.3 Calcul des suggestions de régime

La logique des suggestions a été corrigée dans `RegimeDetailModel`.

Ce qui change:

- calcul de `duree_totale_calculee`;
- calcul de `prix_total_calcule`;
- tri des résultats par durée croissante;
- filtrage selon le sens de variation du poids.

Fichier concerné: [app/Models/RegimeDetailModel.php](app/Models/RegimeDetailModel.php)

### 1.4 Persistance de l’objectif

Une nouvelle couche de persistance a été ajoutée pour la table `objectif`.

Elle sert à:

- retrouver le dernier objectif d’un utilisateur;
- enregistrer l’objectif final avec le régime choisi;
- relier l’objectif au type d’objectif, au régime, au poids initial, au poids cible, à la durée et au prix.

Fichier concerné: [app/Models/ObjectifModel.php](app/Models/ObjectifModel.php)

### 1.5 Vue successive

La vue `objectifs/index` affiche maintenant:

- les informations santé de base;
- l’étape 1 du choix objectif;
- l’auto-remplissage du poids objectif si l’objectif est “IMC idéal”;
- l’étape 2 avec la liste des régimes compatibles;
- le bouton final de validation du régime.

Fichier concerné: [app/Views/user/objectifs/index.php](app/Views/user/objectifs/index.php)

### 1.6 Routes

Les routes ont été ajoutées pour supporter le wizard:

- `GET /objectifs/choose`;
- `POST /objectifs/choose`;
- `POST /objectifs/choose/save`.

Fichier concerné: [app/Config/Routes.php](app/Config/Routes.php)

## 2. Comment le flux fonctionne maintenant

### Étape 1: entrée dans le système

Quand l’utilisateur arrive sur son espace, l’application vérifie si un objectif existe déjà.

Si non, il est envoyé sur `/objectifs/choose`.

### Étape 2: choix de l’objectif

L’utilisateur choisit:

- prendre du poids;
- perdre du poids;
- atteindre l’IMC idéal.

Si l’objectif est “IMC idéal”, le poids cible est calculé automatiquement à partir de la taille.

### Étape 3: calcul des régimes

Le système calcule la différence entre poids actuel et poids cible.

Ensuite il demande à `RegimeDetailModel::getSuggestions()` la liste des régimes compatibles avec le sens de variation.

Le tri final se fait par `duree_totale_calculee`.

### Étape 4: validation finale

Quand l’utilisateur valide un régime:

- le serveur recalcule la cohérence des données;
- il vérifie que le régime choisi fait bien partie des suggestions;
- il applique la remise si l’utilisateur est Gold;
- il vérifie le solde;
- il enregistre tout dans la table `objectif`;
- il met à jour le solde utilisateur.

## 3. Où intégrer la partie portefeuille / créditation avec code

### 3.1 Le bon endroit pour l’intégration

La meilleure place pour intégrer le portefeuille et la créditation est dans la phase finale du parcours, au moment où l’utilisateur clique sur “Valider ce régime”.

Concrètement, c’est dans:

- `ObjectifController::save()`

Fichier: [app/Controllers/ObjectifController.php](app/Controllers/ObjectifController.php)

### 3.2 Ce qu’il faut ajouter pour un vrai portefeuille

Si tu veux une logique propre, il faut séparer en deux responsabilités:

#### A. Gestion du portefeuille

Créer un contrôleur dédié, par exemple:

- `PortefeuilleController`

Rôle:

- afficher le solde;
- afficher l’historique des crédits;
- afficher les opérations de paiement;
- centraliser les recharges.

#### B. Créditation par code

La table `code_argent` existe déjà.

Elle doit servir à:

- valider un code saisi par l’utilisateur;
- récupérer sa valeur;
- créditer le solde de l’utilisateur;
- marquer le code comme utilisé.

Fichier structure: [app/Database/Migrations/2026-05-06-211359_CreateCodeArgentTable.php](app/Database/Migrations/2026-05-06-211359_CreateCodeArgentTable.php)

### 3.3 Les points d’intégration concrets

#### Point 1: formulaire de recharge

Ajouter une page ou une modale pour saisir un code argent.

Ce formulaire doit envoyer vers une route du type:

- `POST /portefeuille/code`

#### Point 2: validation du code

Dans le contrôleur portefeuille:

- vérifier que le code existe;
- vérifier qu’il est encore valide;
- vérifier qu’il n’a pas déjà été utilisé;
- créditer le solde de l’utilisateur;
- enregistrer l’utilisation du code.

#### Point 3: paiement de l’objectif

Dans `ObjectifController::save()`:

- vérifier le solde après la recharge éventuelle;
- si le solde est insuffisant, renvoyer vers le portefeuille;
- si le solde est suffisant, enregistrer l’objectif et débiter le montant.

## 4. Structure recommandée pour la suite

### Tables déjà utiles

- `utilisateur` pour le solde;
- `objectif` pour la commande finale;
- `code_argent` pour les recharges;
- `regime_detail` pour le calcul des suggestions.

### Nouveau flux conseillé

1. l’utilisateur choisit son objectif;
2. le système affiche les régimes;
3. si le solde est insuffisant, redirection vers portefeuille;
4. l’utilisateur saisit un code de recharge;
5. le code crédite le compte;
6. retour vers la validation du régime;
7. enregistrement final dans `objectif`.

## 5. Ce qu’il faut coder ensuite

Si tu veux terminer la partie complète, les prochains fichiers à créer ou compléter sont:

- `app/Controllers/PortefeuilleController.php`
- `app/Models/CodeArgentModel.php`
- une vue pour saisir le code de recharge
- éventuellement une table d’historique des transactions si tu veux tracer les crédits/débits

## 6. Résumé court

Le cœur du système est déjà branché dans `ObjectifController`.

La partie portefeuille/créditation doit être ajoutée juste avant la validation finale, dans un contrôleur séparé si tu veux quelque chose de propre et maintenable.

Le point d’entrée logique est donc:

- recharge du solde via `code_argent`;
- puis validation du régime dans `ObjectifController::save()`.
