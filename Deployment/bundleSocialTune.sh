#!/bin/bash

# i used this website for the bash commands: https://www.gnu.org/software/bash/manual/bash.html
# i used this website for the bash test operators: https://linuxhandbook.com/bash-test-operators/

# this will help me to check the version
#if [ -z "$1" ]; then
#  echo "provide bundle name, example: ./bundleSocialTune.sh <version>"
#  exit 1
#fi

#VERSION=$1
#ENV=${2:-"QA"}

# handling the race condition 
ENV="DEPLOYMENT"
echo "=> we're gettting the next version..."

VERSION=$(ssh katerinegomez@100.97.21.49 "php /home/katerinegomez/deployment/incrementBundle.php")

echo "=> the version for this bundle is: $VERSION"

BUNDLE="socialtune_v${VERSION}.tar.gz"
SRC="$HOME/git/db_survivalists/Survivalists-IT490"
BUILD="/tmp/build_$VERSION"

USER="katerinegomez"
HOST="100.97.21.49"
PATH_REMOTE="/home/katerinegomez/bundles"

# this will remove the old folder only if it exists and then it will make a new one
echo "=> we're creating the build directory..."
rm -rf $BUILD
mkdir -p $BUILD/files

# this will copy everyhing that we have to the new folder
echo "=> we're copying the project files..."
cp -r $SRC/* $BUILD/files/

echo "=>we're creating the deploy config..."
# this part will create the deploy.json so the instller knows to do
# i used this reference for cat eOF: https://stackoverflow.com/questions/2500436/how-does-cat-eof-work-in-bash
cat <<EOF > $BUILD/deploy.json
{
  "copyFiles": [
    {
      "source": "files/",
      "destination": "/var/www/html/"
    }
  ],
  "commands": ["echo Deployment completed"],
  "services": ["apache2"]
}
EOF

# this will compress everything into the tar.gz 
echo "=> we're creating the bundle..."
tar -czf /tmp/$BUNDLE -C $BUILD .

scp /tmp/$BUNDLE $USER@$HOST:$PATH_REMOTE/

# here if the last commnad wasnt successful, im printing an error and it will quit
# i used this reference to understand the bash variables: https://tecadmin.net/bash-special-variables/
if [ $? -ne 0 ]; then
  echo "=> sorry! the bundle creation failed"
  exit 1
fi

echo "=> we're sending the bundle..."
ssh $USER@$HOST "cd /home/katerinegomez/deployment && php socialTuneDeploy.php socialtune $VERSION $ENV"
echo "=> congrats! The bundle was sent successfully."
