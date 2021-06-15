#include <stdio.h>
#include "time.h"

void main(void) {
    uint32_t s;
    double   f;
    
    timeUFF(&s, &f);

    printf("%d %0.9f\n", s, f);
}
