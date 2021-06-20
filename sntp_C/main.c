#include <stdio.h>
#include <unistd.h> // read, write, close
#include <stdlib.h> // exit()
#include "sntp.h"
#include "socket.h"
#include "time.h"
#include "config.h"

void sntp_get(int n, int sock, long int *loc, unsigned char *rem);
void sntp_setup();

void main(void) {
	sntp_setup();
}

void sntp_setup() {
    int sock = getBoundSock(KW_DEFAULT_NTP_SERVER);
	unsigned char **packs;
	long int *loc;
	const int n = KW_DEFAULT_NTP_N_POLLS;
	
	packs = (unsigned char **   )malloc(n * sizeof(char *  ));
	loc   = (long int *)malloc(n * sizeof(long int) * 2);

	int i;
	for(i=0; i < n; i++) packs[i] = malloc(SNTP_PLEN);
	for(i=0; i < n; i++) sntp_get(n, sock, loc + i * 2, packs[i]);

    /* fwrite(pack, SNTP_PLEN, 1, stdout);
    fwrite(&b, sizeof(b), 1, stdout);
    fwrite(&e, sizeof(e), 1, stdout); */
}

void sntp_get(int n, int sock, long int *loc, unsigned char *pack) {
    popSNTPPacket(pack);
    loc[0] = nanotime();
    if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) pack[0] = 0;
    if (read (sock, pack, SNTP_PLEN) != SNTP_PLEN) pack[0] = 0;
    loc[1] = nanotime();

}