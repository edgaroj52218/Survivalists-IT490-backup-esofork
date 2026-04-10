#!/bin/bash

# i used this website for the bash commands: https://www.gnu.org/software/bash/manual/bash.html
# i used this website for the bash test operators: https://linuxhandbook.com/bash-test-operators/

# when somethiing fails, set -e stops the script stop 
set -e

BUNDLE=$1
BUNDLE_PATH="/home/katerinegomez/bundles"
DEST="/var/www/html"

echo "=> starting the  deployment..."

# here cheking if the bundle exists
if [ ! -f "$BUNDLE_PATH/$BUNDLE" ]; then
  echo "=> error: The bundle was not found!"
  exit 1
fi

# this will create the temporary file so it doesnt  conflict wiht the ones that are old.
TEMP="/tmp/deploy_$(date +%s)"
EXTRACT_DIR="$TMP/extracted"

echo "=> we're creating the temporary directory..."
mkdir -p $EXTRACT_DIR

echo "=> we're copying the bundle..."
cp $BUNDLE_PATH/$BUNDLE $TMP/
cd $TMP

echo "=> we're extracting bundle..."
# TYPE=$(file --mime-type -b $BUNDLE)

# reference i used to extract the tar files: https://linuxize.com/post/how-to-extract-unzip-tar-gz-file/
tar -xzf $BUNDLE -C $EXTRACT_DIR

echo "=> we're cleaning the old files..."
sudo rm -rf $DEST/*

echo "=> we're copying the new files..."

# using the if statemnt because there a cases where the bundle we're sending sometiems has files/ folder and other times not 
if [ -d "$EXTRACT_DIR/files" ]; then
  sudo cp -r $EXTRACT_DIR/files/* $DEST/
else
  sudo cp -r $EXTRACT_DIR/* $DEST/
fi

echo "=> we're setting the permissions..."
# i used this reference so i can implement  the chmod and chown in the right way: https://linuxize.com/post/linux-chown-command/
sudo chown -R www-data:www-data $DEST
sudo chmod -R 755 $DEST

echo "=? we're restarting Apache..."
# the changes need to happn so i restart apache
sudo systemctl restart apache2

echo "=> congrats! The deployment was completed successfully!"
