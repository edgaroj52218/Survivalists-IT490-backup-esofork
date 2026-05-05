#!/bin/bash

#if [ -z "$1" ]; then
#  echo "you need to enter: ./bundler.sh <version> [env]"
#  exit 1
#fi

#VERSION=$1
#ENV=${2:-"PROD"}

ENV="PROD"

echo "=> we're getting the latest passed version..."
VERSION=$(php /home/katerinegomez/deployment/passedVersion.php)

if [ -z "$VERSION" ]; then
  echo "=> error: we couldn't find a passed version."
  exit 1
fi
echo "=> we're using the passed version $VERSION" 

BUNDLE="socialtune_v${VERSION}.tar.gz"
USER="katerinegomez"
HOST="100.68.10.113"
REMOTE_SRC_PATH="/home/katerinegomez/bundles"

scp $REMOTE_SRC_PATH/$BUNDLE $USER@$HOST:$REMOTE_SRC_PATH/

echo "Running deployment on PROD"

ssh $USER@$HOST "bash /home/katerinegomez/deployment-agent/install3.sh $BUNDLE"
