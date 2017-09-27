<?php
if (!defined('VIDEOSTREAM_PLUGIN_DIR')) {
    define('VIDEOSTREAM_PLUGIN_DIR', dirname(__FILE__));
}
//add_plugin_hook('public_head', 'mediaelement_public_head');
//add_plugin_hook('admin_head', 'mediaelement_admin_head');
 
require_once VIDEOSTREAM_PLUGIN_DIR . '/VideoStreamMEPlugin.php';
//require_once VIDEOSTREAM_PLUGIN_DIR . '/functions.php';
//$VideoStreamMEPlugin = new VideoStreamMEPlugin;
//$VideoStreamMEPlugin->setUp();

class VideoStreamMEPlugin extends Omeka_Plugin_AbstractPlugin
{
    const DEFAULT_VIEWER_WIDTH = 640;
    const DEFAULT_VIEWER_HEIGHT = 480;
    const DEFAULT_VIEWER_CONTROL = 0;
    const DEFAULT_VIEWER_DISPLAY = 0;
    const DEFAULT_VIEWER_SKIN = 'basic';
    const DEFAULT_VIEWER_FLASH = 0;
    const DEFAULT_VIEWER_HTTP = 0;
    const DEFAULT_VIEWER_HLS = 0;
    const DEFAULT_VIEWER_PRIMARY = 0;
    const DEFAULT_VIEWER_AUTOSTART = 0;
    const DEFAULT_VIEWER_TUNING = 0;
    
    protected $_hooks = array('install',
    'uninstall',
    'config_form',
    'config',
    'video_items_show',
	'public_head',
	'admin_head'
    );

	protected $_options = array(
        'videostream_mediaelement_width_public' => 640,
        'videostream_mediaelement_height_public' => 480,
        'videostream_mediaelement_external_control' => 0,
        'videostream_display_current' => 0,
        'videostream_mediaelement_external_skin' => 'basic',
        'videostream_mediaelement_flash_streaming' => 0,
        'videostream_mediaelement_http_streaming' => 0,
        'videostream_mediaelement_hls_streaming' => 0,
        'videostream_mediaelement_flash_primary' => 0,
        'videostream_mediaelement_autostart' => 0,
        'videostream_display_tuning' => 1,
        'videostream_elements_ids' => '',
    );
    
	protected $_filters = array(
		'admin_items_form_tabs',
	);
    public function setUp()
    {
        add_shortcode('vidplayer', array('ShortcodeVideoPlugin', 'vidplayer'));
        parent::setUp();
    }
        
    public function hookInstall()
    {
        set_option('mediaelement_width_public', VideoStreamMEPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('mediaelement_height_public', VideoStreamMEPlugin::DEFAULT_VIEWER_HEIGHT);
        set_option('mediaelement_external_control', VideoStreamMEPlugin::DEFAULT_VIEWER_CONTROL);
        set_option('mediaelement_display_current', VideoStreamMEPlugin::DEFAULT_VIEWER_DISPLAY);
        set_option('mediaelement_external_skin', VideoStreamMEPlugin::DEFAULT_VIEWER_SKIN);
        set_option('mediaelement_flash_streaming', VideoStreamMEPlugin::DEFAULT_VIEWER_FLASH);
        set_option('mediaelement_http_streaming', VideoStreamMEPlugin::DEFAULT_VIEWER_HTTP);
        set_option('mediaelement_hls_streaming', VideoStreamMEPlugin::DEFAULT_VIEWER_HLS);
        set_option('mediaelement_flash_primary', VideoStreamMEPlugin::DEFAULT_VIEWER_PRIMARY);
        set_option('mediaelement_autostart', VideoStreamMEPlugin::DEFAULT_VIEWER_AUTOSTART);
        set_option('mediaelement_tuning', VideoStreamMEPlugin::DEFAULT_VIEWER_TUNING);

        $elementIds = array();
        $elementsTable = $this->_db->getTable('Element');
        $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Title');
        $elementIds['Dublin Core:Title'] = $element->id;
        $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Description');
        $elementIds['Dublin Core:Description'] = $element->id;
        $elements = $elementsTable->findBySet('Streaming Video');
        foreach ($elements as $element) {
            $elementIds['Streaming Video:' . $element->name] = $element->id;
        }
        $this->_options['videostream_elements_ids'] = json_encode($elementIds);
        $this->_installOptions();


 /*       $db = get_db();

		//if (!$db->query("Select name from {$db->prefix}plugins where name = 'ShortcodeVideo'")) {
		// Don't install if an element set named "Streaming Video" already exists.
	    //if ($db->getTable('ElementSet')->findByName('Streaming Video')) {
	    //      throw new Exception('An element set by the name "Streaming Video" already exists. You must delete that '
	    //                     . 'element set to install this plugin.');
		//	}
		//}

		$elementSetMetadata = array(
			'record_type'        => "Item", 
			'name'        => "Streaming Video", 
			'description' => "Elements needed for streaming video for the VideoStream Plugin"
		);
		$elements = array(
			array(
				'name'           => "Video Filename",
				'description'    => "Actual filename of the video on the video source server"
			), 
			array(
				'name'           => "Video Streaming URL",
				'description'    => "Actual URL of the streaming server without the filename"
			), 
			array(
				'name'           => "Video Type",
				'description'    => "Encoding for the video; mp4, flv, mov, and so forth"
			), 
			array(
				'name'           => "HLS Streaming Directory",
				'description'    => "Directory location on your server for the HLS .m3u8 file."
			), 
			array(
				'name'           => "HLS Video Filename",
				'description'    => "Filename for HLS video file. Include any subdirectories."
			), 
			array(
				'name'           => "HTTP Streaming Directory",
				'description'    => "Directory location for files to HTTP stream directly from Web Server."
			), 
			array(
				'name'           => "HTTP Video Filename",
				'description'    => "Actual filename of the video on the web server"
			), 
			array(
				'name'           => "Segment Start",
				'description'    => "Start point in video in either seconds or hh:mm:ss"
			), 
			array(
				'name'           => "Segment End",
				'description'    => "End point in video in either seconds or hh:mm:ss"
			), 
			array(
				'name'           => "Segment Type",
				'description'    => "Use segment type to help determine how segment is to be displayed. For instance, an event may encompass many scenes, etc."
			), 
			array(
				'name'           => "Show Item",
				'description'    => "Should item be shown in a list. Can be useful in cetain types of displays where you may not want to have all items shown."
			), 
			array(
				'name'           => "Video Source",
				'description'    => "Source of video. Streaming server, YouTube, etc."
			) 
			// etc.
		);
		//if (!$db->query("Select name from {$db->prefix}plugins where name = 'ShortcodeVideo'")) {
		//	insert_element_set($elementSetMetadata, $elements);
		//}
*/	}
    
    public  function hookUninstall()
    {
        delete_option('mediaelement_width_public');
        delete_option('mediaelement_height_public');
        delete_option('mediaelement_external_control');
        delete_option('mediaelement_display_current');
        delete_option('mediaelement_external_skin');
        delete_option('mediaelement_flash_streaming');
        delete_option('mediaelement_http_streaming');
        delete_option('mediaelement_hls_streaming');
        delete_option('mediaelement_flash_primary');
        delete_option('mediaelement_autostart');
        delete_option('mediaelement_tuning');
		//$db=get_db();
       	//if (!$db->query("Select name from {$db->prefix}plugins where name = 'ShortcodeVideo'")) {
		//	if ($elementSet = $db->getTable('ElementSet')->findByName("Streaming Video")) {
        //    	$elementSet->delete();
        //	}
		//}
    }
	
    /**
* Appends a warning message to the uninstall confirmation page.
*/
    public static function admin_append_to_plugin_uninstall_message()
    {
        echo '<p><strong>Warning</strong>: This will permanently delete the Streaming Video element set and all its associated metadata. You may deactivate this plugin if you do not want to lose data.</p>';
    }	
    
    public function hookConfigForm()
    {
        include 'config_form.php';
    }
    
    public function hookConfig()
    {
        if (!is_numeric($_POST['mediaelement_width_public']) ||
        !is_numeric($_POST['mediaelement_height_public'])) {
            throw new Omeka_Validator_Exception('The width and height must be numeric.');
        }
        set_option('mediaelement_width_public', $_POST['mediaelement_width_public']);
        set_option('mediaelement_height_public', $_POST['mediaelement_height_public']);
        set_option('mediaelement_external_control', $_POST['mediaelement_external_control']);
        set_option('mediaelement_external_skin', $_POST['mediaelement_external_skin']);
        set_option('mediaelement_display_current', $_POST['mediaelement_display_current']);
        set_option('mediaelement_flash_streaming', $_POST['mediaelement_flash_streaming']);
        set_option('mediaelement_http_streaming', $_POST['mediaelement_http_streaming']);
        set_option('mediaelement_hls_streaming', $_POST['mediaelement_hls_streaming']);
        set_option('mediaelement_flash_primary', $_POST['mediaelement_flash_primary']);
        set_option('mediaelement_autostart', $_POST['mediaelement_autostart']);
        set_option('mediaelement_tuning', $_POST['mediaelement_tuning']);
    }

	public function hookPublicHead($args)
	{
//          echo queue_css_file("vidStyle");
//	        echo queue_css_file("video-js");
//			echo queue_css_file("speccontrols"); 
	        
//            echo queue_css_file("jquery-ui-1.10.3.custom");
//            echo js_tag('pfUtils');
//            echo js_tag('jquery');
//            echo js_tag('jquery-ui-1.10.3.custom');
//			echo js_tag('video');
//			echo js_tag('youtube');
            echo js_tag('VideoStreamME');
			echo js_tag('mediaelement-and-player');
			
		}

		public function hookAdminHead($args)
		{
			echo queue_css_file("vidStyle");        
			echo queue_css_file("video-js");
//			echo queue_css_file("speccontrols");
	

			}

    public function hookVideoItemsShow($args)
    {
		//echo $this->shortcodes('[vidplayer ids=533-544 width="70%" height=360 float=left]');
	
        $this->append($args);
    }
    public function append($args)
	{
		echo $args['view']->shortcodes('[vidplayer ids='.metadata('item','id').' ext='.get_option('mediaelement_external_control').' current='.get_option('mediaelement_display_current').']');
	}		
	
	/*
	* Save jQuery slider to Streaming Video element and Description to Dublin Core before save item
	* use update_item in AfterSaveItem cause infinite loop where elements updated several times until above packet_limit
			$post = $_POST;
        $item = $args['record'];   
			update_item($item, array (
			'overwriteElementTexts' => "true"
		), array (
			'Streaming Video' => array (
				'Segment Start' => array ( array (
					'text' => "$slider_start",
					'html' => false)
				),
				'Segment End' => array ( array (
					'text' => "$slider_end",
					'html' => false)
				)
			),
			'Dublin Core' => array (
				'Description' => array ( array (
					'text' => "$description",
					'html' => false)
				)
			)
		))
	*
	*/
	

	public function filterAdminItemsFormTabs($tabs, $args)
    {

        // insert the Segmenting Video tab before the Miscellaneous tab
        //$item = $args['item'];
        //if(get_option('mediaelement_tuning')==0){
        //$tabs['Segment Tuning'] = $this->_segmentForm($item);
		$item = $args['item'];

        //$formSelectProperties = get_table_options('ItemRelationsProperty');
        //$subjectRelations = self::prepareSubjectRelations($item);
        //$objectRelations = self::prepareObjectRelations($item);

    if(get_option('mediaelement_tuning')==0){
        ob_start();
        include 'vidplayer.php';
        $content = ob_get_contents();
        ob_end_clean();

       	$tabs['Segment Tuning'] = $content;
	}
       	return $tabs;
	}
}
?>	
