#!/bin/bash
# read_port.sh
# Usage: sudo ./read_port.sh 11
# Tested on: "Memory at 6001100000 (64-bit, non-prefetchable)"

port=${1:-11}
dev="00:14.0"

echo "Reading xHCI port $port on $dev..."

# --- Extract BAR0 from your exact output ---
BAR0_LINE=$(lspci -s $dev -vvv 2>/dev/null | grep "Region 0: Memory at")
if [ -z "$BAR0_LINE" ]; then
    echo "Error: No Region 0 found. Run: lspci -s $dev -vvv"
    exit 1
fi

# Extract hex number after "at "
BAR0=$(echo "$BAR0_LINE" | grep -o 'at [0-9a-fA-F]*' | awk '{print $2}')
if [ -z "$BAR0" ]; then
    echo "Error: Could not parse BAR0 from: $BAR0_LINE"
    exit 1
fi

# --- Calculate PORTSC address ---
ADDR=$(( 0x$BAR0 + 0x400 + ($port - 1) * 0x10 ))
ADDR_HEX=$(printf "0x%X" $ADDR)

echo "BAR0:   0x$BAR0"
echo "PORTSC: $ADDR_HEX"
echo

# --- Read with devmem2 ---
raw=$(sudo devmem2 $ADDR_HEX w 2>/dev/null | grep "Value" | awk '{print $NF}')
if [ -z "$raw" ]; then
    echo "Failed to read. Install devmem2:"
    echo "  sudo apt install devmem2"
    exit 1
fi

echo "Raw PORTSC: $raw"

# --- Decode ---
val=${raw#0x}
pp=$(( (16#$val >> 9) & 1 ))
pic=$(( (16#$val >> 30) & 3 ))
ccs=$(( 16#$val & 1 ))
ped=$(( (16#$val >> 1) & 1 ))
pls=$(( (16#$val >> 5) & 15 ))

echo "  PP : $pp  → 5V $( ((pp)) && echo ON || echo OFF )"
echo "  PIC: $pic → $( ((pic==0)) && echo 500mA || ((pic==1)) && echo 1.5A || ((pic==2)) && echo 3.0A || echo Reserved )"
echo "  CCS: $ccs → $( ((ccs)) && echo Connected || echo Not-connected )"
echo "  PED: $ped → $( ((ped)) && echo Enabled || echo Disabled )"
echo "  PLS: $pls → $( 
    [ $pls -eq 0 ] && echo U0
    [ $pls -eq 3 ] && echo U3
    [ $pls -eq 5 ] && echo RxDetect
    echo Other
)"