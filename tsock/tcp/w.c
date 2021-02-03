#include <netdb.h> 
#include <stdio.h> 
#include <stdlib.h> 
#include <string.h> 
#include <sys/socket.h> 
#include <arpa/inet.h>
#include <unistd.h>
#include "./udp/nanotime.h"

#define OUTBUFMAX 5
#define INBUFMAX 25
#define PORT 8124
#define SA struct sockaddr 
void func(int sockfd) 
{ 
    const char smsg = 'd';
    const int  smsgsize = sizeof(smsg);
    int readr, writer;
    long b, e, timer;
    timer = 0;
    char inbuf[INBUFMAX];
    char *outfmt;

    if (smsg == 'd') outfmt = "%ld\n%s%ld\n";
    else             outfmt = "%ld\n%ld\n\%ld\n";

    for (int i=0; i < 20; i++) {
        bzero(inbuf, INBUFMAX); 
        b = nanotime();
        writer = write(sockfd, &smsg, smsgsize);
        if (smsg == 'r') readr = read(sockfd, &timer, sizeof(timer));
        else             readr = read(sockfd, inbuf, INBUFMAX);
        e = nanotime();
        if (smsg == 'r') 
             printf(outfmt, b, timer, e);
        else printf(outfmt, b, inbuf, e);
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
