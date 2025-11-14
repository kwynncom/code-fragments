#!/usr/bin/env bash
# usb-full-path-vid-pid-product.sh
# All /sys/bus/usb/devices/* entries → full sysfs path + VID:PID + product

for dev in /sys/bus/usb/devices/*; do
    base=$(basename "$dev")
    [[ "$base" == "uevent" ]] && continue

    # ----- full sysfs path -------------------------------------------------
    # /sys/bus/usb/devices/1-3.4:1.3  →  /sys/devices/pci…/usb1/1-3.4/1-3.4:1.3
    fullpath=$(realpath "$dev")

    # ----- VID / PID -------------------------------------------------------
    vid=$(cat "$dev/idVendor" 2>/dev/null | tr -d '\n' || echo "")
    pid=$(cat "$dev/idProduct" 2>/dev/null | tr -d '\n' || echo "")
    vidpid="${vid:+$vid:$pid}"
    [[ -z "$vidpid" ]] && vidpid="—"

    # ----- product name ----------------------------------------------------
    product=$(cat "$dev/product" 2>/dev/null | tr -d '\n' || echo "")
    if [[ -z "$product" && -n "$vid" && -n "$pid" ]]; then
        product=$(lsusb -d "$vid:$pid" 2>/dev/null |
                  awk -F': ' '{print $2}' | cut -d' ' -f3-)
    fi
    [[ -z "$product" ]] && product="—"

    # ----- output ----------------------------------------------------------
    printf "%-70s %-12s %s\n" "$fullpath" "$vidpid" "$product"
done