sntp : main.c sntp.c time.c socket.c
	gcc -g -o sntp main.c sntp.c time.c socket.c -lm

clean : 
	rm *.o sntp

test :
	php wrap.php ; php wrap.php
