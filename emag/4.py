import numpy as np
import matplotlib.pyplot as plt

# Time array: 0 to 100 µs (10 symbols at 10 µs each), 1000 points
t = np.linspace(0, 100e-6, 1000)
symbol_duration = 10e-6  # 10 µs per symbol (100 kbaud)
points_per_symbol = int(len(t) / 10)  # 100 points per symbol
carrier_freq = 1e6  # 1 MHz carrier (10 cycles/symbol)

# Define QAM symbols
# 4-QAM: 4 phases, amplitude = 1
qam4_symbols = [
    (1, 0),          # 00: 0°
    (1, np.pi/2),    # 01: 90°
    (1, np.pi),      # 11: 180°
    (1, 3*np.pi/2),  # 10: 270°
    (1, 0),          # 00
    (1, np.pi/2),    # 01
    (1, np.pi),      # 11
    (1, 3*np.pi/2),  # 10
    (1, 0),          # 00
    (1, np.pi/2)     # 01
]

# 16-QAM: Amplitude (1, 3) and phases (0°, 45°, 90°, 135°, etc.), subset of 10
qam16_symbols = [
    (1, 0),          # 0000: +1, 0°
    (3, np.pi/4),    # 1111: +3, 45°
    (1, np.pi/2),    # 0100: +1, 90°
    (3, np.pi),      # 1011: +3, 180°
    (1, 3*np.pi/4),  # 0001: +1, 135°
    (3, np.pi/2),    # 1100: +3, 90°
    (1, np.pi),      # 0011: +1, 180°
    (3, 0),          # 1000: +3, 0°
    (1, np.pi/4),    # 0101: +1, 45°
    (3, 3*np.pi/4)   # 1110: +3, 135°
]

# Generate signals
signal_qam4 = np.zeros(len(t))
signal_qam16 = np.zeros(len(t))
for i, (amp, phase) in enumerate(qam4_symbols):
    start = i * points_per_symbol
    end = (i + 1) * points_per_symbol
    signal_qam4[start:end] = amp * np.sin(2 * np.pi * carrier_freq * t[start:end] + phase)
for i, (amp, phase) in enumerate(qam16_symbols):
    start = i * points_per_symbol
    end = (i + 1) * points_per_symbol
    signal_qam16[start:end] = amp * np.sin(2 * np.pi * carrier_freq * t[start:end] + phase)

# Plotting
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(12, 8), sharex=True)

# 4-QAM plot
ax1.plot(t * 1e6, signal_qam4, 'b')
for i in range(1, 10):
    ax1.axvline(x=i * 10, color='gray', linestyle='--', alpha=0.5)
ax1.set_title("4-QAM (QPSK) at 100 kbaud, 1 MHz Carrier (200 kbps)")
ax1.set_ylabel("Amplitude (V)")
ax1.grid(True)
ax1.set_ylim(-2, 2)
for i, (amp, phase) in enumerate(qam4_symbols):
    ax1.text(i * 10 + 5, 1.5, f"{int(np.degrees(phase))}°", ha='center', fontsize=8)

# 16-QAM plot
ax2.plot(t * 1e6, signal_qam16, 'r')
for i in range(1, 10):
    ax2.axvline(x=i * 10, color='gray', linestyle='--', alpha=0.5)
ax2.set_title("16-QAM at 100 kbaud, 1 MHz Carrier (400 kbps)")
ax2.set_xlabel("Time (µs)")
ax2.set_ylabel("Amplitude (V)")
ax2.grid(True)
ax2.set_ylim(-4, 4)
for i, (amp, phase) in enumerate(qam16_symbols):
    ax2.text(i * 10 + 5, 3.5, f"{amp}, {int(np.degrees(phase))}°", ha='center', fontsize=8)

# Finalize
plt.suptitle("QAM Symbols Over Time", fontsize=14)
plt.tight_layout(rect=[0, 0, 1, 0.95])
plt.show()
