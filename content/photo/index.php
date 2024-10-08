<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Галерея");
?><?$APPLICATION->IncludeComponent(
	"bitrix:photogallery",
	"",
	Array(
		"USE_LIGHT_VIEW" => "N",
		"IBLOCK_TYPE" => "gallery", 
		"IBLOCK_ID" => "13", 
		
		"SECTION_SORT_BY" => "UF_DATE",
		"SECTION_SORT_ORD" => "DESC",
		"ELEMENT_SORT_FIELD" => "id",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENTS_USE_DESC_PAGE" => "Y",

//		"UPLOAD_MAX_FILE_SIZE" => "1024",
		"ALBUM_PHOTO_THUMBS_SIZE" => "100",
		"ALBUM_PHOTO_SIZE" => "100",
		"THUMBNAIL_SIZE" => "90",
		"JPEG_QUALITY1" => "95",
		"ORIGINAL_SIZE" => "1280",
		"JPEG_QUALITY" => "95",
		"UPLOADER_TYPE" => "form",

		"ADDITIONAL_SIGHTS" => array(),
		"WATERMARK_MIN_PICTURE_SIZE" => "501",
		"PATH_TO_FONT" => "",
		"WATERMARK_RULES" => "USER",
		"USE_WATERMARK" => "Y",
		"WATERMARK" => "Y",
		"USE_RATING" => "Y",
		"MAX_VOTE" => "5",
		"VOTE_NAMES" => array(
			0 => "1",
			1 => "2",
			2 => "3",
			3 => "4",
			4 => "5"),
		
		"DATE_TIME_FORMAT_SECTION" => "d.m.Y",
		"DATE_TIME_FORMAT_DETAIL" => "d.m.Y",
		
		"SHOW_TAGS"	=>	"Y",
		"TAGS_PAGE_ELEMENTS"	=>	"150",
		"TAGS_PERIOD"	=>	"",
		"TAGS_INHERIT"	=>	"Y",
		"TAGS_FONT_MAX"	=>	"30",
		"TAGS_FONT_MIN"	=>	"10",
		"TAGS_COLOR_NEW"	=>	"3E74E6",
		"TAGS_COLOR_OLD"	=>	"C0C0C0",
		"TAGS_SHOW_CHAIN"	=>	"Y",
		
		"USE_COMMENTS" => "Y", 
		"COMMENTS_TYPE" => "forum", 
		"FORUM_ID" => "6",
		"BLOG_URL" => "",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"URL_TEMPLATES_READ" => "/communication/forum/messages/forum#FID#/topic#TID#/message#MID#/",
		"URL_TEMPLATES_PROFILE_VIEW" => "/communication/forum/user/#UID#/",
		"USE_CAPTCHA" => "N",
		"SHOW_LINK_TO_FORUM" => "N",
		"PREORDER" => "N",
		
		"SECTION_PAGE_ELEMENTS" => "25",
		"ELEMENTS_PAGE_ELEMENTS" => "50",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		
		"SHOW_LINK_ON_MAIN_PAGE" => array(
			0 => "id",
			1 => "shows",
			2 => "rating",
			3 => "comments"),
		"TEMPLATE_LIST" => ".default",
		"DISPLAY_AS_RATING" => "rating",
		"CELL_COUNT" => "0",
		"SLIDER_COUNT_CELL" => "3",
		
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/content/photo/",
		"SEF_URL_TEMPLATES" => array(
			"sections_top" => "index.php",
			"section" => "#SECTION_ID#/",
			"section_edit" => "#SECTION_ID#/action/#ACTION#/",
			"section_edit_icon" => "#SECTION_ID#/icon/action/#ACTION#/",
			"upload" => "#SECTION_ID#/action/upload/",
			"detail" => "#SECTION_ID#/#ELEMENT_ID#/",
			"detail_edit" => "#SECTION_ID#/#ELEMENT_ID#/action/#ACTION#/",
			"detail_slide_show" => "#SECTION_ID#/#ELEMENT_ID#/slide_show/",
			"detail_list" => "list/",
			"search" => "search/",
		), 
		
		"CACHE_TYPE" => "A", 
		"CACHE_TIME" => "3600", 
		
		"DISPLAY_PANEL" => "N",
		"SET_TITLE" => "Y"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>