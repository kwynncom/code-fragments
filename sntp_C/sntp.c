#include <math.h> // round()
#include <stdlib.h> // malloc()
#include <unistd.h> // read, write, close
#include <stdio.h>  // stdout
#include <string.h> // memset
#include "time.h"
#include "sntp.h"
#include "socket.h"

void sntp_get(int n, int sock, unsigned long *loc, char (*pack)[49]) {
	popSNTPPacket(pack);
        char locbuf[48 + 1];
        const int vck = 2;
    // loc[0] = nanotime();
    if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) ; // not sure I care about write errors

    const int retv = read (sock, locbuf, SNTP_PLEN);

    if (retv != SNTP_PLEN) { 
        memset(pack, 0, SNTP_PLEN); 
        printf("bad packet read"); 
    }

    memcpy(pack, locbuf, 48);
    // loc[1] = nanotime();
    return;

}
void popSNTPPacket ( char (*pack)[49]) {
    const uint32_t bit_max       = 4294967295;
    const uint32_t epoch_convert = 2208988800;
    uint32_t  secs;
    double    frac;
    int i = 0;

    memcpy(pack, "#", 1); // see PHP version; ord('#') == 0x23
    memset(pack + 1, 0, SNTP_PLEN - 1);

    timeUFF(&secs, &frac);
    u32itobe(secs + epoch_convert, pack, 24);
    u32itobe((uint32_t)lround(frac * bit_max), pack, 28);
}

void u32itoli(uint32_t n, unsigned char *b, int o) {
    for(int i=0; i < 4; i++) b[i + o] = (n >> ((i) * 8)) & 0xff; 
}

void u32itobe(uint32_t n,char (*b)[49], int o) {
    char v;
    for(int i=0; i < 4; i++) {
        v =  (n >> (i * 8)) & 0xff;
        memcpy(b + 3 - i + o, &v, 1); 
    }
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
		packs[i] = (char *)malloc(SNTP_PLEN);
		memset(packs[i], 0, SNTP_PLEN);
	}	

        char tmpbuf[49];
	for(i=0; i < n; i++) {
            sntp_get(n, sock, locs + i * 2, &tmpbuf);
            }

	for(i=0; i < n; i++) { // does not actually loop at the moment
                const int len = sizeof(packs + i);
		fwrite(tmpbuf, SNTP_PLEN, 1, stdout);
		// fwrite(locs + i * 2	   , sizeof(unsigned long), 1, stdout);
		// fwrite(locs + i * 2 + 1, sizeof(unsigned long), 1, stdout);
	}

        exit(0);
}
