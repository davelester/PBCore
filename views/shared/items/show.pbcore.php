<?php echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'; ?>
<PBCoreDescriptionDocument xmlns="http://www.PBCore.org/PBCore/PBCoreNamespace.html"
              xmlns:mt="http://www.iana.org/assignments/media-types/"
              xmlns:la="http://www.loc.gov/standards/iso639-2/"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		
	<?php $item = get_current_item(); ?>

	<PBCoreAssetDate dateType="Broadcast"><?php echo item('PBCore', 'Date Broadcast'); ?></PBCoreAssetDate>
	<PBCoreIdentifier source="Internet Archive"><?php echo item('PBCore', 'Identifier'); ?></PBCoreIdentifier>
	<PBCoreTitle><?php echo item('PBCore', 'Title'); ?></PBCoreTitle>
	<PBCoreTitle titleType="Episode"><?php echo item('PBCore', 'Episode Title'); ?></PBCoreTitle>
	<PBCoreTitle titleType="Series"><?php echo item('PBCore', 'Series Title'); ?></PBCoreTitle>

	<?php $tags = get_tags(array('sort_field' => 'name'), null); 
	foreach ($tags as $tag): ?>
	<PBCoreSubject source="Free tags"><?php echo $tag; ?></PBCoreSubject>
	<?php endforeach; ?>
	
	<PBCoreDescription><?php echo item('PBCore', 'Description'); ?></PBCoreDescription>
	<PBCoreCoverage>
		<coverage><?php if (function_exists('geolocation_get_location_for_item') && geolocation_get_location_for_item($item, true)) { $location = geolocation_get_location_for_item($item, true); echo $location->address; } ?></coverage>
		<coverageType>Spatial</coverageType>
	</PBCoreCoverage>
	<PBCoreCreator>
		<creator><?php echo item('PBCore', 'Creator'); ?></creator>
		<creatorRole>Creator</creatorRole>
	</PBCoreCreator>   

	<PBCoreContributor>
		<contributor><?php echo item('PBCore', 'Interviewee'); ?></contributor>
		<contributorRole><?php echo "Interviewee"; ?></contributorRole>
	</PBCoreContributor>
	<PBCoreContributor>	
		<contributor><?php echo item('PBCore', 'Interviewer'); ?></contributor>
		<contributorRole><?php echo "Interviewer"; ?></contributorRole>
	</PBCoreContributor>
	<PBCoreContributor>
		<contributor><?php echo item('PBCore', 'Host'); ?></contributor>
		<contributorRole><?php echo "Host"; ?></contributorRole>
	</PBCoreContributor>

	<PBCoreRightsSummary>
	    <rightsSummary><?php echo item('PBCore', 'Rights'); ?></rightsSummary>
	</PBCoreRightsSummary>

	<PBCoreInstantiation>
		<instantiationIdentifier source="Filename"><?php echo item('PBCore', 'Identifier'); ?></instantiationIdentifier>
		<instantiationDigital><?php echo item('PBCore', 'Format'); ?></instantiationDigital>
		<instantiationLocation><?php echo item('PBCore', 'Digital Location'); ?></instantiationLocation>
		<instantiationDuration><?php echo item('PBCore', 'Duration'); ?></instantiationDuration>
	</PBCoreInstantiation>
	
	<PBCoreInstantiation>
		<instantiationPhysical><?php echo item('PBCore', 'Physical Format'); ?></instantiationPhysical>
		<instantiationLocation><?php echo item('PBCore', 'Physical Location'); ?></instantiationLocation>
	</PBCoreInstantiation>

	<PBCoreAnnotation annotationType="Transcription"><?php echo item('PBCore', 'Transcription'); ?></PBCoreAnnotation>
	<PBCoreAnnotation annotationType="Notes"><?php echo item('PBCore', 'Notes'); ?></PBCoreAnnotation>
	<PBCoreAnnotation annotationType="MusicUsed"><?php echo item('PBCore', 'Music/Sound Used'); ?></PBCoreAnnotation>

</PBCoreDescriptionDocument>