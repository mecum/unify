echo off

cd ../

cmd /k  ("vendor/bin/phploc.bat" "src")

pause