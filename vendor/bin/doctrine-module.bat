@echo off
pushd .
cd %~dp0
cd "../doctrine/doctrine-module/bin"
set BIN_TARGET=%CD%\doctrine-module
popd
php "%BIN_TARGET%" %*
