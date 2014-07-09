echo off

cd ../

cmd /k  ("vendor/bin/phpunit.bat"  --verbose)

pause