# F1 Prono

Site de pronostics Formule 1 en Html/CSS/Java et PHP/MySQL, développé pour tourner sous MAMP.

## Prérequis

- [MAMP](https://www.mamp.info/) (ou tout environnement Apache + PHP + MySQL)
- PHP avec l'extension PDO MySQL activée

## Installation

1. **Cloner le dépôt** dans le dossier `htdocs` de MAMP :
   ```bash
   git clone https://github.com/<ton-utilisateur>/f1-prono.git
   ```

2. **Créer la base de données** dans phpMyAdmin (via MAMP) :
   - Créer une base nommée `f1_prono`
   - Importer le fichier `database/f1_prono.sql` fourni dans ce dépôt (onglet *Importer* de phpMyAdmin)

3. **Configurer la connexion à la base** :
   - Dupliquer `includes/database.example.php` en `includes/database.php`
   - Adapter les identifiants si besoin (par défaut sous MAMP : host `localhost`, user `root`, mot de passe `root`)

   ```php
   define('HOST', 'localhost');
   define('DB_NAME', 'f1_prono');
   define('USER', 'root');
   define('PASS', 'root');
   ```

   > ⚠️ `includes/database.php` est volontairement ignoré par Git (voir `.gitignore`) car il contient des identifiants. Chaque personne qui clone le projet doit créer son propre fichier à partir de l'exemple.

4. **Démarrer MAMP** et accéder au site via :
   ```
   http://localhost:8888/f1-prono/
   ```
   (le port peut varier selon ta configuration MAMP)

## Structure du projet

```
├── css/                  # Feuilles de style
├── img/                  # Images (pilotes, écuries, visuels)
├── includes/             # Fichiers PHP partagés (connexion BDD, auth...)
├── database/             # Export SQL de la base de données
├── index.php             # Page d'accueil
├── register.php          # Inscription
├── log.php               # Connexion
├── profil.php            # Profil utilisateur
├── pronostics.php        # Page de pronostics
├── admin_resultats.php   # Administration des résultats
├── classement.php        # Classement
└── decouverte.php        # Page découverte
```

## Mettre à jour l'export de la base de données

Si tu modifies la structure ou les données de test, régénère l'export avant de commit :

```bash
mysqldump -u root -p f1_prono > database/f1_prono.sql
```

(depuis MAMP, tu peux aussi le faire via phpMyAdmin : *Exporter* > format SQL)
