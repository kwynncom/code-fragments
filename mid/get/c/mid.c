#include <stdlib.h>
#include <unistd.h>
void main()
{
	setreuid(geteuid(), getuid());
	system("sudo php /home/k/sm20/frag/mid/get/c/../midClass.php");
	
}