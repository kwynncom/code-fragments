#include <stdio.h> // perror
#include <sys/types.h> // fork
#include <unistd.h>    // fork
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <stdlib.h> // exit
#include <string.h> // strcmp
#include <strings.h> // bzero
#include <time.h>

#include "config.h"

long nanotime();
int getBoundSock(int isTCP);
void io_tcp();

void main() {
    int fpid  = fork();
    char *prots;
    if (fpid) prots = "tcp";
    else      prots = "udp";

    int isTCP = !strcmp(prots, "tcp");

    int sock = getBoundSock(isTCP);
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

int getBoundSock(int isTCP) {
    struct sockaddr_in saddr;
    int sock, type, prot;

    if (!isTCP) { type = SOCK_DGRAM ; prot = 17; }
    else        { type = SOCK_STREAM; prot =  6; }

    bzero(&saddr, sizeof(saddr));
    saddr.sin_family = AF_INET; 
    saddr.sin_addr.s_addr = htonl(INADDR_ANY);
    saddr.sin_port = htons(PORT);

    if ((sock = socket(AF_INET, type, prot)) < 0) { perror("socket creation failed"); exit(EXIT_FAILURE); }
    if (bind(sock, (const struct sockaddr *)&saddr, sizeof(saddr)) < 0) { perror("bind failed"); exit(EXIT_FAILURE); }
    if (isTCP) if ((listen(sock, TCP_CONN_BACKLOG)) != 0) { printf("Listen failed...\n"); exit(0);  } 
        
    return sock;
}

long nanotime() {
    struct timespec sts;
    clock_gettime(CLOCK_REALTIME, &sts);
    return sts.tv_sec * 1000000000 + sts.tv_nsec;
}
