User              → id, role (admin, utilisateur)

Utilisateur       → id, id_user, nom, email, date_naissance, genre,
                    poids_actuel, taille, est_gold, solde_monnaie

Intrepretation_IMC  → id, libelle, min, max

Type_objectif   → id, libelle
                        
Objectif          → id, id_utilisateur, id_type_objectif, valeur_poids

Regime            → id, nom, pct_viande, pct_poisson, pct_volaille

Regime_detail     → id, id_regime, duree, prix, variation_poids

Sport             → id, nom

Code_argent       → id, code, valeur, est_valide, id_utilisateur

Suggestion_Programme        → id, id_utilisateur, id_objectif, date_debut, duree_programme

Suggestion_Programme_detail → id, id_suggestion, id_regime, id_sport