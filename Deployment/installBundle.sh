#!/bin/bash

# i used this website for the bash commands: https://www.gnu.org/software/bash/manual/bash.html
# i used this website for the bash test operators: https://linuxhandbook.com/bash-test-operators/

# this checks the bundle name
if [ -z "$1" ]; then
  echo "provide bundle name, example: ./installBundle.sh <bundle-name>"
  exit 1
fi

bundleFile=$1
deployUser="katerinegomez"
deployHost="100.97.21.49"
deployPath="/home/katerinegomez/bundles"
