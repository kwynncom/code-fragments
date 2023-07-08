#! /bin/bash

# Either populate the domain just below or export the var from the config file just below that
# the CONFFILE will override the definition just below due to order of execution
# DOMAINRDTMATE=example.com
CONFFILE=/var/kwynn/tmate/PRIVATE_config.sh

# 2023/07/07 23:04

SLEEPN=7


# conf blah or define URLRDTMATE
TESTLOG=/tmp/tmrdtest

if [ -f "$CONFFILE" ]; then
	source $CONFFILE
fi


URLRDTMATE=https://$DOMAINRDTMATE/receiveLogs.php
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
		bash $0 -send $SOCK &
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
