#!/usr/bin/env bash

# usb-human-list-full.sh
# Show *every* USB device and interface with the best human description

printf "%-15s %-8s %-25s %-35s %-20s %s\n" "SYSFS" "VID:PID" "VENDOR" "PRODUCT" "SERIAL" "TYPE"
printf "%s\n" "------------------------------------------------------------------------------------------------------------------------------------"

# Function to get human name from lsusb (fallback)
get_lsusb_name() {
    local vid="$1" pid="$2"
    local line=$(lsusb -d "$vid:$pid" 2>/dev/null)
    if [[ $line =~ ID\ [0-9a-f]{4}:[0-9a-f]{4}\ (.*) ]]; then
        echo "${BASH_REMATCH[1]}"
    else
        echo ""
    fi
}

# Process both devices (X-Y) and interfaces (X-Y:Z.W)
for dev in /sys/bus/usb/devices/*; do
    base=$(basename "$dev")

    # Skip root hubs and non-device entries
    [[ "$base" =~ ^usb[0-9]+$ ]] && continue
    [[ "$base" == "uevent" ]] && continue

    # Read core attributes
    vid=$(cat "$dev/idVendor" 2>/dev/null | tr -d '\n' || echo "")
    pid=$(cat "$dev/idProduct" 2>/dev/null | tr -d '\n' || echo "")

    # Skip if no VID/PID (not a real device)
    [[ -z "$vid" && -z "$pid" ]] && continue

    manufacturer=$(cat "$dev/manufacturer" 2>/dev/null | tr -d '\n' || echo "")
    product=$(cat "$dev/product" 2>/dev/null | tr -d '\n' || echo "")
    serial=$(cat "$dev/serial" 2>/dev/null | tr -d '\n' || echo "—")

    # Fallback: lsusb database
    if [[ -z "$manufacturer" || -z "$product" ]]; then
        lsusb_name=$(get_lsusb_name "$vid" "$pid")
        if [[ -n "$lsusb_name" ]]; then
            if [[ -z "$manufacturer" ]]; then
                manufacturer=$(echo "$lsusb_name" | awk -F', ' '{print $1}')
            fi
            if [[ -z "$product" ]]; then
                product=$(echo "$lsusb_name" | cut -d' ' -f3-)
            fi
        fi
    fi

    # Default fallbacks
    [[ -z "$manufacturer" ]] && manufacturer="Unknown"
    [[ -z "$product" ]] && product="USB Device ($vid:$pid)"

    # Determine type
    type=""

    # Device-level class
    devclass=$(cat "$dev/bDeviceClass" 2>/dev/null | tr -d '\n' || echo "")
    case "$devclass" in
        "00") type="Defined by interface" ;;
        "09") type="USB Hub" ;;
        *) [[ -n "$devclass" ]] && type="Class $devclass" ;;
    esac

    # Interface-level (more accurate for composite devices)
    if [[ "$base" == *:* ]]; then
        iclass=$(cat "$dev/bInterfaceClass" 2>/dev/null | tr -d '\n' || echo "")
        isub=$(cat "$dev/bInterfaceSubClass" 2>/dev/null | tr -d '\n' || echo "")
        iprot=$(cat "$dev/bInterfaceProtocol" 2>/dev/null | tr -d '\n' || echo "")

        case "$iclass" in
            "01") type="Audio" ;;
            "03") type="HID (Keyboard/Mouse)" ;;
            "07") type="Printer" ;;
            "08") type="Mass Storage" ;;
            "0e") type="Video (Webcam)" ;;
            "ff") type="Vendor Specific" ;;
            "e0") type="Bluetooth" ;;
            *) [[ -n "$iclass" ]] && type="Interface Class $iclass" ;;
        esac

        # Special: Video = Webcam
        [[ "$iclass" == "0e" ]] && type="Webcam"
    fi

    # Trim
    manufacturer=${manufacturer:0:24}
    product=${product:0:34}

    printf "%-15s %s:%s %-25s %-35s %-20s %s\n" \
           "$base" "$vid" "$pid" "$manufacturer" "$product" "$serial" "$type"
done | sort