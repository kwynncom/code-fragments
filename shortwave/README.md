try to receive the WWVB time signal by reading the microphone jack

WWVB is a US NIST (National Institute of Standards and Technology) shortwave radio station that broadcasts a time signal on a 
60kHz carrier wave, or at channel 60kHz (links below), with 30kW.  It broadcasts from (near) Fort Collins, Colorado.  The 
specific AM pulses of the signal are described in a link below.

Based on my microphone jack random number generator (below), I may be able to receive the signal well enough from a microphone jack.  It seems 
that the jack, without a microphone, is extremely sensitive.  I might even plug in a microphone.  I'm not sure how much good the microphone does as an 
antenna given that my math gives me a wavelength of 3.1 miles (186,000 miles per second / 60,000 waves (cycles) per second), but I'll try that later.

The best my microphone can do is 48kHz sampling rate.  My math says that 48kHz and 60kHz meet every 5 cycles.  That is, if I sample at 48kHz and check 
every 5th sample, I am reading same point in the 60kHz wave cycle every 4th wave.  My microphone only records in stereo. 

Attempt 2: Attempt 1 was based on aligning the waves--a 5:4 ratio or whatever.  My second attempt is to read the average power of each 0.1s.  This shows 
some promise.  There is still the issue of aligning the pulses, but this time I'm trying to see an accurate 0.2s pulse rather than several orders of magnitude 
smaller.  

Attempt 3: My latest data is with a microphone.  It's possible that the microphone is mono (or broken) and the jack is definitely stereo only, so I may 
"dump" the 2nd channel.

I demonstrated that I can get true random numbers from an empty (no microphone plugged in) microphone jack: 
https://github.com/kwynncom/true-random-numbers-from-microphone

Depending on the settings, I can hear radio wave "whistlers" from that output.  Given the randomness and whistlers, I conclude that the 
microphone receiver is very electrically sensitive, and I am picking up atmospheric noise.  

WWVB puts out a distinctive pulse.  I believe they said it ranges 17dB and the pulses (higher and lower power) last several 0.1s.

https://www.nist.gov/time-distribution/radio-station-wwvb/wwvb-antenna-configuration-and-power
https://www.nist.gov/time-distribution/radio-station-wwvb
https://www.nist.gov/pml/time-and-frequency-division/radio-stations/wwvb/wwvb-time-code-format

As of January 1, 2006 the WWVB broadcast signal has increased the depth of the time code modulation from 10 dB to 17 dB
https://tf.nist.gov/general/pdf/2139.pdf


***********
try 2:

arecord -f S16_LE -c 2 -r 8000 --device="hw:0,0" -d 2 > /tmp/hwrset1.wav

*************
 
// $ arecord -f S32_LE -c 2 -r 48000 --device="hw:0,0" -d 10 > /tmp/hwrset1.wav
// Recording WAVE 'stdin' : Signed 32 bit Little Endian, Rate 48000 Hz, Stereo
// 3,840,044 bytes
// 44 bytes wav header
// 4 bytes per sample X 2 channels X 48ksamples/s X 10s = 3,840,044
// 8 bytes per sample X 48k samples

// pack / unpack:  V	unsigned long (always 32 bit, little endian byte order)

// 384,000 bytes / s