#!/bin/bash

VERSION=$1
ENV="QA"

# im using the same credentials
BUNDLE="socialtune_v${VERSION}.tar.gz"
USER="katerinegomez"
HOST="100.114.80.98"
REMOTE_SRC_PATH="/home/katerinegomez/bundles"

scp $REMOTE_SRC_PATH/$BUNDLE $USER@$HOST:$REMOTE_SRC_PATH/

echo "=> we're are rolling back on QA to the version $VERSION"
ssh $USER@$HOST "bash /home/katerinegomez/deployment-agent/install3.sh $BUNDLE"
echo "=> the rollback was completed!"
