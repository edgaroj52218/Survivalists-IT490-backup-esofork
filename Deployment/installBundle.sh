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

# this will create the temporary file so it doesnt  conflict wiht the ones that are old.
temporaryDir="/tmp/deploy_$(date +%s)"

echo "=> we're creating the temporary directory..."
mkdir -p $temporaryDir

# this will download the bundle tp the temporary folder
echo "=> we're downloading the bundle..."
scp $deployUser@$deployHost:$deployPath/$bundleFile $temporaryDir/

# here if the last commnad wasnt successful, im printing an error and it will quit
# i used this reference to understand the bash variables: https://tecadmin.net/bash-special-variables/
if [ $? -ne 0 ]; then
  echo "=> the download failed"
  exit 1
fi

cd $temporaryDir

echo "=> we're extracting the bundle..."
tar -xzf $bundleFile

if [ $? -ne 0 ]; then
  echo "=> sorry, the extraction failed"
  exit 1
fi

echo "=> we're reading the deploy.json..."
