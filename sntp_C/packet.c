#include <math.h> // round()
#include <stdlib.h> // malloc()
#include "time.h"
#include "packet.h"

unsigned char *getSNTPPacket (void) {
    const uint32_t bit_max       = 4294967295;
    const uint32_t epoch_convert = 2208988800;
    unsigned char *pack;
    uint32_t  secs;
    double    frac;
    int i = 0;

    pack = (unsigned char *)malloc(SNTP_PLEN);

    pack[0] = '#'; // see PHP version
    for(i=0; i <= 39; i++) pack[i] = 0;

    timeUFF(&secs, &frac);
    u32itobes(secs + epoch_convert, pack, 40);
    u32itobes((uint32_t)lround(frac * bit_max), pack, 44);
    return pack;
}

void u32itobes(uint32_t n, unsigned char *b, int o) {
    for(int i=0; i < 4; i++) b[3 - i + o] = (n >> (i * 8)) & 0xff; 
}