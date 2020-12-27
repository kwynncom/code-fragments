This code gives a boot session a sequence number and other data and then serves that data to client processes.

HISTORY

2020/12/26 11:00pm
The boot20.php FIFO attempt is a disaster.  The damnest things happen with FIFOs.  Perhaps one day I'll sort it all out.

Until around 7:30pm EST (GMT -5) 2020/12/26, I used shared memory.  Then I noticed that a simple temp file was about 
100 times faster.  Playing with shared memory was fun, though.  I don't consider it a waste.

TECH NOTES

Regarding the shm_ / shared memory functions, every way I try to get it to work, I have to use 0666 permission.  
There may be a bug in the shm_ library itself.  

One alternative is to create a group for www-data and any other users who will either create or use the segment.

This problem is one of several reasons I tried the file method.
