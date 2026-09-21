# MMI Portfolio

Plugin WordPress pour la gestion du portfolio pédagogique en BUT MMI.

Ce plugin nécessite l'ajout d'ACF Pro dans le dossier `vendor/advanced-custom-fields-pro`.

## Installation

1. Extensions → Ajouter une extension → Téléverser une extension
2. Téléverser le fichier zip
3. Activer l'extension
4. Ajouter un lien personnalisé dans le menu : `http[s]://votreurl.ext/portfolio-pedagogique/realisation-mmi/`

À l'activation, le plugin enregistre ses règles de réécriture et programme l'import
du contenu pédagogique (apprentissages critiques et termes). L'import s'exécute une
seule fois, au premier chargement de l'administration, et n'écrase rien d'existant.

## Développement des assets

Les styles sont écrits en SCSS dans `templates/src/` et compilés vers
`templates/portfolio.css`. **Ne pas éditer `templates/portfolio.css` à la main.**

```bash
npm install      # dépendances + copie de la police auto-hébergée
npm run dev      # compilation en continu pendant le développement
npm run build    # compilation minifiée avant commit
```

Organisation des partials :

| Fichier | Rôle |
|---|---|
| `_tokens.scss` | Couleurs, typographie, espacements, rayons — tous portés par `.mmi-portfolio` |
| `_fonts.scss` | Déclaration de la police auto-hébergée |
| `_base.scss` | Reset encapsulé, accessibilité, focus |
| `_components.scss` | Badges, boutons, jauge, prose, pagination, état vide |
| `_archive.scss` | Archive et cartes de réalisation |
| `_single.scss` | Fiche détail, sommaire, blocs d'apprentissage critique |

Toutes les règles sont encapsulées sous `.mmi-portfolio` : le plugin ne déborde pas
sur le reste du site, et un thème peut redéfinir les variables en surchargeant cette
seule classe.

La police (Source Serif 4 variable) est **auto-hébergée** dans
`templates/assets/fonts/` : aucun appel à `fonts.googleapis.com`. Elle est copiée
depuis `node_modules` par `npm install` (script `postinstall` → `npm run fonts`).
Le corps de texte utilise la pile système, sans requête supplémentaire.

## Commandes WP-CLI

```bash
wp mmi-portfolio import   # rejoue l'import du contenu pédagogique
```

## Dépendance : ACF Pro

ACF Pro n'est plus embarqué dans le plugin. Il doit être installé et activé
séparément. Le plugin le vérifie au chargement et affiche un message explicite
dans l'administration s'il manque ; les templates du portfolio ne prennent pas
la main tant que la dépendance n'est pas satisfaite.

Cette dépendance est externe pour trois raisons : ACF Pro est une extension
commerciale que le dépôt, public, ne peut pas redistribuer ; une copie figée ne
reçoit jamais ses correctifs de sécurité ; et les 26 Mo d'ACF alourdissaient
chaque release.

## Publier une version

1. Mettre à jour le numéro de version **au même endroit dans les deux fichiers** :
   l'en-tête de `app.php` (`Version:` et la constante `MMI_PORTFOLIO_VERSION`) et
   `package.json`. Le packaging refuse de tourner s'ils divergent.
2. Ajouter une migration dans `includes/mmi-portfolio-upgrade.php` si la version
   touche à la base de données.
3. Commiter, puis poser le tag :

```bash
git tag v1.3.0
git push origin main --tags
```

Le workflow GitHub Actions compile le CSS, contrôle la syntaxe PHP, vérifie que
le tag correspond à la version déclarée, fabrique `mmi-portfolio.zip` et le
joint à la release.

Les sites qui ont déjà le plugin voient la mise à jour apparaître dans
Extensions dans les 12 heures (durée du cache), ou immédiatement via le lien
« Vérifier les mises à jour » sur la ligne du plugin.

Pour fabriquer le zip localement sans publier :

```bash
npm run release     # build + package → dist/mmi-portfolio.zip
```

### Si le dépôt passe en privé

Ajouter dans `wp-config.php` de chaque site un jeton d'accès personnel ayant le
droit de lecture sur le dépôt :

```php
define( 'MMI_PORTFOLIO_GITHUB_TOKEN', 'ghp_...' );
```
