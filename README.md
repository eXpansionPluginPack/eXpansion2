# eXpansion² - Maniaplanet Server Controller

[![Build Status](https://travis-ci.org/eXpansionPluginPack/eXpansion2.svg?branch=master)](https://travis-ci.org/eXpansionPluginPack/eXpansion2)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eXpansionPluginPack/eXpansion2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eXpansionPluginPack/eXpansion2/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/eXpansionPluginPack/eXpansion2/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/eXpansionPluginPack/eXpansion2/?branch=master)

This is the new eXpansion 2 controllers framework, that uses symfony components.

User documentation & documentation to write you own Bundles(plugins) can be foind on our website :  http://mp-expansion.com/

**You will find documentation for core developers on this page.**

## Install local development environment. 

You wish to to help us to make the must awsome server controller for Maniapanet ever? 

You can do so with ease.

### Requirements

1. Have docker installed on your development computer
2. Have basic knowledge of Symfony service containers & autowiring

### Getting started

1. Clone this repository 
2. Copy the `docker-compose.yml.dist` file to `docker-compose.yml`
3. Start the dockers for the first time : 
```bash
docker-compose up
```
4. Run a composer install
```bash
docker-compose exec php composer install
```
5. Update database
```
docker-compose exec php bin/console doctrine:schema:update
```
6. Start eXpansion 
```bash
docker-compose exec php bin/console eXpansion:run
```
7. eXpansion will crash saying game mode is not scripts or it can't connect
(go figure it out, Nadeo tells us not to use legacy but default match setting files still have legacy)
8. Goto docker/data/UserData/Maps/MatchSettings
9. Rename the `eXpanion-mode-fail......txt` file to `TMCanyonA.txt
10. Edit content and change mode to script mode and proper script filename.
11. Copy the `docker/default.config.xml` file into docker/data/UserData/Config
11. Restart eXpansion
```bash
docker-compose exec php bin/console eXpansion:run
```


## TODO

- [ ] Remove unused symfony components (used full stack to get something fast)
- [ ] Separate into 3 repositories
    - Application for installation 
    - Core for the core
    - base plugins

