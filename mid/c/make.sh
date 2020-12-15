#! /bin/bash

php make.php
gcc mid.c -o mid
chmod 755 mid
sudo echo sudo authorized for this shell
sudo chown root mid
sudo chmod u+s mid
