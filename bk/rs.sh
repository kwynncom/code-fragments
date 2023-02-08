#! /bin/bash
RTO=$1

rsync -aLvv --itemize-changes --exclude-from=./ie --mkpath $HOME/ $RTO/

# -L copy sym links to referent
# --archive, -a            archive mode is -rlptgoD (no -A,-X,-U,-N,-H)
# 4 is prefer IPv4 (not relevant to local)
#        --compress, -z           compress file data during the transfer
#        --verbose, -v            increase verbosity
