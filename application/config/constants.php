<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
 */
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
 */
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
 */
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
 */
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/**** USER DEFINED CONSTANTS **********/

defined('ROLE_ADMIN') or define('ROLE_ADMIN', '1');
defined('ROLE_MANAGER') or define('ROLE_MANAGER', '2');
defined('ROLE_VENDOR') or define('ROLE_VENDOR', '3');
defined('ROLE_ACCOUNTING') or define('ROLE_ACCOUNTING', '4');
defined('ROLE_VIGILANT') or define('ROLE_VIGILANT', '5');  
defined('ROLE_OWNER') or define('ROLE_OWNER', '6');
defined('ROLE_TENANT') or define('ROLE_TENANT', '7');
defined('ROLE_EMPLOYEES') or define('ROLE_EMPLOYEES', '8');

defined('STATUS_ACTIVE') or define('STATUS_ACTIVE', '1');
defined('STATUS_INACTIVE') or define('STATUS_INACTIVE', '0');

defined('NAME_SYSTEM') or define('NAME_SYSTEM', 'CONDOMINIO');

defined('SEGMENT') or define('SEGMENT', 2);

defined('PERMISSION_READ') or define('PERMISSION_READ', 0);
defined('PERMISSION_CREATE') or define('PERMISSION_CREATE', 1);
defined('PERMISSION_DELETE') or define('PERMISSION_UPDATE', 2);
defined('PERMISSION_DELETE') or define('PERMISSION_DELETE', 3);

/**** RESERVATION DEFINED CONSTANTS **********/
defined('RESERVE_PENDING') or define('RESERVE_PENDING', 0);
defined('RESERVE_APPROVED') or define('RESERVE_APPROVED', 1);
defined('RESERVE_DECLINED') or define('RESERVE_DECLINED', -1);

/**** INCIDENTS DEFINED CONSTANTS **********/
defined('INCIDENTS_PENDING') or define('INCIDENTS_PENDING', 0);
defined('INCIDENTS_IN_REVIEW') or define('INCIDENTS_IN_REVIEW', 1);
defined('INCIDENTS_SOLVED') or define('INCIDENTS_SOLVED', 2);

define('PHOTO_PATH', './assets/images/incidents');
define('PHOTO_W', '1000');
define('PHOTO_H', '1000');
define('PHOTO_DEFAULT', 'no_image_found.png');

/**** VIGILANT DEFINED CONSTANTS **********/

defined('VIGILANT_INPUT') or define('VIGILANT_INPUT', 1);
defined('VIGILANT_EXIT') or define('VIGILANT_EXIT', 2);
 

defined('CONDITIONS') or define('CONDITIONS', ["OCUPADO","DESOCUPADO","ALQUILADO","EN VENTA"]);
defined('USER_TYPE') or define('USER_TYPE', ["ADMINISTRADOR","CAJA","CONTABILIDAD","VIGILANTE"]);
defined('PERMISSION_DEFAULT') or define('PERMISSION_DEFAULT', '{ "users": { "C": 1, "R": 1, "U": 1, "D": 1 }, "apartments": { "C": 1, "R": 1, "U": 1, "D": 1 }, "edifices": { "C": 1, "R": 1, "U": 1, "D": 1 }, "tenants": { "C": 1, "R": 1, "U": 1, "D": 1 }, "tags": { "C": 1, "R": 1, "U": 1, "D": 1 }, "services": { "C": 1, "R": 1, "U": 1, "D": 1 }, "reservations": { "C": 1, "R": 1, "U": 1, "D": 1 }, "period": { "C": 1, "R": 1, "U": 1, "D": 1 }, "directive": { "C": 1, "R": 1, "U": 1, "D": 1 }, "setting": { "C": 1, "R": 1, "U": 1, "D": 1 }, "incidents": { "C": 1, "R": 1, "U": 1, "D": 1 }, "vigilant": { "C": 1, "R": 1, "U": 1, "D": 1 }, "commonarea": { "C": 1, "R": 1, "U": 1, "D": 1 }, "Payments": { "C": 1, "R": 1, "U": 1, "D": 1 }, "bills": { "C": 1, "R": 1, "U": 1, "D": 1 }, "vehicles": { "C": 1, "R": 1, "U": 1, "D": 1 }, "employees": { "C": 1, "R": 1, "U": 1, "D": 1 }, "shopping": { "C": 1, "R": 1, "U": 1, "D": 1 }, "condominium_items": { "C": 1, "R": 1, "U": 1, "D": 1 }, "condominium": { "C": 1, "R": 1, "U": 1, "D": 1 }, "pay": { "C": 1, "R": 1, "U": 1, "D": 1 } }');
defined('PERMISSION_TENANT') or define('PERMISSION_TENANT', '{ "users": { "C": 0, "R": 0, "U": 0, "D": 0 }, "apartments": { "C": 0, "R": 0, "U": 0, "D": 0 }, "edifices": { "C": 0, "R": 0, "U": 0, "D": 0 }, "tenants": { "C": 0, "R": 0, "U": 0, "D": 0 }, "tags": { "C": 0, "R": 0, "U": 0, "D": 0 }, "services": { "C": 0, "R": 0, "U": 0, "D": 0 }, "reservations": { "C": 1, "R": 1, "U": 1, "D": 0 }, "period": { "C": 0, "R": 0, "U": 0, "D": 0 }, "directive": { "C": 0, "R": 0, "U": 0, "D": 0 }, "setting": { "C": 0, "R": 0, "U": 0, "D": 0 }, "incidents": { "C": 1, "R": 1, "U": 1, "D": 0 }, "vigilant": { "C": 0, "R": 0, "U": 0, "D": 0 }, "commonarea": { "C": 0, "R": 0, "U": 0, "D": 0 }, "Payments": { "C": 0, "R": 0, "U": 0, "D": 0 }, "bills": { "C": 0, "R": 0, "U": 0, "D": 0 }, "vehicles": { "C": 1, "R": 1, "U": 1, "D": 1 }, "employees": { "C": 1, "R": 1, "U": 1, "D": 0 }, "shopping": { "C": 0, "R": 0, "U": 0, "D": 0 }, "condominium_items": { "C": 0, "R": 0, "U": 0, "D": 0 }, "condominium": { "C": 0, "R": 0, "U": 0, "D": 0 }, "pay": { "C": 1, "R": 1, "U": 1, "D": 0 } }');


defined('DIRECTIVE_CHARGUE') or define('DIRECTIVE_CHARGUE', ["PUESTO 1","PUESTO 2","PUESTO 3","PUESTO 4","PUESTO 5","PUESTO 6"]);

/************************** EMAIL CONSTANTS *****************************/
define('EMAIL_FROM', 'no-reply@buskato.com.ar'); // e.g. email@example.com
define('EMAIL_BCC', 'camperanny63@gmail.com'); // e.g. email@example.com
define('FROM_NAME', 'BUSKATO'); // Your system name
define('EMAIL_PASS', 'B5u5sk9a6t3o'); // Your email password
define('PROTOCOL', 'SMTP'); // mail, sendmail, smtp
define('SMTP_HOST', 'vps-1756072-x.dattaweb.com');
define('SMTP_PORT', '993');
define('SMTP_USER', 'no-reply@buskato.com.ar');
define('SMTP_PASS', 'B5u5sk9a6t3o');
define('MAIL_PATH', '/usr/sbin/sendmail');
