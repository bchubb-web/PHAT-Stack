const start = performance.now();

import fs from 'fs';
import readline from 'readline';
import config from './twc.config.js';

let comment = false
let compiledStyles = '';

const rl = readline.createInterface({
    input: fs.createReadStream(config.source),
    crlfDelay: Infinity
});

rl.on('line', (line) => {
    if (line == '' || line.replaceAll(' ','').includes(':;')) return;
    if (line.substring(0,2) === '//') return;
    if (line.includes('/*') && !line.includes('*/')){
        comment = true;
        return;
    } else if (line.includes('*/')){
        comment = false;
        return;
    }
    if (comment) return;

    if (line.charAt(0) === ' '){
        line = line.trim();
    }
    
    [': ',' {',', '].forEach((trimmer) => {
        if (line.includes(trimmer)) {
            line = line.replaceAll(trimmer, trimmer.trim());
        }
    });

    compiledStyles += line;
});


rl.on('close', () => {
    fs.writeFileSync('./styles.php', '<style>'+compiledStyles+'</style>');

    const beforeSize = parseInt(fs.statSync(config.source).size);
    const afterSize = Buffer.byteLength(compiledStyles, 'utf8')

    console.log(`Compilation complete
    Size before: ${beforeSize}
    Size after: ${afterSize}
    ${100-(afterSize/beforeSize)*100}% reduction
    Took: ${performance.now() - start}ms`);
});
