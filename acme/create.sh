BASE=/tmp

if [ "$1" = "" ]; then
    exit
fi

THISP=$BASE$1

if test -f $THISP; then
    echo  "$THISP already exists"
    exit
fi

if [ -d "$THISP" ]; then
    mkdir -p "$THISP"
fi

touch $THISP
echo "$THISP created"
