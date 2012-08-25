@echo off
pushd .
cd %~dp0
cd "../doctrine/orm/bin"
set BIN_TARGET=%CD%\doctrine
popd
php "%BIN_TARGET%" %*
