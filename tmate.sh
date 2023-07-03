#! /bin/bash

if [ "$1" != "-send" ]
	then
		SPRELOC='/tmp/'
		SPRENAME='tmate-KMB-Test-'
		EPOCH=`date '+%s'`
		SOCK=$SPRELOC$PRE$EPOCH
		bash ./$0 -send $SOCK &
		tmate -S $SOCK
		exit
fi

SOCK=$2
echo $SOCK

sleep 5
tmate -S $SOCK show-messages > /tmp/st1

