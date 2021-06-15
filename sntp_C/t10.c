#include <math.h> // round()

#include <stdio.h>  // probably just for testing
#include <string.h> // same
#include <stdlib.h> // same - exit()

#include "time.h"

void u32itobes(uint32_t n, unsigned char *b, int o);

void main(void) {
    unsigned char p[48];
    p[0] = '#'; // see PHP version
    int i = 0;
    for(i=0; i <= 39; i++) p[i] = 'x'; // testing only

    uint32_t  s;
    uint32_t ss;
    double    f;
    uint32_t sf;
    unsigned char sb[4];
    int ir;
                           
    uint32_t intf;
    long round1;
    double m10;
    const uint32_t bit_max       = 4294967295;
    const uint32_t epoch_convert = 2208988800;

    timeUFF(&s, &f);

    u32itobes(s + epoch_convert, p, 40);

    m10 = f * bit_max;

//    intf = (uint32_t)round(m10);
//    intf = (uint32_t)round1;

    intf = (uint32_t)lround(f * bit_max);

    u32itobes(intf, p, 44);

//    printf("%d %012u %f %f\n", s, intf, f, m10);

    for (i=0; i < 48; i++) printf("%c", p[i]);
    

}

void u32itobes(uint32_t n, unsigned char *b, int o) {
/* 
bytes[0] = (n >> 24) & 0xFF;
bytes[1] = (n >> 16) & 0xFF;
bytes[2] = (n >> 8) & 0xFF;
bytes[3] = n & 0xFF;
*/
    int ir;
    int i;

    for(i=0; i < 4; i++) {
        ir = 3 - i;
        // ir = i;
        b[ir + o] = (n >> (i * 8)) & 0xff; 
    }


}