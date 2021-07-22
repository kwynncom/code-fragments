#include <stdio.h> // perror
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <stdlib.h> // exit
#include <string.h> // strcmp
#include <strings.h> // bzero

int getUDPSock(char *outaddr, int port) {

	struct sockaddr_in6 saddr;
    int sock, type, prot;

    bzero(&saddr, sizeof(saddr));

    saddr.sin6_family = AF_INET6; 
	if (inet_pton(AF_INET6, outaddr, &saddr.sin6_addr) != 1) { fprintf(stderr, "bad IP address\n"); exit(EXIT_FAILURE); }
    saddr.sin6_port = htons(port);

    type = SOCK_DGRAM; 
	prot = 17;

    if ((sock = socket(AF_INET6, type, prot)) < 0) { perror("socket creation failed"); exit(EXIT_FAILURE); }

    struct timeval timeout;      
    timeout.tv_sec  = 1;
    timeout.tv_usec = 0;

    if (setsockopt (sock, SOL_SOCKET, SO_RCVTIMEO, (char *)&timeout, sizeof(timeout)) < 0) perror("setsockopt failed\n");
    if (connect(sock, (struct sockaddr *) &saddr, sizeof(saddr)) != 0) {  printf("connection with the server failed...\n"); exit(EXIT_FAILURE); } 
        
    return sock;
}
