#define SNTP_PLEN  48
#define NTP_PORT  123
#include <stdint.h>
void u32itobe(uint32_t n, char *pack, int o);
// void u32itoli(uint32_t n, unsigned char *b, int o);
void popSNTPPacket (char *pack);
void sntp_doit(const int n, char *addr);
