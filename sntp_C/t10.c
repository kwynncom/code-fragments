#include <math.h> // round()
#include <stdio.h>  // probably just for testing
#include "time.h"

void u32itobes(uint32_t n, unsigned char *b, int o);

void main(void) {
    const uint32_t bit_max       = 4294967295;
    const uint32_t epoch_convert = 2208988800;
    unsigned char pack[48];
    uint32_t  secs;
    double    frac;
    int i = 0;

    pack[0] = '#'; // see PHP version
    for(i=0; i <= 39; i++) pack[i] = 'x'; // **!*!*! use xs for testing only 

    timeUFF(&secs, &frac);
    u32itobes(secs + epoch_convert, pack, 40);
    u32itobes((uint32_t)lround(frac * bit_max), pack, 44);
    for (i=0; i < 48; i++) printf("%c", pack[i]);
}

void u32itobes(uint32_t n, unsigned char *b, int o) {
    for(int i=0; i < 4; i++) b[3 - i + o] = (n >> (i * 8)) & 0xff; 
}