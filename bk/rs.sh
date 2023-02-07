#! /bin/bash
RTO=$1

# must be in quotes below or a blank directory exists - not sure what that is about
if [ -d "$RTO" ]; then 
	echo "Directory $RTO exists v4."
else 
	echo "Directory $RTO does not exist v4."
	exit 2210
fi

rsync -aLvv --itemize-changes --exclude-from=./ie $HOME/ $RTO/

# -L copy sym links to referent
# --archive, -a            archive mode is -rlptgoD (no -A,-X,-U,-N,-H)
# 4 is prefer IPv4 (not relevant to local)
#        --compress, -z           compress file data during the transfer
#        --verbose, -v            increase verbosity
