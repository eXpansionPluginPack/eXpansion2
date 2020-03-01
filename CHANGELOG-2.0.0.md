# 2.0.0.x

## Fixes 

* Fixed #401 - Memory leak.

# 2.0.0.0-beta4 (2020-02-29)

## Features

* Feature #377 : Added more detailed expansion version information.

* Feature #380 : Added bundle to create random information messages.

* Feature #394 - Added additional information when connection to the dedicated fails.



## Fixes
* Fixed #384 - Usage of config values of type TextList won't crash expansion anymore.
* Fixed #379 : When expansion crashes in windows the command prompt wont close and hide the message.
* Fixed #365 - Fixed exp not starting if can't fetch title/game mappings for expansion api's.
* Fixed #393 - Updated propel and symfony to support php7.2 & php7.3 & php7.4. 
* Fixed #395 - Fixed run.sh script not stopping when database update didn't work.



# 2.0.0.0-beta3 (2018-06-09)

## Fixes

* Fixed #365 - Not starting if can't fetch title/game mappings for expansion api's.
* Fixed #374 - Permissions configured in the config file is ignored at first start.



## Features

* Feature #369 : Automatically saving server settings.

* Feature #370 : Automatically saving match settings

* Feature #372 : Admin Permissions's are now handled using services. This will allow in the feature to improve the permissions page & adds more flexibility.



# 2.0.0.0-beta2 (2018-05-10)

## Fixes

* Fixed #360 : Live ranking widgets crashing.



# 2.0.0.0-beta1 (2018-05-10)

## Fixes

* Fixed #119 : Fixed all symfony commands requiring connection to the dedicated server. 

* Fixed : Typo in addEventProcessor method of the application dispatcher.

* Fixed #322 : Issue with lost events during eXpansion start. This caused at startup not to have local records for exemple.

* Fixed : Issue on windows with script names can have capital letters.

* Fixed #339 mx info being null at database

* Fixed #277 : sorting numeric values

* Fixed #343 : Hopefully fixed once and for all RowLayout size issues.

* Fixed #354 : Fixed `bin/console` being a symlink.

* Fixed : Dedimania player disconnect handler 



## Features

* Feature Added ingame configuration system. 

* Feature Added DeveloperTools bundle

* Feature Add configs for various types, booleans, textfields, password

* Feature Add MxKarma 

* Feature #118 : eXpansion will attempt multiple connections to dedicated before crashing. 

* Feature : Improved performance of Vote Gui using script update.

* Feature : Console output for enabled/disabled plugins is lighter and easier to read. Additional info is in logs.

* Feature : Added chat command for donation.

* Feature #321 : Plugins can now have multiple data provider dependency with a OR logic. So if one of the providers is enabled it will enable the plugin

* Feature #177 : Local records window display race & lap records. 

* Feature #66 : Added experimental Dedimania support

* Feature #211 : Added possibility to create plugins witohut data providers.

* Feature #345 : Moved list of loaded bundles in a configuration file.

* Feature : Added Live rankings



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



