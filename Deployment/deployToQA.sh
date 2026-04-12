#!/bin/bash

#if [ -z "$1" ]; then
#  echo "Usage: ./bundler.sh <version> [env]"
#  exit 1
#fi

#VERSION=$1
#ENV=${2:-"QA"}

ENV="QA"

echo "=> we're getting the latest new version..."
VERSION=$(php /home/katerinegomez/deployment/newVersion.php)

if [ -z "$VERSION" ]; then
  echo "=> error: we couldn't find a new version."
  exit 1
fi
echo "=> we're using the new version $VERSION"

BUNDLE="socialtune_v${VERSION}.tar.gz"
USER="katerinegomez"
HOST="100.114.80.98"
REMOTE_SRC_PATH="/home/katerinegomez/bundles"

scp $REMOTE_SRC_PATH/$BUNDLE $USER@$HOST:$REMOTE_SRC_PATH/

#ssh $USER@$HOST "cd /home/katerinegomez/bundles && tar -xvzf $BUNDLE -C /var/www/html/" #php socialTuneDeploy.php socialtune $VERSION $ENV"

echo "Running deployment on QA"

ssh $USER@$HOST "bash /home/katerinegomez/deployment-agent/install3.sh $BUNDLE"

#echo "Deployment Completed"

#trying to get the pass and failed. I added the ROLLBACK because it was adding a  new status even tho it was already on the db
if [ $? -eq 0 ]; then
    php /home/katerinegomez/deployment/socialTuneDeploy.php socialtune $VERSION $ENV passed 
else
    php /home/katerinegomez/deployment/socialTuneDeploy.php socialtune $VERSION $ENV failed 
fi
