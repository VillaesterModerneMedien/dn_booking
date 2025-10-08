// Importieren Sie erforderliche Module
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import multiEntry from '@rollup/plugin-multi-entry';

// Rollup-Konfiguration
export default commandLineArgs => {
    return {
        // Eingabedatei als Kommandozeilenargument akzeptieren
        input: 'media/js/src/*.js', // Glob pattern für alle JS-Dateien im src-Verzeichnis

        // Rollup-Plugins
        plugins: [
            resolve(), // Löst Module auf
            commonjs(), // Wandelt CommonJS-Module in ES6 um, damit sie Rollup verarbeiten kann
            multiEntry() // Ermöglicht die Verarbeitung mehrerer Eingabedateien
        ],

        // Ausgabekonfiguration
        output: {
            file: 'media/js/script.js', // Ziel-Bundle-Datei
            format: 'iife', // Sofort ausführbare Funktion (kann je nach Bedarf angepasst werden)
            sourcemap: true  // Sourcemaps erzeugen, falls benötigt
        }
    };
};
