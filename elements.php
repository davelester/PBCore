<?php
$elements = array(
    
//Maps to Date Created (assetDate type:created).
	array(
		'label' => 'Date', 
		'name'  => 'date',
	),
	
//URI for the Internet Archive landing page for the item. Identifier source is always the internet archive.
    array(
		'label' => 'Identifier', 
		'name'  => 'identifier',
    ),
	
//Item title
    array(
		'label' => 'Title', 
        'name'  => 'title',
    ),
	
	array(
   		'label' => 'Episode Title', 
   		'name'  => 'episodeTitle',
   		'description' => 'The episode or piece to which a media item contributed.',
   		'data_type'   => 'Tiny Text',
   		'_refines'    => 'Title',
       ), 
         
	array(
  		'label' => 'Series Title', 
  		'name'  => 'seriesTitle',
  		'description' =>'If applicable, the larger series to which the episode or piece contributed.',
  		'data_type'   => 'Tiny Text',
         '_refines'    => 'Title',
        ),

//We don't currently have this field in our mapping doc.    
    array(
		'label' => 'Description', 
		'name'  => 'description',
     ),

//See if we can autofill make editable. 	
	array(
		'label' => 'Creator', 
		'name'  => 'creator',
	),
	
	array(
		'label' => 'Rights', 
		'name'  => 'rights',
	),
	
	array(
		'label'       => 'Music/Sound Used', 
		'name'        => 'music', 
		'description' => 'Details on music or other sound clips that contributed to the piece. May include title, artist, album, timestamp, producer and record label information.',
		'data_type'   => 'Tiny Text',
	),
	
	array(
   		'label' => 'Date Peg', 
   		'name'  => 'datePeg',
   		'description' => 'A holidays or other date relevant to the item.',
   		'data_type'   => 'Tiny Text',
       ),
	
	array(
		'label'       => 'Notes', 
		'name'        => 'notes', 
		'description' => 'Any other notes or information about the media item, including bibliography/research information, contact information, and legacy metadata.',
		'data_type'   => 'Text',
	),

//Physical format comes with a picklist
	array(
		'label'  => 'Physical Format', 
		'name'  => 'physicalFormat', 
		'description' => 'The format of a particular version or rendition of a media item as it exists in an actual physical form that occupies physical space (e.g., a tape on a shelf), rather than as a digital file residing on a server or hard drive.', 
		'data_type'   => 'Tiny Text',
	),
	
//Instantiation Identifer will be hardcoded.
	array(
		'label' => 'Physical Location', 
		'name'  => 'physicalLocation',
		'description' => 'An address for a physical media item. For an organization or producer acting as caretaker of a media resource, this field may contain information about a specific shelf location for an item, including an organization\'s name, departmental name, shelf ID and contact information.',
		'data_type'   => 'Tiny Text',
	 ),
	
//Display digital format also comes with a picklist. Mimetype of original uploaded file. Should be the mimetype of whatever the instantiation is. Potentially prepopulate. 
	array(
		'label' => 'Format', 
		'name'  => 'format',
	),
		
//Instantiation Identifer will be hardcoded.
	array(
		'label' => 'Digital Location', 
		'name'  => 'digitalLocation',
		'description' => 'An address for a digital media item. Employs an unambiguous reference or identifier for a digital rendition/instantiation of a media item and may include domain, path, filename or html page. This includes URIs for each digital file format created by the Internet Archive (will have multiple values).',
		'data_type'   => 'Tiny Text',
	  ),
	
	array(
		'label' => 'Transcription', 
		'name'  => 'transcription',
		'description' => 'The textual transcription of the media item.',
		'data_type'   => 'Text',
	  ),
);