#define SNTP_PLEN  48
#define NTP_PORT  123
#include <stdint.h>
void u32itobe(uint32_t n, unsigned char *b, int o);
void u32itoli(uint32_t n, unsigned char *b, int o);
void popSNTPPacket (unsigned char *pack);
