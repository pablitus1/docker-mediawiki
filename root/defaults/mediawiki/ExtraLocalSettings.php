<?php
// @see https://www.mediawiki.org/wiki/Manual:Configuration_settings
# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}
##########################################################################
##	Configuration settings											    ##
##	See https://www.mediawiki.org/wiki/Manual:Configuration_setting 	##
##  for an index of all supported configuration settings. 				##
##########################################################################


## Debug
## See https://www.mediawiki.org/wiki/Manual:How_to_debug
$wgShowExceptionDetails = true;
#$wgShowDebugToolbar = true;
#$wgShowDebug = true;
#$wgDebugDumpSql = true;
#$wgDebugLogFile = "/config/log/mediawiki/debug.log";


## Logo and Favicon
## $wgLogo is set in LocalSettings.php when it's generated by the installer
## Simply upload your favicon.ico to the root of your domain/subdomain,
## make sure file name is in lower case and its name is "favicon.ico"
## or uncomment below and set you own path.
#$wgFavicon = "/path/to/your/favicon.ico";


## Short URLs
## See https://www.mediawiki.org/wiki/Manual:Short_URL
## $wgScriptPath = ""; //defined in LocalSettings.php
$wgArticlePath = "/$1";
$wgUsePathInfo = true;
$wgScriptExtension = ".php";


## User Rights
## See https://www.mediawiki.org/wiki/Manual:User_rights
// Make wiki private
#$wgGroupPermissions['*']['read'] = false;
#$wgGroupPermissions['*']['createaccount'] = false;
#$wgGroupPermissions['*']['edit'] = false;


## File Uploads
## See https://www.mediawiki.org/wiki/Manual:Configuring_file_uploads
// Uncomment below to use docker volume /assets upload path or set your own path
#$wgUploadPath = "/assets";
#$wgUploadDirectory = "/assets";
// Maximum file upload size
// If you increase this valve you must also update the config/php/php-local.ini file
$wgUploadSizeWarning = 1073741824; // 1024*1024*1024 = 1 GB
$wgMaxUploadSize = 1073741824; // 1024*1024*1024 = 1 GB
// Allowed file extension type
#$wgFileExtensions = array( 'png', 'gif', 'jpg', 'jpeg', 'doc',
#    'xls', 'mpp', 'pdf', 'ppt', 'tiff', 'bmp', 'docx', 'xlsx',
#    'pptx', 'ps', 'odt', 'ods', 'odp', 'odg'
#);
// Uploading directly from a URLs
#$wgAllowCopyUploads = true;
#$wgCopyUploadsFromSpecialUpload = true;


## Copyright
## See https://www.mediawiki.org/wiki/Manual:Configuration_settings#Copyright
## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
#$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
#$wgRightsUrl = "";
#$wgRightsText = "";
#$wgRightsIcon = "";


## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;


### Additional Extensions and Configuration Setting ###
## ExtensionDistributor
wfLoadExtension( 'ExtensionDistributor' );
$wgExtDistAPIConfig = array(
	'class' => 'GerritExtDistProvider',
	'apiUrl' => 'https://gerrit.wikimedia.org/r/projects/mediawiki%2F$TYPE%2F$EXT/branches',
	'tarballUrl' => 'https://extdist.wmflabs.org/dist/$TYPE/$EXT-$REF-$SHA.tar.gz',
	'tarballName' => '$EXT-$REF-$SHA.tar.gz',
	'repoListUrl' => 'https://gerrit.wikimedia.org/r/projects/?p=mediawiki/$TYPE/',
);
$wgExtDistSnapshotRefs = array(
	'master',
	'REL1_33',
);

## UploadWizard https://www.mediawiki.org/wiki/Extension:UploadWizard
wfLoadExtension( 'UploadWizard' );
#$wgApiFrameOptions = 'SAMEORIGIN'; // Needed to make UploadWizard work in IE, see https://phabricator.wikimedia.org/T41877
// This modifies the sidebar's "Upload file" link - probably in other places as well.
#$wgExtensionFunctions[] = function() {
#	$GLOBALS['wgUploadNavigationUrl'] = SpecialPage::getTitleFor( 'UploadWizard' )->getLocalURL();
#	return true;
#};
// Several other options are available through a configuration array.
$wgUploadWizardConfig = array(
	'tutorial' => array(
	 	'skip' => true
		), // Skip the tutorial
	'altUploadForm' => 'Special:Upload',
	'alternativeUploadToolsPage' => false, // Disable the link to alternative upload tools (default: points to Commons)
	'feedbackLink' => false, // Disable the link for feedback (default: points to Commons)
	'maxUploads' => 15, // Number of uploads with one form - defaults to 50
	'fileExtensions' => $wgFileExtensions // omitting this may cause errors
);

## VisualEditor https://www.mediawiki.org/wiki/Extension:VisualEditor
wfLoadExtension('VisualEditor');
$wgDefaultUserOptions['visualeditor-enable'] = 1;
$wgVirtualRestConfig['modules']['parsoid'] = array(
	'url' => 'http://localhost:8142',
	'domain' => 'localhost',
	'prefix' => ''
);
$wgSessionsInObjectCache = true;
$wgVirtualRestConfig['modules']['parsoid']['forwardCookies'] = true;
// OPTIONAL: Enable VisualEditor's experimental code features
#$wgDefaultUserOptions['visualeditor-enable-experimental'] = 1;
// Parsoid athentication without forwarding cookies. Allows VisualEditor to work in private wikis.
if ( !isset( $_SERVER['REMOTE_ADDR'] ) OR $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
	$wgGroupPermissions['*']['read'] = true;
	$wgGroupPermissions['*']['edit'] = true;
};

## UserMerge https://www.mediawiki.org/wiki/Extension:UserMerge
wfLoadExtension('UserMerge');
// By default nobody can use this function, enable for bureaucrat and sysop
$wgGroupPermissions['bureaucrat']['usermerge'] = true;
$wgGroupPermissions['sysop']['usermerge'] = true;
// Default is array( 'sysop' )
$wgUserMergeProtectedGroups = array( 'sysop' );

## SyntaxHighlight_GeSHi https://www.mediawiki.org/wiki/Extension:SyntaxHighlight
wfLoadExtension( 'SyntaxHighlight_GeSHi' );

## Scribunto https://www.mediawiki.org/wiki/Extension:Scribunto
wfLoadExtension( 'Scribunto' );
$wgScribuntoDefaultEngine = 'luastandalone';

## TemplateData https://www.mediawiki.org/wiki/Extension:TemplateData
wfLoadExtension( 'TemplateData' );
// OPTIONAL: Experimental dialog interface to edit templatedata JSON
#$wgTemplateDataUseGUI = true;

## TemplateStyles https://www.mediawiki.org/wiki/Extension:TemplateStyles
wfLoadExtension( 'TemplateStyles' );
// OPTIONAL: See https://www.mediawiki.org/wiki/Extension:TemplateStyles#Configuration for more details
// Default settings listed below
/* $wgTemplateStylesAllowedUrls[
    "audio" => [
        "<^https://upload\\.wikimedia\\.org/wikipedia/commons/>"
    ],
    "image" => [
        "<^https://upload\\.wikimedia\\.org/wikipedia/commons/>"
    ],
    "svg" => [
        "<^https://upload\\.wikimedia\\.org/wikipedia/commons/[^?#]*\\.svg(?:[?#]|$)>"
    ],
    "font" => [],
    "namespace" => [
        "<.>"
    ],
    "css" => []
]; */
#$wgTemplateStylesNamespaces[ 10 => true ];
#$wgTemplateStylesPropertyBlacklist[];
#$wgTemplateStylesAtRuleBlacklist[];
#$wgTemplateStylesUseCodeEditor = true;
#$wgTemplateStylesAutoParseContent = true;
#$wgTemplateStylesMaxStylesheetSize = 102400;
