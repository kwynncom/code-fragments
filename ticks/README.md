I am studying precision of CPU ticks from the rdtscp x86 instruction.  This is yet another spinoff of my first php extension:

https://github.com/kwynncom/readable-primary-key/tree/master/php_extension

The eventual goal of all that is to create a primary key without mutex and assuming multiple threads.  The clock tick and thread core number works 
within a boot session and a given machine.  Adding a timestamp makes the key independent of boot session.  Even if I want very precise time, is there 
a need to call nanotime() every time, or do I just need to keep track of the boot sessions?  

This code studies whether the clock tick is consistent enough for very precise time.  So far the answer is yes in that I can get 14 decimal digits of 
precision, which is the max precision of a double floating point.  

Specifically, if I take samples over several hours and use the latest example as the slope of my ticks / nanosecond, I get very consistent results using the 
tick versus nanotime().  

I have not figured out what happens when the machine goes to sleep.  

In this code, I removed the 14 digit number for 2 related reasons:

* I am trying to be aware of anything in GitHub that could remotely be a security issue--revealing too much.  Even if I think it's a very minor potential issue, 
I at least try to catch those things.  

* The exact clock tick timing likely does identify my CPU.  Perhaps that is a bad idea, even if it's very paranoid.

I can't remember which file was which--hopefully earlier versions are in GitHub--but this is a case where I wandered in a circle, in part because I was 
getting tired at 4am on Sat, December 5.  I think enough timestamps are in GitHub to show which file is the circular one and which worked.

One of the points of confusion, mostly (I hope) because I was tired is that CPU clock reset time and boot time are not the same.  I think they were 43 seconds 
apart in my case.  CPU clock reset is just after the boot session ends and before the BIOS / hardware manufacturer splash screen.  Linux records boot time 
"long" after that--once a kernel is loaded and such.
