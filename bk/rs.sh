#! /bin/bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
cd $SCRIPT_DIR

bash ./20_mount.sh

source public_paths.sh
source PRIVATE_paths.sh

if [ -f "$KWBK23DATTOF" ]; then
	echo no to dir
	exit
fi

echo BEGIN > $KWBK23LOG
chmod 600 $KWBK23LOG
echo date  >> $KWBK23LOG
rsync -aLvv --itemize-changes --exclude-from=./ie --mkpath $HOME/ $KWBK23DATTOF/ >> $KWBK23LOG
echo date >> $KWBK23LOG
echo END  >> $KWBK23LOG

cat $KWBK23LOG | grep ++
cat $KWBK23LOG | grep -P "\.\." 
