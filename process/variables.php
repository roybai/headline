<?php
define('_PAGE_TYPE_RESOURCE',0);
define('_LINK_TYPE_RESOURCE',1);
define('_TEXT_TYPE_RESOURCE',2);
define('_IMAGE_TYPE_RESOURCE',3);
define('_CSS_TYPE_RESOURCE',4);
define('_TXT_TYPE_RESOURCE',5);
define('_VIDEO_TYPE_RESOURCE',6);

//save all link in one grab, if page and sub page have same link like css, just save once.
$g_Link=array();
