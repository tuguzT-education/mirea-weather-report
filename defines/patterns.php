<?php

define('PASSWORD_REGEX_HTML', '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)' .
	'(?=.*[()\[\]\{\}&lt;&gt;?`~.,;:№!\\\\\/\|@#$%^&amp;&apos;&quot;*_=+-]).{8,}$');
define('NO_DIGIT_REGEX_HTML', '^([^0-9]*)$');
define('NUMBER_REGEX_HTML', '^-?\d*(\.\d+)?$');
