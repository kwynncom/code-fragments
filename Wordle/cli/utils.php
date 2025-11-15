<?php

function isl(string $l) : bool { return preg_match('/^[a-z]{1}$/', $l) ? true : false; }

