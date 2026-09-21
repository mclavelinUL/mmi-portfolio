/**
 * Fabrique le zip distribuable : dist/mmi-portfolio.zip
 *
 *   npm run package
 *
 * Ce que le zip contient : le code exécuté par WordPress, le CSS compilé,
 * la police et les icônes.
 * Ce qu'il ne contient pas : l'historique git, node_modules, les sources SCSS,
 * l'outillage, les XML de travail et les fichiers macOS — c'est-à-dire tout ce
 * que l'ancien zip fabriqué depuis le Finder embarquait par accident.
 */
import { execFile } from 'node:child_process';
import { mkdir, rm, cp, readFile, writeFile } from 'node:fs/promises';
import { promisify } from 'node:util';
import { existsSync } from 'node:fs';

const run = promisify(execFile);

const SLUG = 'mmi-portfolio';
const STAGE = `dist/${SLUG}`;

// Chemins repris tels quels dans le zip.
const INCLUDE = [
  'app.php',
  'uninstall.php',
  'readme.md',
  'includes',
  'data',
  'templates',
];

// Retirés après la copie (présents dans les dossiers ci-dessus).
const STRIP = [
  `${STAGE}/templates/src`,
  `${STAGE}/data/_acs.xml`,
  `${STAGE}/data/_all_content.xml`,
  `${STAGE}/data/all_content_test.xml`,
];

const pkg = JSON.parse(await readFile('package.json', 'utf8'));

// La version du zip doit être celle déclarée à WordPress.
const header = await readFile('app.php', 'utf8');
const headerVersion = header.match(/^\s*\*\s*Version:\s*(.+)$/m)?.[1].trim();

if (!headerVersion) {
  throw new Error("Version introuvable dans l'en-tête de app.php");
}

if (headerVersion !== pkg.version) {
  throw new Error(
    `Versions désaccordées : app.php dit ${headerVersion}, package.json dit ${pkg.version}.`
  );
}

if (!existsSync('templates/portfolio.css')) {
  throw new Error('templates/portfolio.css absent — lance `npm run build` avant de packager.');
}

await rm('dist', { recursive: true, force: true });
await mkdir(STAGE, { recursive: true });

for (const entry of INCLUDE) {
  if (!existsSync(entry)) {
    throw new Error(`Chemin attendu absent : ${entry}`);
  }
  await cp(entry, `${STAGE}/${entry}`, {
    recursive: true,
    // Filtre : aucun fichier macOS ni dossier caché ne doit entrer.
    filter: (src) => {
      const base = src.split('/').pop();
      return base !== '.DS_Store' && !base.startsWith('._');
    },
  });
}

for (const path of STRIP) {
  await rm(path, { recursive: true, force: true });
}

// -x exclut par sécurité ce qui aurait échappé au filtre de copie.
await run('zip', [
  '-r', '-q', '-X',
  `${SLUG}.zip`, SLUG,
  '-x', '*.DS_Store', '*/__MACOSX/*', '*/.git/*',
], { cwd: 'dist' });

await writeFile('dist/VERSION', `${headerVersion}\n`);

const { stdout } = await run('du', ['-h', `dist/${SLUG}.zip`]);
console.log(`dist/${SLUG}.zip — version ${headerVersion} — ${stdout.trim().split(/\s+/)[0]}`);
