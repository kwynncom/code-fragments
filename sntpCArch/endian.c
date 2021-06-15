#include <inttypes.h>
#include <stdio.h>

int isLittleEndian() { // https://stackoverflow.com/questions/12791864/c-program-to-check-little-vs-big-endian
    volatile uint32_t i=0x01234567;
    // return 0 for big endian, 1 for little endian.
    return (*((uint8_t*)(&i))) == 0x67;
}

void main(void){
    printf("Little Endian = %s\n", (isLittleEndian() ? "true" : "false"));
}
