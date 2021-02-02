#include <netdb.h> 
#include <stdio.h> 
#include <stdlib.h> 
#include <string.h> 
#include <sys/socket.h> 
#include <arpa/inet.h>
#include <unistd.h>
#include "./udp/nanotime.h"

#define MAX 2
#define PORT 8124
#define SA struct sockaddr 
void func(int sockfd) 
{ 
    char buff[MAX]; 
    buff[0] = 't';
    buff[1] = '\0';
    int readr, writer;
    long b, e, timer;

    timer = 0;

    for (int i=0; i < 20; i++) { 
        b = nanotime();
        writer = write(sockfd, buff, sizeof(buff)); 
        readr  = read(sockfd, &timer, sizeof(timer)); 
        e = nanotime();
        printf("%ld\n%ld\n\%ld\n", b, timer, e); 
    } 
} 
  
int main() 
{ 
    int sockfd, connfd; 
    struct sockaddr_in servaddr, cli; 
  
    // socket create and varification 
    sockfd = socket(AF_INET, SOCK_STREAM, 0); 
    if (sockfd == -1) { 
        printf("socket creation failed...\n"); 
        exit(0); 
    } 
    bzero(&servaddr, sizeof(servaddr)); 
  
    // assign IP, PORT 
    servaddr.sin_family = AF_INET; 
    servaddr.sin_addr.s_addr = inet_addr("127.0.0.1"); 
    // servaddr.sin_addr.s_addr = inet_addr("34.193.238.16"); 
    servaddr.sin_port = htons(PORT); 
  
    // connect the client socket to server socket 
    if (connect(sockfd, (SA*)&servaddr, sizeof(servaddr)) != 0) { 
        printf("connection with the server failed...\n"); 
        exit(0); 
    } 
  
    // function for chat 
    func(sockfd); 
  
    // close the socket 
    close(sockfd); 
}
