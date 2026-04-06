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

# this will remove the old folder only if it exists and then it will make a new one
echo "=> we're creating the build directory..."
rm -rf $buildDir
mkdir -p $buildDir/files

# this will copy everyhing that we have to the new folder
echo "=> we're copying the project files..."
cp -r $sourceDir/* $buildDir/files/

echo "=>we're creating the deploy config..."

# this part will create the deploy.json so the instller knows to do
# i used this reference for cat eOF: https://stackoverflow.com/questions/2500436/how-does-cat-eof-work-in-bash
cat <<EOF > $buildDir/deploy.json
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
tar -czf /tmp/$bundleFile -C $buildDir .

# here if the last commnad wasnt successful, im printing an error and it will quit
# i used this reference to understand the bash variables: https://tecadmin.net/bash-special-variables/
if [ $? -ne 0 ]; then
  echo "=> sorry! the bundle creation failed"
  exit 1
fi

echo "=> we're sending the bundle..."
scp /tmp/$bundleFile $deployUser@$deployHost:$deployPath/

if [ $? -ne 0 ]; then
  echo "Sorry! The SCP failed"
  exit 1
fi

echo "=> congrats! The bundle was sent successfully: $bundleFile"
