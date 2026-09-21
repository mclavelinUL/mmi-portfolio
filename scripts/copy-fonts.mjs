// Copie la police variable auto-hébergée depuis node_modules vers templates/assets/fonts.
// Auto-hébergement volontaire : aucun appel à fonts.googleapis.com (RGPD + perf).
import { cp, mkdir } from 'node:fs/promises';

const src = 'node_modules/@fontsource-variable/source-serif-4/files';
const dest = 'templates/assets/fonts';

await mkdir(dest, { recursive: true });
await cp(`${src}/source-serif-4-latin-wght-normal.woff2`, `${dest}/source-serif-4-latin-wght-normal.woff2`);
console.log('Police copiée dans', dest);
