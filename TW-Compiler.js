import fs from 'fs';
import readline from 'readline';
import config from './twc.config.json' assert { type: 'json'} ;

const styleSheet = fs.createReadStream(config.source);

let comment = false
let compiledStyles = '';
const vars = [];

const trimCases = [
    ': ', ' {', '}', ', '
]

const rl = readline.createInterface({
    input: styleSheet,
    crlfDelay: Infinity
});

rl.on('line', (line) => {
    if (line == '') return
    // if line opens comment but doesnt close
    if (line.includes('/*') && !line.includes('*/')){
        comment = true;
        return;
    } 
    // if closes comment
    else if (line.includes('*/')){
        comment = false;
        return;
    }
    // if in a comment and doesnt end it
    if (comment) return;

    // actual line
    line = line.trim();
    
    trimCases.forEach((case) => {

    })

    if (line.replaceAll(' ','').includes(':;')) return;

    if (line.replaceAll(' ','').substring(0,2) == '--'){
        const value = line.substring(line.indexOf(':')+1, line.indexOf(';'));
        console.log(line)
        console.log(value)
    }
    else {
        //line = line.trim();
    }
    
    compiledStyles += line;
})

rl.on('close', () => {
    console.log(compiledStyles);
})
