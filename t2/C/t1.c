#include <stdio.h> // FILE
#include <stdio.h> // perror
#include <sys/socket.h> 
#include <arpa/inet.h> 
#include <stdlib.h>
#include <unistd.h> // read, write, close
#include <strings.h> // bzero
#include <string.h> // strcmp
#include <math.h> // round

#define SNPL 48 // SNTP packet length
#define M_BILLION 1000000000
#define M_MILLION 1000000

int getOutboundUDPSock(char *addrStr, int port);
char *getAddr(char *ips);
void setOBPack(char *pack);
void decodeSNTPP(const unsigned char *p, unsigned long *sr, unsigned long *ss);

void main(void) {
	int sock = getOutboundUDPSock(getAddr("::1"), 123);
    unsigned char pack[SNPL];
	unsigned long sr, ss;

	int i;
	for (i=0; i < 1000; i++) {
		setOBPack(pack); // does need to be reset everytime, otherwise bad read: Resource temporarily unavailable
		if (write(sock, pack, SNPL) != SNPL) perror("bad write");
		if (read (sock, pack, SNPL) != SNPL) perror("bad read" );
		if (0) { // results 1
			decodeSNTPP(pack, &sr, &ss);
			printf("%lu\n%lu\n", sr, ss);
		}
		fwrite(pack, SNPL, 1, stdout);
	}
	close(sock);
}

int getOutboundUDPSock(char *addrStr, int port) {

    int sock;
	struct sockaddr_in6 addro; // addr object, more of a PHP convention, but whatever

    bzero(&addro, sizeof(addro));

    addro.sin6_family = AF_INET6; 
	if (inet_pton(AF_INET6, addrStr, &addro.sin6_addr) != 1) { fprintf(stderr, "bad IP address\n"); exit(EXIT_FAILURE); }
    addro.sin6_port = htons(port);

    if ((sock = socket(AF_INET6, SOCK_DGRAM, 17)) < 0) { perror("socket creation failed"); exit(EXIT_FAILURE); }

    struct timeval timeout;      
    timeout.tv_sec  = 1;
    timeout.tv_usec = 0;

    if (setsockopt (sock, SOL_SOCKET, SO_RCVTIMEO, (char *)&timeout, sizeof(timeout)) < 0) perror("setsockopt failed\n");
    if (connect(sock, (struct sockaddr *) &addro, sizeof(addro)) != 0) {  printf("connection with the server failed...\n"); exit(EXIT_FAILURE); } 
        
    return sock;
}

char *getAddr(char *ips) {

	int argl;
	argl = strlen(ips);
	
	if (argl < 3 || argl > 39) // "1.2.3.4" is 7 chars; IPv6 max 39 chars ; "::1" is 3 chars
		{ fprintf(stderr, "bad IP length of %d\n", argl); exit(EXIT_FAILURE);}

	if (strstr(ips, ".") == NULL) return ips;

	if (argl > 15) { fprintf(stderr, "bad IPv4 address - too long\n"); exit(EXIT_FAILURE);}

	const char *ip46p = "::FFFF:";
	const int bsz = strlen(ip46p) + 15 + 1;
	char *sbuf = (char *)malloc(bsz);
	sbuf = strcat(sbuf, ip46p);
	sbuf = strcat(sbuf, ips);

	return sbuf;
}

void setOBPack(char *pack) {
    memcpy(pack    , "#",  1); // SNTP packet header - see readme
    bzero (pack + 1,      47);
}

void decodeSNTPP(const unsigned char *p, unsigned long *sr, unsigned long *ss) {

    const unsigned int UminusNTP = 2208988800;
    const unsigned int full32    = 4294967295; 
    int i = 0;
    int j = 0;
    unsigned int ntps = 0;
    unsigned int U;
    unsigned int fri;
    double fr;
    unsigned long Uns;

    for (j = 0; j < 2; j++) {
        
        U = 0;
        fri = 0;

        for (i=0; i < 4; i++) ntps = ntps | (p[i+32 + j * 8] << (8 * (3 - i)));

        U    = ntps - UminusNTP;

        for (i=0; i < 4; i++) fri = fri | (p[i+36 + j * 8] << (8 * (3 - i)));    

        fr = (double)fri / (double)full32;

        Uns = (unsigned long)U * M_BILLION + (unsigned long)round(fr * M_BILLION);

        if (j == 0) *sr = Uns;
        else        *ss = Uns;
        
        const int ignore = 1;
    } // loop
} // func
