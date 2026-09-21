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
