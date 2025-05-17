#include <stdio.h>
#include <time.h>

// code by Grok, but I have done all of this myself before.

// Grok's second suggestion, which I would not quickly have come up with, was:
// gcc -O3 -march=native -flto add.c
// That gets 262 - 362 ns
// then a handful of samples from 53 - 66 

/* $ gcc -O3 add.c
   $ ./a.out */
// between 239 - 550 ns
// the sum had better always be 3128

int main() {
    int durations[137] = {
        4, 37, 11, 6, 34, 13, 15, 2, 37, 30, 40, 14, 4, 42, 8, 20, 3, 16, 26, 49,
        33, 9, 43, 2, 7, 23, 1, 32, 68, 54, 29, 28, 15, 2, 20, 10, 0, 3, 21, 6,
        6, 13, 16, 18, 19, 9, 13, 9, 27, 4, 46, 12, 39, 26, 4, 7, 8, 10, 2, 15,
        44, 58, 38, 11, 7, 73, 11, 2, 7, 8, 4, 15, 18, 7, 11, 29, 24, 5, 15, 7,
        3, 3, 6, 16, 14, 11, 5, 12, 14, 23, 23, 19, 14, 2, 32, 18, 45, 5, 32, 8,
        47, 25, 23, 10, 32, 177, 38, 4, 3, 20, 96, 63, 53, 40, 20, 4, 30, 189, 58, 1,
        10, 1, 1, 16, 21, 10, 16, 12, 29, 17, 35, 6, 40, 11, 5, 8, 138
    };

    // Variables for timing
    struct timespec start, end;
    long long sum = 0;
    int i;

    // Get start time (nanosecond precision)
    clock_gettime(CLOCK_MONOTONIC, &start);

    // Sum the 137 durations
    for (i = 0; i < 137; i++) {
        sum += durations[i];
    }

    // Get end time
    clock_gettime(CLOCK_MONOTONIC, &end);

    // Calculate elapsed time in nanoseconds
    long long elapsed_ns = (end.tv_sec - start.tv_sec) * 1000000000LL + (end.tv_nsec - start.tv_nsec);

    // Print results
    printf("Sum of 137 integers: %lld \n", sum);
    printf("Elapsed time: %lld nanoseconds\n", elapsed_ns);

    return 0;
}
