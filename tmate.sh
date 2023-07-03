#! /bin/bash

if [ "$1" != "-send" ]
	then
		PRE='tmate-KMB-Test-'
		EPOCH=`date '+%s'`
		SOCK=$PRE$EPOCH
		bash ./$0 -send $SOCK &
		tmate -S $SOCK
		exit
fi

SOCK=$2
sleep 5
echo $SOCK

# tmate -S $SOCK

