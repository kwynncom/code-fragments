import numpy as np
import matplotlib.pyplot as plt

# Time array: 0 to 500 ns (5 symbols at 100 ns each), high resolution
t = np.linspace(0, 500e-9, 5000)  # 5000 points for smooth curves

# Frequencies and symbol timing
f1, f2 = 100e6, 12e9  # 100 MHz, 12 GHz
symbol_duration = 100e-9  # 100 ns per symbol (10 Mbaud)
points_per_symbol = int(len(t) / 5)  # 1000 points per 100 ns

# 4-QAM (QPSK) symbols: constant amplitude (1), four phase shifts
# Mapping: 0°, 90°, 180°, 270° for "00", "01", "11", "10"
symbols = [
    (1, 0),          # 0°   (e.g., "00")
    (1, np.pi/2),    # 90°  (e.g., "01")
    (1, np.pi),      # 180° (e.g., "11")
    (1, 3*np.pi/2),  # 270° (e.g., "10")
    (1, 0)           # Back to 0° (e.g., "00")
]

# Generate signals with symbol transitions
signal1 = np.zeros(len(t))  # 100 MHz
signal2 = np.zeros(len(t))  # 12 GHz
for i, (amp, phase) in enumerate(symbols):
    start = i * points_per_symbol
    end = (i + 1) * points_per_symbol
    signal1[start:end] = amp * np.sin(2 * np.pi * f1 * t[start:end] + phase)
    signal2[start:end] = amp * np.sin(2 * np.pi * f2 * t[start:end] + phase)

# Add noise (20 dB SNR approximation)
noise_amplitude = 0.1  # Signal power ~100x noise power
noise1 = noise_amplitude * np.random.randn(len(t))
noise2 = noise_amplitude * np.random.randn(len(t))
noisy_signal1 = signal1 + noise1
noisy_signal2 = signal2 + noise2

# Plotting
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(12, 8), sharex=True)

# 100 MHz plot
ax1.plot(t * 1e9, noisy_signal1, 'b', label='Noisy Signal', alpha=0.7)
ax1.plot(t * 1e9, signal1, 'k--', alpha=0.3, label='Clean Signal')
for i in range(1, 5):
    ax1.axvline(x=i * 100, color='gray', linestyle='--', alpha=0.5)
ax1.set_title("100 MHz Carrier, 10 Cycles/Symbol, 10 Mbaud (4-QAM, 20 Mbps)")
ax1.set_ylabel("Amplitude (V)")
ax1.legend(loc="upper right")
ax1.grid(True)
ax1.set_ylim(-2, 2)  # Fixed amplitude range

# 12 GHz plot
ax2.plot(t * 1e9, noisy_signal2, 'r', label='Noisy Signal', alpha=0.7)
ax2.plot(t * 1e9, signal2, 'k--', alpha=0.3, label='Clean Signal')
for i in range(1, 5):
    ax2.axvline(x=i * 100, color='gray', linestyle='--', alpha=0.5)
ax2.set_title("12 GHz Carrier, 1200 Cycles/Symbol, 10 Mbaud (4-QAM, 20 Mbps)")
ax2.set_xlabel("Time (ns)")
ax2.set_ylabel("Amplitude (V)")
ax2.legend(loc="upper right")
ax2.grid(True)
ax2.set_ylim(-2, 2)  # Fixed amplitude range

# Add phase annotations
for i, (amp, phase) in enumerate(symbols):
    ax1.text(i * 100 + 50, 1.5, f"{int(np.degrees(phase))}°", ha='center', fontsize=10)
    ax2.text(i * 100 + 50, 1.5, f"{int(np.degrees(phase))}°", ha='center', fontsize=10)

# Tighten layout and show
plt.suptitle("Carrier Frequency Impact on 4-QAM Signal Clarity", fontsize=14)
plt.tight_layout(rect=[0, 0, 1, 0.95])
plt.show()
