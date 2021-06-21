#include <math.h> // round()
#include <stdlib.h> // malloc()
#include <unistd.h> // read, write, close
#include <stdio.h>  // stdout
#include <string.h> // memset
#include "time.h"
#include "sntp.h"
#include "socket.h"

void sntp_get(int n, int sock, unsigned long *loc, char *pack) {
    // popSNTPPacket(pack);
    loc[0] = nanotime();
    if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) ; // not sure I care about write errors
    if (read (sock, pack, SNTP_PLEN) != SNTP_PLEN) { memset(pack, 0, SNTP_PLEN); printf("read error");}
    loc[1] = nanotime();
}
void popSNTPPacket (char *pack) {
    const uint32_t bit_max       = 4294967295;
    const uint32_t epoch_convert = 2208988800;
    uint32_t  secs;
    double    frac;
    int i = 0;

    pack[0] = '#'; // see PHP version; 0x23

    timeUFF(&secs, &frac);
    u32itobe(secs + epoch_convert, pack, 24);
    u32itobe((uint32_t)lround(frac * bit_max), pack, 28);
}

void u32itoli(uint32_t n, unsigned char *b, int o) {
    for(int i=0; i < 4; i++) b[i + o] = (n >> ((i) * 8)) & 0xff; 
}

void u32itobe(uint32_t n,char *b, int o) {
    for(int i=0; i < 4; i++) b[3 - i + o] = (n >> (i * 8)) & 0xff; 
}

void sntp_doit(const int n, char *addr) {
    int sock = getBoundSock(addr);
	char **packs;
	unsigned long *locs;
	
	packs = malloc(n * sizeof(char *  ));
	int locsz = sizeof(unsigned long) * 2;
	const int locssz = n * locsz;
	locs   = (unsigned long  *)malloc(locssz);

	int i;
	for(i=0; i < n; i++) {
		packs[i] = malloc(SNTP_PLEN);
		memset(packs[i], 'x', SNTP_PLEN);
	}	

	for(i=0; i < n; i++) sntp_get(n, sock, locs + i * 2, packs[i]);

	for(i=0; i < n; i++) {
		fwrite(packs + i, SNTP_PLEN, 1, stdout);
		// fwrite(locs + i * 2	   , sizeof(unsigned long), 1, stdout);
		// fwrite(locs + i * 2 + 1, sizeof(unsigned long), 1, stdout);
	}
}


