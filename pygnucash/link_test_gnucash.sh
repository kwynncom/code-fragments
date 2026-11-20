#!/bin/bash
src=/var/kwynn/gnucash/gnucash.xml.gnucash
dest=/var/kwynn/gnucash/grok-gnucash.gnucash

ln -fL "$src" "$dest"
chgrp grok "$dest"
chmod 640 "$dest"
