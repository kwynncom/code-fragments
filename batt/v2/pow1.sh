find /sys/bus/usb/devices -maxdepth 1 \( -name 'usb*' -o -name '*-*' \) -print0 2>/dev/null |
while IFS= read -r -d '' link; do
    realpath=$(realpath "$link" 2>/dev/null) || continue
    status_file="$realpath/power/runtime_status"
    [[ -f "$status_file" ]] && printf '%s %s\n' "$realpath" "$(<"$status_file")"
done