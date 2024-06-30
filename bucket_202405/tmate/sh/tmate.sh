#! /bin/bash

# Either populate the domain just below or export the var from the config file just below that
# the CONFFILE will override the definition just below due to order of execution

# DOMAINRDTMATE=example.com
CONFFILE=/var/kwynn/tmate/PRIVATE_config.sh

# 2023/07/13 12:45

TMRDPATH=/
TMMETASRV=receiveLogs.php
IPSRV=receiveIPGeo.php


SLEEPN=7

# conf blah or define URLRDTMATE
TESTLOG=/tmp/tmrdtest

if [ -f "$CONFFILE" ]; then
	source $CONFFILE
fi


SRVPATH=https://$DOMAINRDTMATE$TMRDPATH
URLRDTMATE=$SRVPATH$TMMETASRV
URLRDGEO=$SRVPATH$IPSRV

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

echo $SINFO    | curl -X POST -d "$(</dev/stdin)" $URLRDTMATE

IPINFO=`curl ipinfo.io`
IPALL=`echo $ALLCH; echo $SINFO; echo $IPINFO`

echo $IPALL >> $TESTLOG
echo $IPALL | curl -X POST -d "$(</dev/stdin)" $URLRDGEO
