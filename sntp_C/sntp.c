#include <math.h> // round()
#include <stdlib.h> // malloc()
#include "time.h"
#include "sntp.h"

void popSNTPPacket (unsigned char * pack) {
    const uint32_t bit_max       = 4294967295;
    const uint32_t epoch_convert = 2208988800;
    uint32_t  secs;
    double    frac;
    int i = 0;

    pack [0] = '#'; // see PHP version
    for(i=1; i < SNTP_PLEN; i++) pack[i] = 0;

    timeUFF(&secs, &frac);
    u32itobe(secs + epoch_convert, pack, 24);
    u32itobe((uint32_t)lround(frac * bit_max), pack, 28);
}

void u32itoli(uint32_t n, unsigned char *b, int o) {
    for(int i=0; i < 4; i++) b[i + o] = (n >> ((i) * 8)) & 0xff; 
}

void u32itobe(uint32_t n, unsigned char *b, int o) {
    for(int i=0; i < 4; i++) b[3 - i + o] = (n >> (i * 8)) & 0xff; 
}