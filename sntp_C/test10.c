#include <stdio.h>
#include "time.h"
void main(void) {
    unsigned long s;
    double fr;
    
    timeUFF(&s, &fr);

    printf("%lu %0.9lf\n", s, fr);
}
