#!/bin/bash

mkdir temp
rsync -avz upload/ temp/
cd temp
zip -r maharder_assets.zip *
cd ..
cp -f temp/maharder_assets.zip install.zip
rm -rf temp
exit 0