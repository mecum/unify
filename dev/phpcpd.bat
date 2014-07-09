echo off

cd ../

cmd /k  ("vendor/bin/phpcpd.bat" "src/")

pause