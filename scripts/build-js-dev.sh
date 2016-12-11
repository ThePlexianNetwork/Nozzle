#!/bin/bash
mkdir -p Build/js/

babel --presets es2015 src/Client/js/scripts/org/plexian/Nozzle/Client/Nozzle.js -o build/js/Nozzle.js