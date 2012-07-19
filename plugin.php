<?php
add_plugin_hook('install', 'install');
add_filter('define_response_contexts', 'pbcoreOutputReponseContext');
add_filter('define_action_contexts', 'pbcoreOutputActionContext');
add_plugin_hook('public_theme_header', 'pbcoreThemeHeader');

function install() {
	$elementSetMetadata = array(
	    'name'        => "PBCore", 
	    'description' => "PBCore is metadata standard for audiovisual media developed by the public broadcasting community. See http://www.pbcore.org/documentation/"
	);
	$elements = array(

	//Maps to Date Created (assetDate type:created).
		array(
			'label' => 'Date', 
			'name'  => 'Date',
		),

	//Maps to Date Broadcast (assetDate type:broadcast).
		array(
			'label' => 'Date Broadcast', 
			'name'  => 'Date Broadcast',
		),

	//AUTOFILL: URI for the Omeka landing page for the item. Identifier source is always Omeka.
	    array(
			'label' => 'Identifier', 
			'name'  => 'Identifier',
	    ),

	//Item title
	    array(
			'label' => 'Title', 
	        'name'  => 'Title',
	    ),

		array(
	   		'label' => 'Episode Title', 
	   		'name'  => 'Episode Title',
	   		'description' => 'The episode or piece to which a media item contributed.',
	   		'data_type'   => 'Tiny Text',
	   		'_refines'    => 'Title',
	       ), 

		array(
	  		'label' => 'Series Title', 
	  		'name'  => 'Series Title',
	  		'description' =>'If applicable, the larger series to which the episode or piece contributed.',
	  		'data_type'   => 'Tiny Text',
	         '_refines'    => 'Title',
	        ),

	//We should have this field in our mapping doc.    
	    array(
			'label' => 'Description', 
			'name'  => 'Description',
	     ),

	//AUTOFILL: but make editable. 	
		array(
			'label' => 'Creator', 
			'name'  => 'Creator',
		),

	//We should have this field in our mapping doc.    
	    array(
			'label' => 'Interviewee', 
			'name'  => 'Interviewee',
	     ),

	//We should have this field in our mapping doc.    
	    array(
			'label' => 'Host', 
			'name'  => 'Host',
	     ),

	//We should have this field in our mapping doc.    
	    array(
			'label' => 'Interviewer', 
			'name'  => 'Interviewer',
	     ),

		array(
			'label' => 'Rights', 
			'name'  => 'Rights',
		),

	//Physical format comes with a picklist
		array(
			'label'  => 'Physical Format', 
			'name'  => 'Physical Format', 
			'description' => 'The format of a particular version or rendition of a media item as it exists in an actual physical form that occupies physical space (e.g., a tape on a shelf), rather than as a digital file residing on a server or hard drive.', 
			'data_type'   => 'Tiny Text',
		),

	//Display digital format also comes with a picklist. Mimetype of original uploaded file. Should be the mimetype of whatever the instantiation is. Potentially prepopulate. 
		array(
			'label' => 'Format', 
			'name'  => 'Format',
		),

	//This is not hardcoded.
		array(
			'label' => 'Physical Location', 
			'name'  => 'Physical Location',
			'description' => 'An address for a physical media item. For an organization or producer acting as caretaker of a media resource, this field may contain information about a specific shelf location for an item, including an organization\'s name, departmental name, shelf ID and contact information.',
			'data_type'   => 'Tiny Text',
		 ),

	//AUTOFILL: Internet Archive landing page for the item. Maps to instantiationLocation in PBCore XML.
		array(
			'label' => 'Digital Location', 
			'name'  => 'Digital Location',
			'description' => 'An address for a digital media item. Employs an unambiguous reference or identifier for a digital rendition/instantiation of a media item and may include domain, path, filename or html page. This includes URIs for each digital file format created by the Internet Archive (will have multiple values).',
			'data_type'   => 'Tiny Text',
		  ),

	//AUTOFILL: Can we automatically detect duration of files when they are uploaded?
		array(
			'label' => 'Duration', 
			'name'  => 'Duration',
		),

		array(
			'label'       => 'Music/Sound Used', 
			'name'        => 'Music/Sound Used', 
			'description' => 'Details on music or other sound clips that contributed to the piece. May include title, artist, album, timestamp, producer and record label information.',
			'data_type'   => 'Tiny Text',
		),

		array(
	   		'label' => 'Date Peg', 
	   		'name'  => 'Date Peg',
	   		'description' => 'A holidays or other date relevant to the item.',
	   		'data_type'   => 'Tiny Text',
	       ),

		array(
			'label'       => 'Notes', 
			'name'        => 'Notes', 
			'description' => 'Any other notes or information about the media item, including bibliography/research information, contact information, and legacy metadata.',
			'data_type'   => 'Text',
		),

		array(
			'label' => 'Transcription', 
			'name'  => 'Transcription',
			'description' => 'The textual transcription of the media item.',
			'data_type'   => 'Text',
		  ),
	);
	insert_element_set($elementSetMetadata, $elements);

	// Extend the Oral History Item Type to add Broadcast Date & Music/Sound Used
	$item_type_name = 'Oral History';

	$_elements = array(
	    array('name'        => 'Broadcast Date',
	          'description' => '',
	          'data_type'   => 'Tiny Text'),

	    array('name'        => 'Host',
	          'description' => '',
	          'data_type'   => 'Tiny Text'),
		array(
			'label'       => 'Music/Sound Used', 
			'name'        => 'Music', 
			'description' => 'Details on music or other sound clips that contributed to the piece. May include title, artist, album, timestamp, producer and record label information.',
			'data_type'   => 'Tiny Text',
		),
	);
	
	$db = get_db();
	$itemType = $db->getTable('ItemType')->findByName($item_type_name);
    $itemType->addElements($_elements);
    $itemType->save();
}

add_filter('admin_items_form_tabs', 'pbcore_items_form_tabs');

function pbcore_items_form_tabs($tabs, $item)
{
	unset($tabs['Dublin Core']);
	return $tabs;
}

function pbcoreOutputReponseContext($context)
{
    $context['pbcore'] = array('suffix'  => 'pbcore', 
                            'headers' => array('Content-Type' => 'text/xml'));

    return $context;
}

function pbcoreOutputActionContext($context, $controller)
{
    if ($controller instanceof ItemsController) {
        $context['show'][] = 'pbcore';
    }

    return $context;
}

function pbcoreThemeHeader()
{
	echo pbcoreOutput();
}
   
function pbcoreOutput()
{
    $request = Zend_Controller_Front::getInstance()->getRequest();

	if ($request->getControllerName() == 'items' && $request->getActionName() == 'show') {
	    return '<link rel="alternate" type="application/rss+xml" href="'.item_uri().'?output=pbcore" id="pbcore"/>' . "\n";
	}
}