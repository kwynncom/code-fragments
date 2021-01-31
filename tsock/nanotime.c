#include <time.h>

long nanotime() {
    struct timespec sts;
    clock_gettime(CLOCK_REALTIME, &sts);
    return sts.tv_sec * 1000000000 + sts.tv_nsec;
}
