# Nozzle

[![Stories in Ready](https://badge.waffle.io/ThePlexianNetwork/Nozzle.png?label=ready&title=Ready)](http://waffle.io/ThePlexianNetwork/Nozzle)
[![Build - Release](https://travis-ci.org/ThePlexianNetwork/Nozzle.svg?branch=release)](https://travis-ci.org/ThePlexianNetwork/Nozzle)
[![GitHub issues](https://img.shields.io/github/issues/ThePlexianNetwork/Nozzle.svg)](https://github.com/ThePlexianNetwork/Nozzle/issues)
[![GitHub forks](https://img.shields.io/github/forks/ThePlexianNetwork/Nozzle.svg)](https://github.com/ThePlexianNetwork/Nozzle/network)

Nozzle is a cross-platform, cross-language, implementation of the Plexian Distribution API. Simple add the library to your project and get cracking!

## Branches
### Release
[![Build - Release](https://travis-ci.org/ThePlexianNetwork/Nozzle.svg?branch=release)](https://travis-ci.org/ThePlexianNetwork/Nozzle)

The release branch is where the latest stable release of Nozzle can be downloaded from. This branch is protected from pushing by everyone but
administrators. As such, please refrain from making a pull request to it.

### Beta
[![Build - Beta](https://travis-ci.org/ThePlexianNetwork/Nozzle.svg?branch=beta)](https://travis-ci.org/ThePlexianNetwork/Nozzle)

The beta branch is where the latest and greatest advancemenets are made. This branch is actively being edited by developers and pull requests being merged.


## Building - Client
### Javascript

1. Clone this repository, then ```cd``` into it.
2. Type ```npm install``` to install dependencies (if this doesn't work Nozzle depends on babel for es2015 and uglifyjs)
3. Do you want a development version of the code (with comments & expanded) or a minified version?
  * __Development__ Type ```npm run build-js-dev```
  * __Minified__ Type ```npm run build-js```
4. Build is in the ```Build/js``` directory in the ```Nozzle.js``` file