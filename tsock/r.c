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

#define MAX 2
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
  
    char buff[MAX]; 
    int n; 
    int readr, writer;
    long t;

    len = sizeof(cli); 
    if ((listen(sockfd, 5)) != 0) { printf("Listen failed...\n"); exit(0);  } 

    while(1) {

       connfd = accept(sockfd, (SA*)&cli, &len); 
       if (connfd < 0) { printf("server acccept failed...\n"); exit(0);   } 

       if (!fork()) {
        close(sockfd); 
       while (1) {
        bzero(buff, MAX); 
        readr = read(connfd, buff, 2); 
        t = nanotime();
        writer = write(connfd, &t, sizeof(t)); 

        }

        close(connfd);
        } else close(connfd);
    } 

    close(sockfd); 
}