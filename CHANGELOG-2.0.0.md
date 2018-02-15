# 2.0.0.x

## Bug Fixes

* Fixed issue #248 : Strange behaviour of translated uiButtons.
* Fixed issue #255 : Fixed issue when asking permissions on guest admin groups crashing controller

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

# 2.0.0.0-alpha1 (2018-01-20)

**It starts again!**

