BASE=/tmp

while IFS= read line; do
    
    if [ "$line" = "" ]; then
        continue
    fi

    if test -f $line; then
	echo "$line already exists"
	continue
    fi

    if [ -d "$BASE$line" ]; then
	mkdir -p "$BASE$DIR"
    fi

    touch $BASE$line
    

done < ./files.txt

# IFS (Internal Field Separator)
# a newline is one of the default IFS settings
