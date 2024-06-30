FILELS=`pwd`/files.txt
BASE=/tmp

cd $BASE
IAMSHELLRAW=`ps -p $$`
IAMSHELL=`echo $IAMSHELLRAW | awk '{print $NF}'`
echo "This shell that is running is $IAMSHELL"


while IFS= read line; do
    
    THISP=$BASE$line

    if [ "$line" = "" ]; then
        continue
    fi

    if test -f $THISP; then
	echo "$line already exists"
	continue
    fi

    if [ -d "$THISP" ]; then
	mkdir -p "$THISP"
    fi

    touch $THISP
    echo "$THISP created"
    
    

done < $FILELS

# IFS (Internal Field Separator)
# a newline is one of the default IFS settings
