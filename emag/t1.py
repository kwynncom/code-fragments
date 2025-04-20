import numpy as np
import matplotlib.pyplot as plt

t = np.linspace(0, 200e-9, 1000)  # 0-200 ns
f1, f2 = 100e6, 12e9  # 100 MHz, 12 GHz
signal1 = 1 * np.sin(2 * np.pi * f1 * t)  # 100 MHz, first 100 ns
signal1[500:] = 3 * np.sin(2 * np.pi * f1 * t[500:] + np.pi/4)  # Shift at 100 ns
signal2 = 1 * np.sin(2 * np.pi * f2 * t)  # 12 GHz
signal2[500:] = 3 * np.sin(2 * np.pi * f2 * t[500:] + np.pi/4)

fig, (ax1, ax2) = plt.subplots(2, 1)
ax1.plot(t * 1e9, signal1, 'b'); ax1.set_title("100 MHz, 10 Cycles/Symbol")
ax2.plot(t * 1e9, signal2, 'r'); ax2.set_title("12 GHz, 1200 Cycles/Symbol")
plt.show()
