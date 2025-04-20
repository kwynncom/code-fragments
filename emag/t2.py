import numpy as np
import matplotlib.pyplot as plt

# Time array: 0 to 200 ns, high resolution for GHz visibility
t = np.linspace(0, 200e-9, 2000)  # 2000 points for smoother curves

# Frequencies and symbol transition
f1, f2 = 100e6, 12e9  # 100 MHz, 12 GHz
symbol_duration = 100e-9  # 100 ns per symbol (10 Mbaud)
transition = int(len(t) / 2)  # Midpoint at 100 ns

# Clean signals (16-QAM example: +1 to +3 amplitude, 0° to 45° phase)
signal1 = 1 * np.sin(2 * np.pi * f1 * t)  # 100 MHz, first symbol
signal1[transition:] = 3 * np.sin(2 * np.pi * f1 * t[transition:] + np.pi/4)  # Second symbol
signal2 = 1 * np.sin(2 * np.pi * f2 * t)  # 12 GHz
signal2[transition:] = 3 * np.sin(2 * np.pi * f2 * t[transition:] + np.pi/4)

# Add noise (20 dB SNR: signal power 100x noise power)
noise_amplitude = 0.1  # Rough approximation for 20 dB SNR
noise1 = noise_amplitude * np.random.randn(len(t))
noise2 = noise_amplitude * np.random.randn(len(t))
noisy_signal1 = signal1 + noise1
noisy_signal2 = signal2 + noise2

# Plotting
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(10, 8), sharex=True)

# 100 MHz plot
ax1.plot(t * 1e9, noisy_signal1, 'b', label='Noisy Signal')
ax1.plot(t * 1e9, signal1, 'k--', alpha=0.5, label='Clean Signal')
ax1.axvline(x=100, color='gray', linestyle='--', label='Symbol Transition')
ax1.set_title("100 MHz Carrier, 10 Cycles/Symbol, 10 Mbaud")
ax1.set_ylabel("Amplitude (V)")
ax1.legend(loc="upper right")
ax1.grid(True)

# 12 GHz plot
ax2.plot(t * 1e9, noisy_signal2, 'r', label='Noisy Signal')
ax2.plot(t * 1e9, signal2, 'k--', alpha=0.5, label='Clean Signal')
ax2.axvline(x=100, color='gray', linestyle='--', label='Symbol Transition')
ax2.set_title("12 GHz Carrier, 1200 Cycles/Symbol, 10 Mbaud")
ax2.set_xlabel("Time (ns)")
ax2.set_ylabel("Amplitude (V)")
ax2.legend(loc="upper right")
ax2.grid(True)

# Tighten layout and show
plt.suptitle("Carrier Frequency Impact on Signal Clarity (40 Mbps, 16-QAM)", fontsize=14)
plt.tight_layout(rect=[0, 0, 1, 0.95])
plt.show()
