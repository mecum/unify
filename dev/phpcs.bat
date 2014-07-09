echo off

cd ../

cmd /k  ("vendor/bin/phpcs" --standard="PSR2"  --report-full --ignore="*/tests/*" src/)

pause