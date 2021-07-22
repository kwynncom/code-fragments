#include <string.h> // memset, memcpy
#include <unistd.h> // read, write, close
#include <stdio.h>  // stdout
#include "config.h"
#include "socket.h"
#include "time.h"


#define SNTP_PLEN 48
void main(void) {
	
	char pack[SNTP_PLEN];
	int sock = getUDPSock(KW_DEFAULT_NTP_SERVER_IP4, 123);
	unsigned long b, e;
	
    memcpy(pack    , "#", 1 ); // see PHP version; ord('#') == 0x23	
	bzero(pack + 1, 47);
	
	b = nanotime();
	if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) perror("bad write");
    if (read (sock, pack, SNTP_PLEN) != SNTP_PLEN) perror("bad read");
	e = nanotime();

	fwrite(&pack, SNTP_PLEN, 1, stdout);
	fwrite(&b	, sizeof(b), 1, stdout);
	fwrite(&e	, sizeof(e), 1, stdout);

	close(sock);

}