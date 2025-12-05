#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <unistd.h>
#include <fcntl.h>
#include <sys/mman.h>
#include <sys/io.h>

#define PCI_CFG_ADDR 0xCF8
#define PCI_CFG_DATA 0xCFC

uint32_t pci_read(uint32_t addr) {
    outl(addr, PCI_CFG_ADDR);
    return inl(PCI_CFG_DATA);
}

int main(int argc, char **argv) {
    if (argc != 2) {
        printf("Usage: %s <port>\n", argv[0]);
        return 1;
    }
    int port = atoi(argv[1]);
    if (port < 1 || port > 32) return 1;

    if (ioperm(PCI_CFG_ADDR, 8, 1) || ioperm(PCI_CFG_DATA, 4, 1)) {
        perror("ioperm");
        return 1;
    }

    // Read BAR0
    uint32_t addr = 0x80000000 | (0<<16) | (0x14<<11) | (0<<8) | 0x10;
    uint32_t bar = pci_read(addr) & ~0xF;
    if (pci_read(addr + 4)) bar |= 1ULL << 32;  // 64-bit

    // Correct offset: 0x400 + (port-1)*0x10
    uint64_t reg_addr = bar + 0x400 + (port - 1) * 0x10;

    int fd = open("/dev/mem", O_RDONLY);
    if (fd < 0) { perror("open"); return 1; }

    size_t ps = sysconf(_SC_PAGESIZE);
    void *map = mmap(NULL, ps, PROT_READ, MAP_PRIVATE, fd,
                     reg_addr & ~(ps - 1));
    if (map == MAP_FAILED) { perror("mmap"); close(fd); return 1; }

    uint32_t val = *(volatile uint32_t *)((char *)map + (reg_addr & (ps - 1)));
    munmap(map, ps);
    close(fd);

    printf("Port %d: 0x%08X\n", port, val);
    return 0;
}
