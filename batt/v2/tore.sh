find /sys/devices/pci0000:00/0000:00:14.0 -name "delete" -exec sh -c '
    path=$(dirname "$1")
    port=$(echo "$path" | grep -o "1-[0-9]\+" || echo "$path" | grep -o "2-[0-9]\+")
    if [ -n "$port" ]; then
        echo "Port: $port → $path/delete"
    fi
' _ {} \;