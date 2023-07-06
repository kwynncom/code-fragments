#! /bin/bash

# 2023/07/05 21:25

SLEEPN=7

# URLRDTMATE=https://example.com/server.php
# conf blah or define URLRDTMATE
CONFFILE=/var/kwynn/tmate/PRIVATE_config.sh
TESTLOG=/tmp/tmrdtest

if [ -f "$CONFFILE" ]; then
	source $CONFFILE
fi

echo $URLRDTMATE

# exit

# minimum session info strlen
MINSISL=400

touch $TESTLOG
chmod 600 $TESTLOG

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

# cat st1 | grep -P "ssh session\: .+\b"
# 6 lines

sleep $SLEEPN
SINFO=`tmate -S $SOCK show-messages`
RET10=`echo $SINFO |  grep -P "ssh session\: .+\b"`
SESCH=`echo $RET10 | wc | awk '{print $3}'`
ALLCH=`echo $SINFO | wc | awk '{print $3}'`
echo sess chars $SESCH >> $TESTLOG
echo info chars $ALLCH >> $TESTLOG

if (( $SESCH > $MINSISL)); then
	echo sess chars pass >> $TESTLOG
fi

if (( $ALLCH > $MINSISL)); then
	echo all chars pass >> $TESTLOG
fi

echo $SINFO | curl -X POST -d "$(</dev/stdin)" $URLRDTMATE
