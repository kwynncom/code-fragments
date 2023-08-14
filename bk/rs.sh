#! /bin/bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
cd $SCRIPT_DIR

bash ./20_mount.sh

source public_paths.sh
source PRIVATE_paths.sh

echo TO DIR below
echo $KWBK23DATTOF 

if [ -f "$KWBK23DATTOF" ]; then
	echo no to dir
	exit
fi

echo BEGIN > $KWBK23LOG
echo date  >> $KWBK23LOG
rsync -aLvv --itemize-changes --exclude-from=./ie --mkpath $HOME/ $KWBK23DATTOF/ >> $KWBK23LOG
echo date >> $KWBK23LOG
echo END  >> $KWBK23LOG

LOG=$KWBK23LOG
cat $LOG | grep ++
cat $LOG | grep -P "\.\." 


# -L copy sym links to referent
# --archive, -a            archive mode is -rlptgoD (no -A,-X,-U,-N,-H)
# 4 is prefer IPv4 (not relevant to local)
#        --compress, -z           compress file data during the transfer
#        --verbose, -v            increase verbosity

# SCRIPT_DIR from 
# https://stackoverflow.com/questions/59895/how-do-i-get-the-directory-where-a-bash-script-is-located-from-within-the-script
