# 2.0.0.x 

## Fixes
* Fixed #119 : Fixed all symfony commands requiring connection to the dedicated server. 
* Fixed : Typo in addEventProcessor method of the application dispatcher.

## Features
* Added ingame configuration system. 
* Added DeveloperTools bundle
* Fixed issue #277 - sorting numeric values
* Add configs for various types, booleans, textfields, password
* Add MxKarma 
* #118 : eXpansion will attempt multiple connections to dedicated before crashing. 

# 2.0.0.0-alpha3 (2018-02-24)
## Bug Fixes

* Fixed issue #248 : Strange behaviour of translated uiButtons.
* Fixed issue #255 : Fixed issue when asking permissions on guest admin groups crashing controller
* Fixed issue #269 : eXpansion crashing with unknow player exception.

## Features 

* Feature #262 : Added support to ping eXpansion Analytics.
* Feature #266 : Add warning that displayes every 5 minutes about dev mode.

# 2.0.0.0-alpha2 (2018-02-03)

## Bug Fixes

* `run.bat` & `run.sh` contained errors : 
  * Cache was not cleared at proper place, and required parameters to be correct
  * Database build commands were not executed in prod mode
* Fixed default `parameters.yml.dist`
  * mysql host was from docker
  * replaced localhost by 127.0.0.1 as on windows with wamp it works more often.
* Fixed issue #230 
    * Impossible to unmute player from interface
* Fixed issue #224 
    * Update.bat file didn't work and was not available in releases.
* Fixed issue #235
    * Added missing translations for players-window.
    * Removed error message at vote manager, when a plugin is not bind for native vote equivalent.
* Fixed issue when shuffling maps if there are more then 250 maps. 
* Fixed issue #243
    * If custom script mode doesen't have method `setApiVersion` eXpansion crashes.
* Enhanced admin logging and added nicer error messages for missing dedicated and database connections.
* Fix layoutLine to accept centered and left-aligned elements

# 2.0.0.0-alpha1 (2018-01-20)

**It starts again!**

