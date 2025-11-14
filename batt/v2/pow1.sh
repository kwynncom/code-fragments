find /sys/bus/usb/devices -maxdepth 1 \( -name 'usb*' -o -name '*-*' \) -print0 2>/dev/null |
while IFS= read -r -d '' l; do
    r=$(realpath "$l" 2>/dev/null) && [[ -f "$r/power/runtime_status" ]] &&
    printf '%s: %s\n' "$(basename "$l")" "$(<"$r/power/runtime_status")"
done