#! /bin/bash

RTO=$1
SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
cd $SCRIPT_DIR

rsync -aLvv --itemize-changes --exclude-from=./ie --mkpath $HOME/ $RTO/

# -L copy sym links to referent
# --archive, -a            archive mode is -rlptgoD (no -A,-X,-U,-N,-H)
# 4 is prefer IPv4 (not relevant to local)
#        --compress, -z           compress file data during the transfer
#        --verbose, -v            increase verbosity

# SCRIPT_DIR from 
# https://stackoverflow.com/questions/59895/how-do-i-get-the-directory-where-a-bash-script-is-located-from-within-the-script
