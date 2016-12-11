# Nozzle

[![Stories in Ready](https://badge.waffle.io/ThePlexianNetwork/Nozzle.png?label=ready&title=Ready)](http://waffle.io/ThePlexianNetwork/Nozzle)

Release: [![Build - Release](https://travis-ci.org/ThePlexianNetwork/Nozzle.svg?branch=release)](https://travis-ci.org/ThePlexianNetwork/Nozzle)

Beta: [![Build - Beta](https://travis-ci.org/ThePlexianNetwork/Nozzle.svg?branch=beta)](https://travis-ci.org/ThePlexianNetwork/Nozzle)

Nozzle is a cross-platform, cross-language, implementation of the Plexian Distribution API. Simple add the library to your project and get cracking!

## Building - Client

### Javascript

1. Clone this repository, then ```cd``` into it.
2. Type ```npm install``` to install dependencies (if this doesn't work Nozzle depends on babel for es2015 and uglifyjs)
3. Do you want a development version of the code (with comments & expanded) or a minified version?
  a. __Development__ Type ```npm run build-js-dev```
  b. __Minified__ Type ```npm run build-js```
4. Build is in the ```Build/js``` directory in the ```Nozzle.js``` file