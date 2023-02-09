2023 attempt at reading the NIST WWVB time-singal shortwave station from a microphone jack

https://www.nist.gov/pml/time-and-frequency-division/time-distribution/radio-station-wwvb#antenna
https://www.nist.gov/pml/time-and-frequency-division/radio-stations/wwvb/wwvb-time-code-format

https://stackoverflow.com/questions/60525245/what-is-bits-per-sample-structure-of-wav-audio-files

Plan of attack:

This may be hopeless without filtering for the carrier.

I think my calculations are correct.


The absolute value of the numbers gets smaller as time passes.  
Given that I only care about 1 second intervals, take the minimum value within that second and then 
calculate around that.


****************
I may have to back up and make sure I understand WAV

I have to find the minimum number throughout the sample THEN add

Am I interpreting stereo correctly?  
Should I take abs? - no

Take time samples of start, each read.  Buffer this data to display at the end.
Correlate time with size of read
Sleep to hit the start of the second.
It takes a number of seconds for the sampling to stabilize; then again, maybe all I care about are relative 0.1 intervals, so maybe I can account 
for this.
Perhaps try to read the time code precisely, with the actual numbers.
Note that I think I was misreading the pulse format last time.

https://en.wikipedia.org/wiki/Decibel

Specific version of previous attempt:
https://github.com/kwynncom/code-fragments/tree/a924941f03831e1ae92a40c99b861e008f2fd8ff/2022_03_bucket/2022_01_bucket/shortwave
