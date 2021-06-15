#include <time.h>   // timespec struct
#include <stdlib.h> // exit()

void timeUFF(unsigned long *u, double *fr) {
// time_t tv_sec	whole seconds (valid values are >= 0)
// long   tv_nsec	nanoseconds (valid values are [0, 999999999])

    struct timespec sts;
    int cgr = clock_gettime(CLOCK_REALTIME, &sts);
    if (cgr != 0) exit(cgr);

    *u  = (unsigned long)      sts.tv_sec;                      // 123456789
    *fr = (double) (((double)  sts.tv_nsec) / (double) 1000000000);
}
