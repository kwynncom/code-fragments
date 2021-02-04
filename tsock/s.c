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

    fp = popen("php ./cp.php", "r");
    if (fp == NULL) { printf("Failed to run command\n" ); exit(1);   }
    if (fread(sntpp, SNTP_PLEN, 1, fp) != 1) { printf("sntp client package bad size"); exit(1); }
    pclose(fp);
}

void main() {
    char outpack[SNTP_PLEN];
    getPacket(&outpack);
    int writer;

    int sock = getBoundSock(0, "127.0.0.1", 123);
    if (write(sock, &outpack, SNTP_PLEN) != SNTP_PLEN) { printf("bad write size"); exit(1);}


}
