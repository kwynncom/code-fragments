TIMEOUT_SECONDS=20
COMMAND=(udevadm monitor -s usb)

KEYWORDS=("add" "remove")

timeout --foreground "$TIMEOUT_SECONDS" "${COMMAND[@]}" |
while IFS= read -r line; do
    lowered="${line,,}"
    for kw in "${KEYWORDS[@]}"; do
        if [[ "$lowered" == *"${kw,,}"* ]]; then
            echo "FOUND: $kw"
            echo "$line"
            exit 1
        fi
    done
done

echo "TIMEOUT after ${TIMEOUT_SECONDS}s"
