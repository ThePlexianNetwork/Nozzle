#!/bin/bash
mkdir -p Build/js/

babel --presets es2015 Client/js/scripts/org/plexian/Nozzle/Client/Nozzle.js -o Build/js/Nozzle.bundle.js

uglifyjs Build/js/Nozzle.bundle.js -o Build/js/Nozzle.js -m -c
rm -r Build/js/Nozzle.bundle.js