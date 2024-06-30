FILELS=`pwd`/files.txt
CREATE=`pwd`/create.sh


cd $BASE
IAMSHELLRAW=`ps -p $$`
IAMSHELL=`echo $IAMSHELLRAW | awk '{print $NF}'`
echo "This shell that is running is $IAMSHELL"


while IFS= read line; do
    
   bash $CREATE $line
    
    

done < $FILELS

# IFS (Internal Field Separator)
# a newline is one of the default IFS settings
