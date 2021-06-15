#include <stdio.h>
#include <unistd.h> // read, write, close
#include <stdlib.h> // exit()
#include "sntp.h"
#include "socket.h"
#include "time.h"

void main(void) {
    const unsigned char *res = getSNTPPacket();
    char *pack;
    int writer;
    long b, e;
    int sock = getBoundSock("34.193.238.16"); // 0 means UDP, which it must be for NTP

    pack = getSNTPPacket();
    b = nanotime();
    if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) { printf("bad write size"); exit(1);}
    if (read (sock, pack, SNTP_PLEN) != SNTP_PLEN) { printf("bad read size - 140"); exit(1);}
    e = nanotime();

    fwrite(&b, sizeof(b), 1, stdout);
    fwrite(&e, sizeof(e), 1, stdout);
    fwrite(&pack, SNTP_PLEN, 1, stdout);
}
