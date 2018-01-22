# 2.0.0.x

## Bug Fixes

* `run.bat` & `run.sh` contained errors : 
  * Cache was not cleared at proper place, and required parameters to be correct
  * Database build commands were not executed in prod mode
* Fixed default `parameters.yml.dist`
  * mysql host was from docker
  * repalced localhost by 127.0.0.1 as on windows with wamp it works more often.
* Fixed Issue #230 
    * Impossible to unmute player from interface
* Fixed issue #224 
    * Update.bat file didn't work and was not available in releases.

# 2.0.0.0-alpha1 (2018-01-20)

**It starts again!**

