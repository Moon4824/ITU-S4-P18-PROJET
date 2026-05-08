<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        // UTILISATEURS
        $data = [
            [
                'id_role' => 1,
                'nom' => 'Admin Système',
                'email' => 'admin@app.com',
                'mot_de_passe' => '$2y$10$0MbgG5ykCS1deZaFdNBH8O9lS8CYKAICA6j2LbqTySNqEl1Kshhg.',
                'date_naissance' => '1990-01-01',
                'genre' => 'homme',
                'poids_actuel' => 75.00,
                'taille' => 1.75,
                'est_gold' => 0,
                'solde_monnaie' => 0.00,
            ],
            [
                'id_role' => 2,
                'nom' => 'Alice Dupont',
                'email' => 'alice@mail.com',
                'mot_de_passe' => '$2y$10$LPqMTwlpiC6pYO/5.NXTlOHEMejg91VcRaKyd15GOtE44mNBArxbS',
                'date_naissance' => '1995-03-12',
                'genre' => 'femme',
                'poids_actuel' => 72.00,
                'taille' => 1.65,
                'est_gold' => 0,
                'solde_monnaie' => 20.00,
            ],
            [
                'id_role' => 2,
                'nom' => 'Bob Martin',
                'email' => 'bob@mail.com',
                'mot_de_passe' => '$2y$10$LPqMTwlpiC6pYO/5.NXTlOHEMejg91VcRaKyd15GOtE44mNBArxbS',
                'date_naissance' => '1988-07-22',
                'genre' => 'homme',
                'poids_actuel' => 95.00,
                'taille' => 1.80,
                'est_gold' => 1,
                'solde_monnaie' => 50.00,
            ],
            [
                'id_role' => 4,
                'nom' => 'Clara Rabe',
                'email' => 'clara@mail.com',
                'mot_de_passe' => '$2y$10$LPqMTwlpiC6pYO/5.NXTlOHEMejg91VcRaKyd15GOtE44mNBArxbS',
                'date_naissance' => '2000-11-05',
                'genre' => 'femme',
                'poids_actuel' => 50.00,
                'taille' => 1.60,
                'est_gold' => 0,
                'solde_monnaie' => 10.00,
            ],
            [
                'id_role' => 5,
                'nom' => 'David Rakoto',
                'email' => 'david@mail.com',
                'mot_de_passe' => '$2y$10$LPqMTwlpiC6pYO/5.NXTlOHEMejg91VcRaKyd15GOtE44mNBArxbS',
                'date_naissance' => '1992-06-18',
                'genre' => 'homme',
                'poids_actuel' => 85.00,
                'taille' => 1.78,
                'est_gold' => 0,
                'solde_monnaie' => 0.00,
            ],
            [
                'id_role' => 6,
                'nom' => 'Eva Rasolofo',
                'email' => 'eva@mail.com',
                'mot_de_passe' => '$2y$10$LPqMTwlpiC6pYO/5.NXTlOHEMejg91VcRaKyd15GOtE44mNBArxbS',
                'date_naissance' => '1998-09-30',
                'genre' => 'femme',
                'poids_actuel' => 60.00,
                'taille' => 1.70,
                'est_gold' => 0,
                'solde_monnaie' => 30.00,
            ],
        ];

        $this->db->table('utilisateur')->insertBatch($data);
    }
}
