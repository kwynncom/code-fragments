#include <stdio.h>
#include "common.h"

void main() {
    long t0, t1;
    for (int i=0; i < 10; i++) {
        t0 = nanotime();
        t1 = nanotime();
        printf("%ld\n", t1 - t0);
    }
}
