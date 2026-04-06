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

# i used this reference to understand jq better: https://jqlang.org/manual/
# this part will copy all the files -- note: -c means compact aand helps in this case so it doesnt break the loop -- note: -r will remove the quotes so the cp doesnt get confused
jq -c '.copyFiles[]' deploy.json | while read item; do
  SRC=$(echo $item | jq -r '.source')
  DEST=$(echo $item | jq -r '.destination')

  echo "=> we're copying $SRC to $DEST"
  sudo cp -r $SRC/* $DEST
done

# this will run the commands
jq -r '.commands[]' deploy.json | while read cmd; do
  echo "=> we're running: $cmd"
  # eval helps to run the command from the json
  eval $cmd
done

# this will restart the services 
jq -r '.services[]' deploy.json | while read service; do
  echo "=> we're restarting: $service"
  sudo systemctl restart $service
done

echo "=> congrats! The deployment was completed successfully!"
