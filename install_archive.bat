@echo off
mkdir temp
robocopy upload temp /E
cd temp
set PATH=%PATH%;%ProgramFiles%\7-Zip\
7z a -mx0 -r -tzip -aoa maharder_assets.zip *
cd ..
copy /Y temp\maharder_assets.zip install.zip
rd /s /q temp
exit;