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

#define MAX 20
#define PORT 8080 
#define SA struct sockaddr 
  
void func(int sockfd)  // Function designed for chat between client and server. 
{ 
    char buff[MAX]; 
    int n; 
    int readr, writer;
    for (;;) {      // infinite loop for chat 
        bzero(buff, MAX); 
        readr = read(sockfd, buff, 1); 
        bzero(buff, MAX); 
        long t = nanotime();
        writer = write(sockfd, &t, sizeof(t)); 
    } 
} 
  
// Driver function 
int main() 
{ 
    int sockfd, connfd, len; 
    struct sockaddr_in servaddr, cli; 
  
    // socket create and verification 
    sockfd = socket(AF_INET, SOCK_STREAM, 0); 
    if (sockfd == -1) { 
        printf("socket creation failed...\n"); 
        exit(0); 
    } 

    bzero(&servaddr, sizeof(servaddr)); 
  
    // assign IP, PORT 
    servaddr.sin_family = AF_INET; 
    servaddr.sin_addr.s_addr = htonl(INADDR_ANY); 
    servaddr.sin_port = htons(PORT); 
  
    // Binding newly created socket to given IP and verification 
    if ((bind(sockfd, (SA*)&servaddr, sizeof(servaddr))) != 0) { 
        printf("socket bind failed...\n"); 
        exit(0); 
    } 
  
    // Now server is ready to listen and verification 
    if ((listen(sockfd, 5)) != 0) { 
        printf("Listen failed...\n"); 
        exit(0); 
    } 
    len = sizeof(cli); 
  
    // Accept the data packet from client and verification 
    connfd = accept(sockfd, (SA*)&cli, &len); 
    if (connfd < 0) { 
        printf("server acccept failed...\n"); 
        exit(0); 
    } 
  
    // Function for chatting between client and server 
    func(connfd); 
  
    // After chatting close the socket 
    close(sockfd); 
}