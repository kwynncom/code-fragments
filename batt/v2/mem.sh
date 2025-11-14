#!/bin/bash
# mem.sh — FINAL, WORKS AFTER REBOOT
# Requires: sudo apt install devmem2

port=${1:-11}
dev="00:14.0"

echo "Reading xHCI port $port on $dev..."

# --- 1. Get BAR0 with -vvv ---
BAR0_LINE=$(lspci -s $dev -vvv 2>/dev/null | grep -m1 "Region 0: Memory at")
if [ -z "$BAR0_LINE" ]; then
    echo "ERROR: Run 'lspci -s $dev -vvv' to see BAR0"
    exit 1
fi

# Extract hex after "at"
BAR0=$(echo "$BAR0_LINE" | sed -n 's/.*at \([0-9a-fA-F]*\).*/0x\1/p')
if [ -z "$BAR0" ]; then
    echo "ERROR: Could not parse BAR0 from:"
    echo "  $BAR0_LINE"
    exit 1
fi

# --- 2. Calculate address ---
ADDR=$(( $BAR0 + 0x400 + ($port - 1) * 0x10 ))
ADDR_HEX=$(printf "0x%lX" $ADDR)

echo "BAR0:   $BAR0"
echo "PORTSC: $ADDR_HEX"
echo

# --- 3. Read ---
raw=$(sudo devmem2 $ADDR_HEX w 2>/dev/null | grep "Value" | awk '{print $NF}')
if [ -z "$raw" ]; then
    echo "ERROR: Install devmem2: sudo apt install devmem2"
    exit 1
fi

echo "Raw PORTSC: $raw"
echo

# --- 4. Decode ---
val=${raw#0x}
val=$((16#$val))

ccs=$(( val & 1 ))
ped=$(( (val >> 1) & 1 ))
pp=$(( (val >> 9) & 1 ))
pls=$(( (val >> 5) & 15 ))
pic=$(( (val >> 30) & 3 ))
ps=$(( (val >> 10) & 3 ))

echo "  CCS : $ccs → $( ((ccs)) && echo Connected || echo Not-connected )"
echo "  PED : $ped → $( ((ped)) && echo Enabled || echo Disabled )"
echo "  PP  : $pp  → 5V $( ((pp)) && echo ON || echo OFF )"
echo "  PLS : $pls → $(case $pls in 0) echo U0;; 3) echo U3;; 5) echo RxDetect;; *) echo Other;; esac)"
echo "  PIC : $pic → $(case $pic in 0) echo 500mA;; 1) echo 1.5A;; 2) echo 3.0A;; *) echo Reserved;; esac)"
echo "  Speed: $ps → $(case $ps in 1) echo 1.5Mbps;; 2) echo 480Mbps;; 3) echo 5Gbps;; *) echo Unknown;; esac)"

# --- 5. Latch detection ---
if [ $ccs -eq 1 ] && ! [ -d "/sys/devices/pci0000:00/0000:00:14.0/usb2/2-$port" ]; then
    echo
    echo "HARDWARE LATCH DETECTED"
    echo "  Fix: sudo sh -c 'echo 1 > /sys/kernel/debug/usb/xhci/0000:00:14.0/ports/port$port/reset'"
fi