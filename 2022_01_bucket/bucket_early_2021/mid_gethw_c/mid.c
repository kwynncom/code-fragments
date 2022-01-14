// THIS ENTIRE PROGRAM IS WRITTEN FROM PHP; DON'T CHANGE THE C FILE
#include <stdlib.h>
#include <unistd.h>
void main()
{
	setreuid(geteuid(), getuid());
	system("sudo php /home/user/blah/frag/bootd/mid/gethw/c/../midcl.php");
	
}