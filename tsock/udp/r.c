#include <stdio.h> 
#include <stdlib.h> 
#include <unistd.h> 
#include <string.h> 
#include <sys/types.h> 
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <netinet/in.h> 
#include "nanotime.h"
#include "config.h"

int main() { 
    int sockfd; 
    struct sockaddr_in servaddr, cliaddr; 
      
    if ( (sockfd = socket(AF_INET, SOCK_DGRAM, 17)) < 0 ) { perror("socket creation failed"); exit(EXIT_FAILURE); } 
      
    memset(&servaddr, 0, sizeof(servaddr)); 
    memset(&cliaddr, 0, sizeof(cliaddr)); 

    servaddr.sin_family    = AF_INET;
    servaddr.sin_addr.s_addr = INADDR_ANY;
    servaddr.sin_port = htons(PORT_KWNTP_NS_2021_01_1); 
      
    if ( bind(sockfd, (const struct sockaddr *)&servaddr, sizeof(servaddr)) < 0 ) { perror("bind failed"); exit(EXIT_FAILURE); } 
      
    char buffer[2];
    int cliaddrlen;

    long t;
    
    do {
        recvfrom(sockfd, (char *)buffer, 1, 0, ( struct sockaddr *) &cliaddr, &cliaddrlen); 
        t = nanotime();
        sendto(sockfd, (long int *) &t, sizeof(t), 0, (const struct sockaddr *) &cliaddr, cliaddrlen); 
    } while (1);

    close(sockfd); 

    return 0; 
} 
