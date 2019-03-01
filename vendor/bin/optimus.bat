@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../jenssegers/optimus/bin/optimus
php "%BIN_TARGET%" %*
