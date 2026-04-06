#!/bin/bash

# i used this website for the bash commands: https://www.gnu.org/software/bash/manual/bash.html
# i used this website for the bash test operators: https://linuxhandbook.com/bash-test-operators/

# this will help me to check the version
if [ -z "$1" ]; then
  echo "provide bundle name, example: ./bundleSocialTune.sh <version>"
  exit 1
fi

bundleName="socialtune"
VERSION=$1
sourceDir="$HOME/git/db_survivalists/Survivalists-IT490"
buildDir="/tmp/build_$VERSION"
bundleFile="${bundleName}_v${VERSION}.tar.gz"
deployUser="katerinegomez"
deployHost="100.97.21.49"
deployPath="/home/katerinegomez/bundles"


