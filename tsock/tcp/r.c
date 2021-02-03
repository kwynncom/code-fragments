#include <stdio.h> 
#include <netdb.h> 
#include <netinet/in.h> 
#include <stdlib.h> 
#include <string.h> 
#include <sys/socket.h> 
#include <sys/types.h>
#include <arpa/inet.h>
#include <unistd.h>
#include "./udp/nanotime.h"

#define OUTBUFMAX 25
#define PORT 8124 
#define SA struct sockaddr 
  
int main() 
{ 
    int sockfd, connfd, len; 
    struct sockaddr_in servaddr, cli; 
  
    sockfd = socket(AF_INET, SOCK_STREAM, 0); 
    if (sockfd == -1) { 
        printf("socket creation failed...\n"); 
        exit(0); 
    } 

    bzero(&servaddr, sizeof(servaddr)); 
    servaddr.sin_family = AF_INET; 
    servaddr.sin_addr.s_addr = htonl(INADDR_ANY); 
    servaddr.sin_port = htons(PORT); 
  
    if ((bind(sockfd, (SA*)&servaddr, sizeof(servaddr))) != 0) { 
        printf("socket bind failed...\n"); 
        exit(0); 
    } 
  
    char inbuf;
    int inbufsz = sizeof(inbuf);
    int n; 
    int readr, writer;
    long t;

    len = sizeof(cli); 
    if ((listen(sockfd, 5)) != 0) { printf("Listen failed...\n"); exit(0);  } 

    char outbufs[25];

    int sizet = sizeof(t);

    while(1) {

       connfd = accept(sockfd, (SA*)&cli, &len); 
       if (connfd < 0) { printf("server acccept failed...\n"); exit(0);   } 

       if (!fork()) {
        close(sockfd); 
       while (1) {
        bzero(outbufs, OUTBUFMAX); 
        readr = read(connfd, &inbuf, inbufsz);
        t = nanotime();
        if (inbuf == 'r') writer = write(connfd, &t, sizet); 
        else {
            sprintf(outbufs, "%ld\n", t);
            writer = write(connfd, outbufs, strlen(outbufs));
            
        }

        }

        close(connfd);
        } else close(connfd);
    } 

    close(sockfd); 
}