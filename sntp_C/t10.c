#include "time.h"
void main(void) {
    unsigned char p[48];
    p[0] = '#'; // see PHP version
    int i = 0;
    for(i=0; i <= 39; i++) p[i] = 0;

    uint32_t  s;
    uint32_t ss;
    double    f;
    uint32_t sf;
    unsigned char sb[4];
    int ir;

    timeUFF(&s, &f);

    for(i=0; i < 4; i++) {
        ir = 3 - i;
        sb[ir] = (sf >> ir) & 0xff; 
    }

/* 
bytes[0] = (n >> 24) & 0xFF;
bytes[1] = (n >> 16) & 0xFF;
bytes[2] = (n >> 8) & 0xFF;
bytes[3] = n & 0xFF;
*/
    

}

void u32itobes(uint32_t n, unsigned char *b, int o) {

}