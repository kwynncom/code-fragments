#define SNTP_PLEN  48
#define NTP_PORT  123
#include <stdint.h>
void u32itobes(uint32_t n, unsigned char *b, int o);
unsigned char *getSNTPPacket (void);
