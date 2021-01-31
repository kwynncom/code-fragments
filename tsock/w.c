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
	struct sockaddr_in	 servaddr;
	if ( (sockfd = socket(AF_INET, SOCK_DGRAM, 17)) < 0 ) { perror("socket creation failed"); exit(EXIT_FAILURE); }  // 17 is UDP in /etc/protocols

        struct timeval timeout;      
        timeout.tv_sec  = 1;
        timeout.tv_usec = 0;

        if (setsockopt (sockfd, SOL_SOCKET, SO_RCVTIMEO, (char *)&timeout, sizeof(timeout)) < 0) perror("setsockopt failed\n");

	memset(&servaddr, 0, sizeof(servaddr)); 
	
	servaddr.sin_family = AF_INET; 
	servaddr.sin_port = htons(PORT_KWNTP_NS_2021_01_1); 
	// servaddr.sin_addr.s_addr = INADDR_ANY; 

        servaddr.sin_addr.s_addr = inet_addr("34.193.238.16");
	
	const char *tomsg = "t";
	
        long b; long e;
        long r;

        char readb[20];
        int rcvdmsglen;
        int servaddrlen = sizeof(servaddr);

        int i;
        for (i=0; i < 5; i++) {
            b = nanotime();
            sendto(sockfd, tomsg, 2, 0, (const struct sockaddr *) &servaddr, servaddrlen); 
            recvfrom(sockfd, &r, sizeof(r), MSG_WAITALL, (struct sockaddr *) &servaddr, &rcvdmsglen); 
            e = nanotime();

            printf("%ld\n%ld\n\%ld\n", b, r, e); 
        }

	close(sockfd); 
	return 0; 
} 
