#include <stdio.h> 
#include <stdlib.h> 
#include <unistd.h> 
#include <string.h> 
#include <sys/types.h> 
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <netinet/in.h> 
#include "nanotime.h"

#define PORT	 8080 

int main() { 
	int sockfd; 
	struct sockaddr_in	 servaddr; 
	if ( (sockfd = socket(AF_INET, SOCK_DGRAM, 17)) < 0 ) { perror("socket creation failed"); exit(EXIT_FAILURE); }  // 17 is UDP in /etc/protocols
	memset(&servaddr, 0, sizeof(servaddr)); 
	
	servaddr.sin_family = AF_INET; 
	servaddr.sin_port = htons(PORT); 
	servaddr.sin_addr.s_addr = INADDR_ANY; 
	
	int n, len;
	char *tomsg = "t"; 
        long int *buf;
        char readb[20];
	
        long b; long e;

        b = nanotime();
	sendto(sockfd, (const char *)tomsg, 2, 
		0, (const struct sockaddr *) &servaddr, 
			sizeof(servaddr)); 

	n = recvfrom(sockfd, &readb, 20, 
				MSG_WAITALL, (struct sockaddr *) &servaddr, 
                		&len); 
        e = nanotime();
      

	printf("%ld\n%s\n\%ld\n", b, readb, e); 

	close(sockfd); 
	return 0; 
} 
