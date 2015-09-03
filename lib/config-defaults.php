<?php
/**
 *
 * @package    mahara
 * @subpackage core
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

/**
 * CONFIGURATION DEFAULTS
 *
 * Do Not Edit This File!
 *
 * If you see a setting in here you'd like to change, copy it to your
 * config.php and change it there.
 *
 * This file sets defaults that are useful for most people.
 */

$cfg = new stdClass();

/**
 * @global int $cfg->directorypermissions what permissions to use for files and directories in
 * dataroot. The default allows only the web server user to read the data. If
 * you're on shared hosting and might want to download the contents of your
 * dataroot later (e.g. for backup purposes), set this to 0755. Otherwise,
 * leave it as is!
 */
//$cfg->directorypermissions = 0700;

/**
 * @global bool $cfg->insecuredataroot whether to enforce checking that files being served have
 * come from dataroot. You would only want to turn this on if you were running
 * more than one Mahara against the same dataroot. If you are doing that, make
 * sure you create separate dataroots for each installation, but symlink the
 * artefact directory from all of them to one of them, and turn on
 * 'insecuredataroot' on all the ones you created symlinks for.
 *
 * If you don't know what you're doing/didn't understand the paragraph above,
 * then leave this setting alone!
 */
//$cfg->insecuredataroot = false;

/**
 * @global string $cfg->noreplyaddress system mail address. emails out come from this address.
 * if not specified, will default to noreply@ automatically detected host.
 * if that doesn't work or you want something else, then specify it here.
 */
// $cfg->noreplyaddress = 'noreply@myhost.com';

/**
 * Logging configuration
 * @global int $cfg->log_dbg_targets Where to log debugging messages
 * @global int $cfg->log_info_targets Where to log informational messages
 * @global int $cfg->log_warn_targets Where to log warnings
 * @global int $cfg->log_environ_targets Where to log environment errors
 * For each log level, you can specify where the messages are displayed.
 *
 * LOG_TARGET_SCREEN makes the error messages go to the screen - useful
 * when debugging but not on a live site!
 * LOG_TARGET_ADMIN sends error messages to the screen but only when
 * browsing in the admin section
 * LOG_TARGET_ERRORLOG makes the error messages go to the log as specified
 * by the apache ErrorLog directive. It's probably useful to have this on
 * for all log levels.
 * LOG_TARGET_FILE allows you to specify a file that messages will be logged
 * to. It's best to pick a path in dataroot, but note that logfiles tend to get
 * very large over time - so it's advisable to implement some kind of logrotate
 * if you want to leave this on all the time. The other option is to just turn
 * this on when you are getting some kind of error or want to see the logging,
 * and know that you're not going to let the logfile get large.
 *
 * You can combine the targets with bitwise operations,
 * e.g. LOG_TARGET_SCREEN | LOG_TARGET_ERRORLOG
 *
 * This configuration is suitable for people running Mahara for the first
 * time. You will immediately see environment errors, and so can correct
 * them. You will be able to see other debugging information in your error
 * logs. Once your site is up and running you might want to remove the
 * environment level logging completely, and just log everything else to
 * the error log.
 */
$cfg->log_dbg_targets     = LOG_TARGET_ERRORLOG;
$cfg->log_info_targets    = LOG_TARGET_ERRORLOG;
$cfg->log_warn_targets    = LOG_TARGET_ERRORLOG;
$cfg->log_environ_targets = LOG_TARGET_SCREEN | LOG_TARGET_ERRORLOG;
/**
 * This configuration is suitable for developers. You will see all errors
 * and they will also be in the logs.
 */
//$cfg->log_dbg_targets     = LOG_TARGET_SCREEN | LOG_TARGET_ERRORLOG;
//$cfg->log_info_targets    = LOG_TARGET_SCREEN | LOG_TARGET_ERRORLOG;
//$cfg->log_warn_targets    = LOG_TARGET_SCREEN | LOG_TARGET_ERRORLOG;
//$cfg->log_environ_targets = LOG_TARGET_SCREEN | LOG_TARGET_ERRORLOG;

/**
 * @global string $cfg->log_file: If you use LOG_TARGET_FILE, this is the file that errors will be logged to.
 * By default, it will write to the file 'error.log' under dataroot. If you
 * change this in config.php, make sure you use a folder which is writable by
 * the webserver.
 */
// $cfg->log_file = '/path/to/dataroot/error.log';

/**
 * @global int $cfg->log_backtrace_levels The log levels that will generate backtraces. Useful for development,
 * but probably only warnings are useful on a live site.
 */
$cfg->log_backtrace_levels = LOG_LEVEL_WARN | LOG_LEVEL_ENVIRON;

/**
 * @global int $cfg->error_reporting What level of errors to print to the Mahara logs. Gets passed directly
 * to the PHP function "error_reporting()".
 *
 * NOTE: There are some limitations in this method, because it doesn't get called until several scripts
 * have already been compiled: init.php, config.php, config-defaults.php, errors.php, and the file directly
 * invoked in the URL. So, compile-time errors in those files (which includes most strict errors) will be
 * unaffected by this setting.
 */
$cfg->error_reporting = E_ALL & ~E_STRICT;

/**
 * @global int|bool $cfg->developermode Developer mode
 * When set, the following things (among others) will happen:
 *
 * * 'debug.js' will be included on each page. You can edit this file to add
 *   debugging javascript at your discretion
 * * 'debug.css' will be included on each page. You can edit this file to add
 *   debugging CSS at your discretion
 * * the unpacked version of MochiKit will be used
 *
 * These options are a performance hit otherwise, enable when you are
 * developing for Mahara
 */
$cfg->developermode = false;
// $cfg->developermode = DEVMODE_DEBUGJS | DEVMODE_DEBUGCSS | DEVMODE_UNPACKEDJS;

/**
 * @global bool $cfg->sendemail Whether to send e-mail. If set to false, Mahara will not send any e-mail at
 * all. This is useful for when setting up development versions of Mahara where
 * you don't want to accidentally send email to users from this particular
 * Mahara installation.
 */
$cfg->sendemail = true;
/**
 * @global string $cfg->sendallemailto You can use sendallemailto to have all e-mails from this instance of Mahara
 * sent to one particular address instead of where they're aimed for. Leave
 * sendemail = true if you want to use this setting.
 */
// $cfg->sendallemailto = 'you@example.com';

/**
 * @global string $cfg->emaillog Log basic details of emails sent out by Mahara.  Must be writable by the
 * webserver user.  This will get big.
 */
// $cfg->emaillog = '/path/to/dataroot/email.log';

/**
 * @global bool $cfg->perftolog capture performance information and print it
 * @global bool $cfg->perftofoot needs a call to mahara_performance_info (smarty callback) - see default theme's footer.tpl
 * if neither are set, performance info wont be captured.
 */
// $cfg->perftolog = true;
// $cfg->perftofoot = true;

/**
 * Mail handling
 *
 * Unless you have a specific reason for having mail settings in the config file,
 * please use Configure Site -> Site options -> Email interface.
 *
 * @global string $cfg->smpthosts If you want mahara to use SMTP servers to send mail, enter one or more here
 * blank means mahara will use the default PHP method.
 */
// $cfg->smtphosts = 'smtp1.example.com;smtp2.example.com';
/**
 * @global int $cfg->smtpport If smtp server uses port number different from 25 (e.g. for secure connections,
 * port 465 is usually used with ssl, port 587 is usually used with tls),
 * specify it below. Alternatively you may specify the port in smtphosts
 * definition above using format [hostname:port] (e.g. 'smtp1.example.com:465').
 */
// $cfg->smtpport = 25;
/**
 * @global string $cfg->smtpuser If you have specified an smtp server above, and the server requires
 * authentication, enter user credentials here:
 * @global string $cfg->smptpass Password to match the user in $cfg->smtpuser
 */
// $cfg->smtpuser = '';
// $cfg->smtppass = '';
/**
 * @global string $cfg->smtpsecure If smtp server requres secure connection, specify the protocol type below.
 * Valid options are '', 'ssl' or 'tls'. Setting it to '' or leaving the line
 * commented means that secure connection will not be used.
 */
// $cfg->smtpsecure = '';

/**
 * Variable Envelope Return Path Handling
 * @global bool $cfg->bounces_handle If you want mahara to keep track of email addresses which generate a
 * bounce, set bounces_handle to true.
 * If set to true, $cfg->bouncedomain *must* be set.
 */
$cfg->bounces_handle  = false;
/**
 * @global int $cfg->bounces_min Rather than disable an email address on the first bounce message,
 * require bounces_min bounces.
 */
$cfg->bounces_min     = 5;
/**
 * @global number $cfg->bounces_ratio Require at least (bounces_ratio*100)% of sent mail to be bounced back
 * before disabling mail for that user.
 * e.g. If using the default bounces_ratio of 0.20 and 20 mails are sent to
 * a user, at least 4 must be returned before email is disabled.
 * (Set this to 0 to ignore bounces_ratio and rely solely on bounces_min)
 */
$cfg->bounces_ratio   = 0.20;
/**
 * @global string $cfg->bounceprefix Identity of the Mahara instance
 * If you have several Mahara, Moodle, or other VERP processors on the same
 * bounce domain, you need to keep track of which processor belongs to
 * which domain.
 */
$cfg->bounceprefix    = 'AAA-';
/**
 * @global string $cfg->bouncedomain The domainpart of the generated VERP domain. e.g.
 * <localpart>@$cfg->bouncedomain
 * This must be set for VERP handling to take effect
 */
//$cfg->bouncedomain    = '';

/**
 * @global string $cfg->imapserver The imap server to check for bounced emails
 * @global int $cfg->imapport The port for the imap server
 */
//$cfg->imapserver  = 'localhost';
//$cfg->imapport    = 143;
/**
 * @global string $cfg->imapuser The imap username to authenticate
 * @global string $cfg->imappass The imap password to authenticate
 */
//$cfg->imapuser    = '';
//$cfg->imappass    = '';
/**
 * @global string $cfg->imapflags Any flags to pass to imap_open, can have multiple
 * See http://www.php.net/manual/en/function.imap-open.php
 */
//$cfg->imapflags   = '';
/**
 * @global string $cfg->imapmailbox Which mailbox to poll, defaults to INBOX
 */
//$cfg->imapmailbox = 'INBOX.someotherbox';
/**
 * You can even set the library to check pop mail boxes
 */
//$cfg->imapflags   = '/pop3';
//$cfg->imapport    = 110;

/**
 * maximum allowed size of uploaded images
 * @global int $cfg->imagemaxwidth
 * @global int $cfg->imagemaxheight
 * NOTE: the scalable resize might result in images with one dimesion larger than one of these sizes, but not both
 */
$cfg->imagemaxwidth = 1024;
$cfg->imagemaxheight = 1024;
/**
 * @global int $cfg->maximageresizememory Maximum allowed memory usage for thumbnail generation (approximate)
 */
$cfg->maximageresizememory = 104857600;

/**
 * Paths and arguments for various system commands
 *
 * @global string $cfg->pathtoaspell Set this path to use the TinyMCE Spellcheck button in place of the
 * browser's built-in spellchecker.
 */
//$cfg->pathtoaspell = '/usr/bin/aspell';

/**
 * @global $cfg->pathtoclam The path to the ClamAV executable (clamscan or clamdscan); disabled by default
 */
$cfg->pathtoclam = '';

/**
 * @global mixed $cfg->pathtomagicdb Set this value to specify where the PHP fileinfo "magic" DB is.
 *
 * If this value is NULL, Mahara will attempt to use PHP's internal magic db, or to
 * autolocate your system's magic DB.
 */
$cfg->pathtomagicdb = NULL;

/**
 * @global string $cfg->pathtogzip
 * @global string $cfg->pathtounzip
 * @global string $cfg->pathtozip
 * @global string $cfg->ziprecursearg Argument to pass to the zip executable to indicate that it should act recursively
 * @global string $cfg->unzipdirarg Argument to pass to the unzip executable to indicate the directory it should unzip into
 * @global string $cfg->unziplistarg Argument to pass to the unzip executable to list the contents of the archive
 */
$cfg->pathtogzip = '/bin/gzip';
$cfg->pathtounzip = '/usr/bin/unzip';
$cfg->pathtozip   = '/usr/bin/zip';
$cfg->ziprecursearg = '-r';
$cfg->unzipdirarg = '-d';
$cfg->unziplistarg = '-l';
/**
 * @global string $cfg->unziptempdir some shared hosts have restrictions on where unzip can be used
 * dataroot is often not allowed; but /tmp is. This path should end with a "/"
 * Note that if there is more than one mahara on this host using this setting
 * you must change this to something unique, eg /tmp/mahara1/ and /tmp/mahara2/
 */
// $cfg->unziptempdir = '/tmp/mahara/';
/**
 * @global string $cfg->sessionpath The directory to store session files in. Defaults to $cfg->dataroot.'sessions'.
 * This path should NOT end with a "/"
 *
 * If your dataroot is stored on a slow volume (such as NFS) you may want to change this to a local directory.
 * Although if you're using a web server cluster, be aware that session files need to be stored in a location shared
 * by all servers, or you need to use persistence to send each user to only one server per session.
 */
// $cfg->sessionpath = '/tmp/mahara-sessions';

/**
 * @global int $cfg->accesstimeupdatefrequency How often Mahara should update the last
 * access time for users. Setting this lower means the field will be updated more
 * regularly, but means a database write will be required for more requests.
 * Setting it to zero means the access time will be updated every request
 */
$cfg->accesstimeupdatefrequency = 300;

/**
 * @global int $cfg->accessidletimeout How long since their last request before a user is considered to be logged
 * out. Note that it makes no sense for this to be less than the
 * accesstimeupdatefrequency.
 */
$cfg->accessidletimeout = 600;

/**
 * @global bool $cfg->showonlineuserssideblock
 * Whether to show the onlineusers sideblock
 */
//$cfg->showonlineuserssideblock = true;

/**
 * @global int $cfg->leapovermnetlogelevel if importing Leap2A over an xmlrpc mnet connection,
 * set this to something higher than 0 to log import information
 * see the constants in import/leap/lib.php
 */
$cfg->leapovermnetloglevel = 0;

/**
 * @global string $cfg->remoteavatarbaseurl base URL of avatar server (with the trailing slash)
 * This should normally be set to http://www.gravatar.com/avatar/
 */
//$cfg->remoteavatarbaseurl = 'http://www.gravatar.com/avatar/';

/**
 * Options for width/height of wysiwyg editor in block configuration
 * forms.  Workaround for current lack of tinymce fullscreen button.
 *
 * @global bool $cfg->blockeditormaxwidth Make the block config form expand to the full width of browser window
 * whenever it contains a tinymce (also increases editor height in
 * textbox blocktype):
 */
//$cfg->blockeditormaxwidth = true;

/**
 * @global int $cfg->blockeditorheight Set a fixed height in pixels for the tinymce editor (currently only
 * affects the textbox blocktype):
 */
// $cfg->blockeditorheight = 550;

/**
 * @global bool $cfg->sslproxy This needs to be true when forcing https with an ssl proxy such as nginx.
 */
$cfg->sslproxy = false;

/**
 * @global string $cfg->cacertinfo The name of a file holding one or more certificates to verify the peer
 * when Mahara does HTTPS requests using the PHP Curl library.
 */
// $cfg->cacertinfo = null;

/**
 * External login page
 * @global string $cfg->externallogin Use this config option when you want users to be redirected to another
 * login page, for example a moodle instance that has mnet to this mahara
 * You can use the following placeholders:
 * {wwwroot} - Expands out to the wwwroot of this moodle
 * {shorturlencoded} - Expands to the relative script path of the current page (and is urlencoded)
 *
 * A point to note about the example below. Moodle doesn't strip the trailing slash from wwwroot
 * Bug MDL-30042 fixes this, if this patch isn't applied, just hard code the login url you want instead
 */
// $cfg->externallogin = 'http://moodle.example.com/auth/mnet/jump.php?hostwwwroot={wwwroot}&wantsurl={shorturlencoded}';

/**
 * @global bool $cfg->renamecopies If true, new copies of views & collections will have "Copy of" prepended to the title,
 * and if a page already exists with that title, a number will be appended to the end of the title.
 *
 * If false, "Copy of" will NOT be prepended, but a number may still be appended to the end of the title.
 */
$cfg->renamecopies = false;

/**
 * Favicon display
 * @global string $cfg->favicondisplay string used to get the favicon image src from a given domain.
 * Used to display the sites whose iframe embed code is allowed by htmlpurifier.
 * Either assume that favicon.ico exists at the root of the domain, or use a service.
 */
// $cfg->favicondisplay = 'http://%s/favicon.ico';
// $cfg->favicondisplay = 'http://www.grabicon.com/%s';
// $cfg->favicondisplay = 'http://www.getfavicon.org/?url=%s';
$cfg->favicondisplay = 'http://www.google.com/s2/favicons?domain=%s';

/**
 * @global bool $cfg->productionmode If false, a message is shown at the top of the screen saying that the
 * site is not in production mode; and a number of other parameters are overridden with sensible defaults
 * for a dev site. (See init.php for the full effect).
 *
 * Because productionmode=false overrides a lot of settings with sensible dev mode defaults, if you want to
 * fine-tune your settings on your dev site, you'll paradoxically need to set productionmode=true.
 */
$cfg->productionmode = true;

/**
 * @global bool $cfg->sitethemeprefs If true, users can change their preferred theme for browsing the site.  The user's theme preference
 * will override any site, institution, or page theme.
 * (Even with this setting disabled, users in multiple institutions can choose which of their
 * institutions' themes they wish to use)
 */
// $cfg->sitethemeprefs = true;

/**
 * Clean url configuration
 * @global bool $cfg->cleanurls Set "true" to activate clean URLS in Mahara
 * Do not turn this on until you have the correct rewrite rules in place on your webserver, or none of
 * your links will work.
 * See https://wiki.mahara.org/index.php/System_Administrator%27s_Guide/Clean_URL_Configuration
 */
// $cfg->cleanurls = true;

/**
 * Strings to use when generating user and group urls, i.e. the 'user' and 'group' portion of clean urls
 * such as http://mahara.example.com/user/bob and http://mahara.example.com/group/bobs-group.  These
 * must match the output of the rewrite rules you have enabled in your webserver.  These strings will
 * also be used as prefixes whenever a valid clean url cannot be automatically generated.
 * @global string $cfg->cleanurluserdefault
 * @global string $cfg->cleangroupdefault
 * @global string $cfg->cleanurlviewdefault
 */
$cfg->cleanurluserdefault = 'user';
$cfg->cleanurlgroupdefault = 'group';
$cfg->cleanurlviewdefault = 'page';

/**
 * @global string $cfg->cleanurlcharset Character encoding for clean urls.  ASCII or UTF-8.
 */
$cfg->cleanurlcharset = 'ASCII';

/**
 * @global string $cfg->cleanurlinvalidcharacters A PCRE pattern defining sequences of characters to be removed and replaced by '-' when automatically
 * generating names for use in clean urls.  For example, if the pattern is /[^a-zA-Z0-9]+/, and a clean
 * url is being generated for a user with the username 'nigel.mcnie', the '.', which appears in the
 * invalidcharacters list is replaced, to give a url like http://mahara.example.com/user/nigel-mcnie
 */
$cfg->cleanurlinvalidcharacters = '/[^a-zA-Z0-9]+/';

/**
 * @global string $cfg>cleanurlvalidate A pattern to validate user-editable fields for use in clean urls.  If a user enters a string that
 * doesn't match this, it's an error.
 */
$cfg->cleanurlvalidate = '/^[a-z0-9-]*$/';

/**
 * @global bool $cfg->cleanurlusereditable Setting this to false will remove the "Change profile URL" option from the settings page.
 */
$cfg->cleanurlusereditable = true;

/**
 * @global bool $cfg->cleanurlusersubdomains generate subdomain-style profile urls like http://bob.mahara.example.com
 * Warning: Enabling this option on your site is likely to cause users with open sessions to be logged out on all profile pages.
 * See https://wiki.mahara.org/index.php/System_Administrator%27s_Guide/Clean_URL_Configuration#User_Subdomains
 */
// $cfg->cleanurlusersubdomains = true;

/**
 * @global bool $cfg->nocache Turn on caching of HTTP requests
 */
// $cfg->nocache = true;

/**
 * Settings used by the "elasticsearch" search plugin.
 * See the helpfiles on the plugin's configuration page for details.
 * @global string $cfg->plugin_search_elasticsearch_host
 * @global int $cfg->plugin_search_elasticsearch_port
 * @global string $cfg->plugin_search_elasticsearch_username
 * @global string $cfg->plugin_search_elasticsearch_password
 * @global string $cfg->plugin_search_elasticsearch_indexname
 * @global string $cfg->plugin_search_elasticsearch_bypassindexname
 * @global string $cfg->plugin_search_elasticsearch_analyzer
 * @global string $cfg->plugin_search_elasticsearch_types
 */
// $cfg->plugin_search_elasticsearch_host = '127.0.0.1';
// $cfg->plugin_search_elasticsearch_port = 9200;
// $cfg->plugin_search_elasticsearch_username = '';
// $cfg->plugin_search_elasticsearch_password = '';
// $cfg->plugin_search_elasticsearch_indexname = 'mahara';
// $cfg->plugin_search_elasticsearch_bypassindexname = null;
// $cfg->plugin_search_elasticsearch_analyzer = 'mahara_analyzer';
// $cfg->plugin_search_elasticsearch_types = 'usr,interaction_instance,interaction_forum_post,group,view,artefact';

/**
 * Additional HTML: Use these settings to put snippets of HTML at the top of every page on the site.
 * This is useful for e.g. Google Analytics. If you need to enter a multi-line snippet, it may be best
 * to use a PHP nowdoc: http://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.nowdoc
 */
// $cfg->additionalhtmlhead = <<<'HTML'
// <script type="text/javascript">
//   var _gaq = _gaq || [];
//   _gaq.push(['_setAccount', 'UA-XXXXX-X']);
//   _gaq.push(['_trackPageview']);
//   (function() {
//     var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
//     ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
//     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
//   })();
// </script>
// HTML;
/**
 * @global string $cfg->additionalhtmlhead Added as the last item before the closing </head> tag
 */
$cfg->additionalhtmlhead = '';
/**
 * @global string $cfg->additionalhtmltopofbody Added immediately after the opening <body> tag
 */
$cfg->additionalhtmltopofbody = '';
/**
 * @global string $cfg->additionalhtmlfooter Added as the last item before the closing </body> tag
 */
$cfg->additionalhtmlfooter = '';

/**
 * @global string $cfg->auth_ldap_debug_sync_cron Whether to display extended debugging messages in
 * the auth/ldap user & group synchronization cron task.
 */
// $cfg->auth_ldap_debug_sync_cron = false;

/**
 * @global bool $cfg->usersuniquebyusername When turned on, this setting means that it doesn't matter
 * which other application the user SSOs from, the same username string from a remote SSO will be given
 * the same account in Mahara.
 *
 * This setting is one that has security implications unless only turned on by people who know what they're doing. In
 * particular, every system linked to Mahara should be making sure that same username == same person.  This happens for
 * example if two Moodles are using the same LDAP server for authentication.
 *
 * If this setting is on, it must NOT be possible to self register on the site for ANY institution - otherwise users
 * could simply pick usernames of people's accounts they wished to steal.
 */
$cfg->usersuniquebyusername = false;

/**
 * @global bool $cfg->skins Activates the experimental "page skins" feature, which allows users to customize the CSS
 * on individual pages.
 */
// $cfg->skins = false;

/**
 * @global string $cfg->opensslcnf Allows manual setting of path to openssl.cnf file for ssl key generation if not
 * being automatically detected. Needed for extra-site networking.
 */
// $cfg->opensslcnf = '';

/**
 * @global string $cfg->dbtimezone Sets the timezone for your database connection. This is only necessary if your
 * database server has a different timezone than your web server (which is most likely to happen in cloud hosting).
 * Consult your database's manual for legal values.
 */
// $cfg->dbtimezone = '+10:00';
// $cfg->dbtimezone = 'Europe/Rome';

/**
 * @global bool $cfg->publicsearchallowed Activates the display of the search box for logged-out users
 */
$cfg->publicsearchallowed = false;

/**
 * @global bool $cfg->alwaysallowselfdelete Set this to "true" to allow all users on the site to delete
 * their accounts. Leave on the default "false" to only allow users to delete their accounts if they
 * belong to an institution that allows self-registration.
 */
//$cfg->alwaysallowselfdelete = true;

/**
 * @global boolean $cfg->probationenabled Determines whether or not to use the new-user probation system.
 * If enabled, this will prevent newly self-registered users from taking certain actions that would be
 * useful for creating spam content. Users will leave probation once non-probationary users take actions
 * that indicate they trust the probationary user.
 */
$cfg->probationenabled = false;

/**
 * @global integer $cfg->probationstartingpoints If new user probation is enabled, this setting determines how
 * many probation points new users will start with.
 */
$cfg->probationstartingpoints = 2;

/**
 * @global string $cfg->cookieprefix Prefix to use on the names of any cookies issued by Mahara. This may
 * be useful in some unusual hosting situations, for instance if you are running another web application
 * that issues cookies with the same domain and path as Mahara.
 */
// $cfg->cookieprefix = '';

/**
 * @global bool $cfg->showloginsideblock
 * Whether to show the login sideblock
 */
$cfg->showloginsideblock = true;

/**
 * @global string $cfg->ajaxifyblocks Whether or not to use AJAX to load the content of blocktypes.
 * If the network connection to your users is quite slow, then disabling this may improve the user
 * experience.
 */
$cfg->ajaxifyblocks = true;
