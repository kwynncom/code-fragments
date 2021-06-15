sntp : main.c sntp.c time.c socket.c
	gcc -o sntp main.c sntp.c time.c socket.c -lm

clean : 
	rm *.o sntp

test :
	php test.php ; php test.php
