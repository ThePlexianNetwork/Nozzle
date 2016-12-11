#!/bin/bash
mkdir -p build/js/

babel --presets es2015 src/Client/js/scripts/org/plexian/Nozzle/Client/Nozzle.js -o build/js/Nozzle.bundle.js

uglifyjs build/js/Nozzle.bundle.js -o build/js/Nozzle.js -m -c
rm -r build/js/Nozzle.bundle.js