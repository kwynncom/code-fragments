#!/usr/bin/env bash

# via Grok Fast mode, 2025/11/14 00:09 EST
# usb-human-list.sh
# Show human-readable USB device list with best possible names

printf "%-12s %-25s %-30s %-20s %s\n" "SYSFS" "VENDOR" "PRODUCT" "SERIAL" "TYPE"
printf "%s\n" "---------------------------------------------------------------------------------------------"

for dev in /sys/bus/usb/devices/*-*; do
    [ -f "$dev/idVendor" ] || continue

    # Core IDs
    vid=$(cat "$dev/idVendor" 2>/dev/null | tr -d '\n')
    pid=$(cat "$dev/idProduct" 2>/dev/null | tr -d '\n')
    sysname=$(basename "$dev")

    # Human strings (fallback chain)
    manufacturer=$(cat "$dev/manufacturer" 2>/dev/null | tr -d '\n' || echo "")
    product=$(cat "$dev/product" 2>/dev/null | tr -d '\n' || echo "")
    serial=$(cat "$dev/serial" 2>/dev/null | tr -d '\n' || echo "—")

    # Fallback: use lsusb database if no kernel strings
    if [ -z "$manufacturer" ] || [ -z "$product" ]; then
        lsusb_line=$(lsusb -s "${sysname/*:/}" 2>/dev/null || echo "")
        if [[ $lsusb_line =~ ID\ ([0-9a-f]{4}):([0-9a-f]{4})\ (.*) ]]; then
            ls_vid=${BASH_REMATCH[1]}
            ls_pid=${BASH_REMATCH[2]}
            ls_rest=${BASH_REMATCH[3]}
            if [ "$ls_vid" = "$vid" ] && [ "$ls_pid" = "$pid" ]; then
                if [ -z "$manufacturer" ]; then
                    manufacturer=$(echo "$ls_rest" | awk -F', ' '{print $1}')
                fi
                if [ -z "$product" ]; then
                    product=$(echo "$ls_rest" | cut -d' ' -f3-)
                fi
            fi
        fi
    fi

    # Default fallbacks
    [ -z "$manufacturer" ] && manufacturer="Unknown Vendor"
    [ -z "$product" ] && product="USB Device ($vid:$pid)"

    # Device type (from interface or device class)
    devclass=$(cat "$dev/bDeviceClass" 2>/dev/null | tr -d '\n' || echo "")
    case "$devclass" in
        "00") type="Defined by interface" ;;
        "02") type="Communications" ;;
        "03") type="HID (Keyboard/Mouse)" ;;
        "07") type="Printer" ;;
        "08") type="Mass Storage" ;;
        "09") type="USB Hub" ;;
        "0e") type="Video (Webcam)" ;;
        "ff") type="Vendor Specific" ;;
        *) type="Class $devclass" ;;
    esac

    # Check first interface for better type
    iface_dir=$(ls -d "$dev"/"$sysname":* 2>/dev/null | head -1)
    if [ -n "$iface_dir" ]; then
        iclass=$(cat "$iface_dir/bInterfaceClass" 2>/dev/null || echo "")
        case "$iclass" in
            "01") type="Audio" ;;
            "03") type="HID Input" ;;
            "08") type="Storage" ;;
            "0a") type="CDC Data" ;;
            "e0") type="Wireless (Bluetooth)" ;;
            "ff") type="Vendor Interface" ;;
        esac
    fi

    # Trim long names
    manufacturer=${manufacturer:0:24}
    product=${product:0:29}

    printf "%-12s %-25s %-30s %-20s %s\n" \
           "$sysname" "$manufacturer" "$product" "$serial" "$type"
done
