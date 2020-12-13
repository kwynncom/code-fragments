Mid as in machine ID.

Again this goes to primary keys or a form of UUID in that I want to identify CPU tick, core / thread number, which boot session (or timestamp), 
and which machine.  

I may also add which filesystem, filesystem creation date, which OS version, etc.

I should save AWS EC2 image ID and filesystem time because when you build one instance from an older image, the "old" filesystem time is still recorded.

COMMANDS:

sudo tune2fs -l /dev/sda1

Shows filesystem creation time, which may be instances and years ago.

mount | grep 'on / '

Shows where root is mounted

/sys/class/dmi/id$ cat chassis_vendor

Make, either HP or "Amazon EC2"

/sys/class/dmi/id$ cat product_name

Model: Fabulous X22 or t3a.nano [EC2 type]

/sys/class/dmi/id$ sudo cat product_serial

Actual computer box serial number.  With AWS it's a UUID that doesn't correspond to anything else. 

/sys/class/dmi/id$ cat board_asset_tag

AWS instance ID.

curl http://169.254.169.254/latest/dynamic/instance-identity/document

Lots of AWS info that I'd want, in JSON format.

lsb_release -a

Distributor ID:	Ubuntu
Description:	Ubuntu 20.04.1 LTS
Release:	20.04
Codename:	focal
