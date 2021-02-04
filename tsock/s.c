#include <stdio.h> // perror
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <stdlib.h> // exit
#include <string.h> // strcmp
#include <strings.h> // bzero
#include <unistd.h> // read, write, close

#include "common.h"

#define SNTP_PLEN 48

void getPacket(char (*sntpp)[SNTP_PLEN]);

void getPacket(char (*sntpp)[SNTP_PLEN]) {
    FILE *fp;

    if (1) {
    fp = popen("php ./cp.php", "r");
    if (fp == NULL) { printf("Failed to run command\n" ); exit(1);   }
    if (fread(sntpp, SNTP_PLEN, 1, fp) != 1) { printf("sntp client package bad size"); exit(1); }
    pclose(fp);
    } else if (fread(sntpp, SNTP_PLEN, 1, stdin) != 1) { printf("sntp client package bad size - stdin edition"); exit(1); }    
}

void main() {
    char pack[SNTP_PLEN];
    int writer;
    long b, e;
    int sock = getBoundSock(0, "34.193.238.16", 123); // 0 means UDP, which it must be for NTP

    for (int i=0; i < 20; i++) {
    getPacket(&pack);
    b = nanotime();
    if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) { printf("bad write size"); exit(1);}
    if (read (sock, pack, SNTP_PLEN) != SNTP_PLEN) { printf("bad read size"); exit(1);}
    e = nanotime();

    fwrite(&b, sizeof(b), 1, stdout);
    fwrite(&e, sizeof(e), 1, stdout);
    fwrite(&pack, sizeof(pack), 1, stdout);
    }
}
