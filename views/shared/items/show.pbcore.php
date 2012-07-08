<?php echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'; ?>
<pbcoreDescriptionDocument xmlns="http://www.pbcore.org/PBCore/PBCoreNamespace.html"
              xmlns:mt="http://www.iana.org/assignments/media-types/"
              xmlns:la="http://www.loc.gov/standards/iso639-2/"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		
	<?php $item = get_current_item(); ?>

	<pbcoreAssetDate dateType="Broadcast"><?php echo item('Item Type Metadata', 'Broadcast Date'); ?></pbcoreAssetDate>
	<pbcoreIdentifier source="Internet Archive"><?php echo item('Dublin Core', 'Identifier'); ?></pbcoreIdentifier>
	<pbcoreTitle><?php echo item('Dublin Core', 'Title'); ?></pbcoreTitle>
	<pbcoreTitle titleType="Episode"><?php echo item('Dublin Core', 'Episode Title'); ?></pbcoreTitle>
	<pbcoreTitle titleType="Series"><?php echo item('Dublin Core', 'Series Title'); ?></pbcoreTitle>

	<?php $tags = get_tags(array('sort_field' => 'name'), null); 
	foreach ($tags as $tag): ?>
	<pbcoreSubject source="Free tags"><?php echo $tag; ?></pbcoreSubject>
	<?php endforeach; ?>
	
	<pbcoreDescription><?php echo item('Dublin Core', 'Description'); ?></pbcoreDescription>
	<pbcoreCoverage>
		<coverage><?php echo "Not sure where to get geolocation info"; ?></coverage>
		<coverageType>Spatial</coverageType>
	</pbcoreCoverage>
	<pbcoreCreator>
		<creator><?php echo item('Dublin Core', 'Creator'); ?></creator>
		<creatorRole>Creator</creatorRole>
	</pbcoreCreator>   

	<pbcoreContributor>
		<contributor><?php echo item('Item Type Metadata', 'Interviewee'); ?></contributor>
		<contributorRole><?php echo "Interviewee"; ?></contributorRole>
	</pbcoreContributor>
	<pbcoreContributor>	
		<contributor><?php echo item('Item Type Metadata', 'Interviewer'); ?></contributor>
		<contributorRole><?php echo "Interviewer"; ?></contributorRole>
	</pbcoreContributor>
	<pbcoreContributor>
		<contributor><?php echo item('Item Type Metadata', 'Host'); ?></contributor>
		<contributorRole><?php echo "Host"; ?></contributorRole>
	</pbcoreContributor>

	<pbcoreRightsSummary>
	    <rightsSummary></rightsSummary>
	</pbcoreRightsSummary>

	<!-- loop files here.. need to know how they're stored. -->
	<pbcoreInstantiation>
		<instantiationIdentifier source="Filename"><?php echo "Not sure how to pull filename from Omeka DB"; ?></instantiationIdentifier>
		<instantiationDigital><?php echo item('Dublin Core', 'Format'); ?></instantiationDigital>
		<instantiationLocation><?php echo item('Dublin Core', 'Digital Location'); ?></instantiationLocation>
		<instantiationDuration><?php echo item('Item Type Metadata', 'Duration'); ?></instantiationLocation>
	</pbcoreInstantiation>
	
	<pbcoreInstantiation>
		<instantiationPhysical><?php echo item('Dublin Core', 'Physical Format'); ?></instantiationPhysical>
		<instantiationLocation><?php echo item('Dublin Core', 'Physical Location'); ?></instantiationLocation>
	</pbcoreInstantiation>

	<pbcoreAnnotation annotationType="Transcription"><?php echo item('Dublin Core', 'Transcription'); ?></pbcoreAnnotation>
	<pbcoreAnnotation annotationType="Notes"><?php //echo item('Dublin Core', 'Notes'); ?></pbcoreAnnotation>
	<pbcoreAnnotation annotationType="MusicUsed"><?php //echo item('Dublin Core', 'Music Used'); ?></pbcoreAnnotation>

</pbcoreDescriptionDocument>