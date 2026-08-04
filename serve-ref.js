const http = require('http');
const fs = require('fs');
const path = require('path');

const PORT = 9000;

// Read the reference HTML we already fetched
const refHtmlPath = 'C:/unicancer/ref-vi-live.html';
let refHtml = '';
let hasRef = fs.existsSync(refHtmlPath);

const server = http.createServer((req, res) => {
    // Serve the reference HTML (it's already the "correct" version)
    if (hasRef) {
        res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end(refHtml);
    } else {
        res.writeHead(200, { 'Content-Type': 'text/plain' });
        res.end('Reference HTML not found at ' + refHtmlPath);
    }
});

server.listen(PORT, () => {
    console.log(`Reference site served at http://127.0.0.1:${PORT}/vi/`);
    console.log(`Press Ctrl+C to stop.`);
});
