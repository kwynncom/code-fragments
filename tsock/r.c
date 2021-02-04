#include <stdio.h> // perror
#include <sys/types.h> // fork
#include <unistd.h>    // fork
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <stdlib.h> // exit
#include <string.h> // strcmp
#include <strings.h> // bzero

#include "config.h"
#include "common.h"

void main() {
    int fpid  = fork();
    char *prots;
    if (fpid) prots = "tcp";
    else      prots = "udp";

    int isTCP = !strcmp(prots, "tcp");

    int sock = getBoundSock(isTCP, "");
    struct sockaddr_in caddr; // only UDP uses
    int caddrsz = sizeof(caddr); // same
    char inbuf[3];
    int inbufsz = sizeof(inbuf);
    int outbufssz = 30;
    char outbufs[outbufssz];
    int readr, connfd, writer;
    long t;
    int sizet = sizeof(t);
 
    if (isTCP) {
        while(1) {
            if ((connfd = accept(sock, (struct sockaddr  *)&caddr, &caddrsz)) < 0) { printf("server acccept failed...\n"); exit(1);   } 
            if (!fork()) {
                close(sock); // child process does not need socket
                while (1) {
                    bzero(outbufs, outbufssz); 
                    readr = read(connfd, &inbuf, inbufsz);
                    t = nanotime();
                    if (inbuf[0] == 'r') writer = write(connfd, &t, sizet); 
                    else {
                        sprintf(outbufs, "%ld\n", t);
                        writer = write(connfd, outbufs, strlen(outbufs));
                    }
                } // while inner
                close(connfd);
            } // if !fork
            else close(connfd);
        } // while outer
    } // TCP
    else {
        do {
            recvfrom(sock, &inbuf, inbufsz, 0 /* flags */, ( struct sockaddr *) &caddr, &caddrsz); 
            t = nanotime();
            if (inbuf[0] == 'r') sendto(sock, &t, sizet, 0, (const struct sockaddr *) &caddr, caddrsz); 
            else {
                sprintf(outbufs, "%ld\n", t);
                writer = sendto(sock, outbufs, strlen(outbufs), 0, (const struct sockaddr *) &caddr, caddrsz); 
            }         
        } while (1);
    }

    close(sock);
}
