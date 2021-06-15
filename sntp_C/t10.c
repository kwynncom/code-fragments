#include <stdio.h>
#include "packet.h"
void main(void) {
    const unsigned char *res = getSNTPPacket();
    for (int i=0; i < SNTP_PLEN; i++) printf("%c", res[i]);
}