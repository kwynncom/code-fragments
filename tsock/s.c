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

    if (fread(sntpp, SNTP_PLEN, 1, stdin) != 1) { printf("sntp client package bad size - stdin edition"); exit(1); }    

    if (0) {
    fp = popen("php ./cp.php", "r");
    if (fp == NULL) { printf("Failed to run command\n" ); exit(1);   }
    if (fread(sntpp, SNTP_PLEN, 1, fp) != 1) { printf("sntp client package bad size"); exit(1); }
    pclose(fp);
    }
}

void main() {
    char pack[SNTP_PLEN];
    getPacket(&pack);
    int writer;

    int sock = getBoundSock(0, "127.0.0.1", 123);
    long b = nanotime();
    if (write(sock, pack, SNTP_PLEN) != SNTP_PLEN) { printf("bad write size"); exit(1);}
    if (read (sock, pack, SNTP_PLEN) != SNTP_PLEN) { printf("bad read size"); exit(1);}
    long e = nanotime();

    char outs[SNTP_PLEN + sizeof(b) + sizeof(e)];

    fwrite(&b, sizeof(b), 1, stdout);
    fwrite(&e, sizeof(e), 1, stdout);
    fwrite(&pack, sizeof(pack), 1, stdout);
}
