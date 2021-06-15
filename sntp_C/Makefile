sntp : t10.c packet.c time.c
	gcc -o sntp t10.c packet.c time.c -lm

clean : 
	rm *.o sntp

test :
	php test.php ; php test.php
