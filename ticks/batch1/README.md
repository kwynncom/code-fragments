Experiments on the timestamp counter (TSC) versus UTC.  

TSC is an x86 instruction.  It is the basic CPU clock tick such as 2.33 GHz.  One question I am trying to answer is to what degree one can 
use the TSC for accurate time.  

I created a nanopk() PHP function

Experiments from the last 2 days:

I learned that when my desktop "suspends" (not sure whether that's "sleep," "hibernate," or neither), the TSC starts over.

The tests also show that the first calls to nanopk() are, to use a technical term, a mess, for at least two reasons:

1. Within the extension / C code, if the calls to rdtscp and clock_gettime() (for nanotime()) are too far apart due to load time or preemption or 
whatever, that throws off the TSC - UTC sync.  I've solved that in triplets.php and getStableNanoPK().  I'll come back to this.  

2. You need at least 0.1s and preferably 3s between two TSC-UTC data points to get enough significant digits to start to get the proper ratio (ns per tick or 
vice versa).  I mostly solved that that in what is currently called "quick.php," and now I'm probably solving it another way.  So, the details / explanation:

[to come soon]

BACKGROUND

PHP extension for nanopk: 
https://github.com/kwynncom/readable-primary-key/tree/006ce33770c43d6631221680b8f6dce73eb5534d/php_extension

Note that that is a specific version / commit.  It's not the latest.  


MUTTERINGS

For once I'll try to explain all this.