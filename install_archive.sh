#!/bin/bash

mkdir temp
rsync -avz upload/ temp/
cd temp
zip -r dle_api.zip *
cd ..
cp -f temp/dle_api.zip install.zip
rm -rf temp
exit 0