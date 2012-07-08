<?php
add_plugin_hook('install', 'PBCorePlugin::install');
add_plugin_hook('uninstall', 'PBCorePlugin::uninstall');
add_plugin_hook('upgrade', 'PBCorePlugin::upgrade');
add_plugin_hook('admin_append_to_plugin_uninstall_message', 'PBCorePlugin::adminAppendToPluginUninstallMessage');
add_filter('define_response_contexts', 'PBCorePlugin::outputReponseContext');
add_filter('define_action_contexts', 'PBCorePlugin::outputActionContext');
add_plugin_hook('public_theme_header', 'PBCorePlugin::themeHeader');

class PBCorePlugin
{
    private $_db;
    private $_elements;
    private $_dcElements = array('Title', 'Subject', 'Description', 
                                 'Creator', 'Source', 'Publisher', 
                                 'Date', 'Contributor', 'Rights', 
                                 'Relation', 'Format', 'Language', 
                                 'Type', 'Identifier', 'Coverage');
    
    public function __construct()
    {
        $this->_db = get_db();
        $this->_setElements();
    }
    
    public static function install()
    {
        $pbc = new PBCorePlugin;
        $pbc->_createTable();
        $pbc->_addElements();
        $pbc->_insertRelationships();
    }
    
    public static function uninstall()
    {
        $pbc = new PBCorePlugin;
        $pbc->_dropTable();
        $pbc->_deleteElements();
        $pbc->_resetOrder();
    }
    
    public static function upgrade($oldVersion, $newVersion)
    {
        $db = get_db();
        switch ($oldVersion) {
            case '1.0':
                // Fixes a bug that incorrectly set the record type of the new 
                // elements to "Item." Sets them to "All" instead.
                $sql = "
                UPDATE `{$db->prefix}elements` e 
                SET e.`record_type_id` = (
                    SELECT rt.`id` 
                    FROM `{$db->prefix}record_types` rt 
                    WHERE rt.`name` = 'All'
                )
                WHERE e.`element_set_id` = (
                    SELECT es.`id` 
                    FROM `{$db->prefix}element_sets` es 
                    WHERE es.`name` = 'Dublin Core'
                )";
                $db->query($sql);
            default:
                break;
        }
    }
    
    public static function adminAppendToPluginUninstallMessage()
    {
        echo '<p><strong>Warning</strong>: This will remove all the PBCore 
        elements added by this plugin and permanently delete all element texts 
        entered in those fields.</p>';
    }
    
	public function getElements()
    {
        return $this->_elements;
    }

	public static function outputReponseContext($context)
	{
	    $context['pbcore'] = array('suffix'  => 'pbcore', 
	                            'headers' => array('Content-Type' => 'text/xml'));

	    return $context;
	}

	public static function outputActionContext($context, $controller)
	{
	    if ($controller instanceof ItemsController) {
	        $context['show'][] = 'pbcore';
	    }

	    return $context;
	}

	private function themeHeader()
	{
		echo $this->pbcoreOutput();
	}
    
    private function pbcoreOutput()
	{
	    $request = Zend_Controller_Front::getInstance()->getRequest();

		if (($request->getControllerName() == 'items' && $request->getActionName() == 'show') || ($request->getControllerName() == 'index' && $request->getActionName() == 'index')) {
		    return '<link rel="alternate" type="application/rss+xml" href="'.items_output_uri('pbcore').'" id="pbcore"/>' . "\n";
		}
	}

    private function _setElements()
    {
        include 'elements.php';
        $this->_elements = $elements;
    }
    
    private function _createTable()
    {
        // Create the relationships table. The data in this table isn't used 
        // yet, but I'm anticipating they will be necessary in the future.
        $sql = "
        CREATE TABLE IF NOT EXISTS 
`{$this->_db->prefix}pbcore_relationships` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `element_id` int(10) unsigned NOT NULL,
            `refines_element_id` int(10) unsigned NOT NULL,
            PRIMARY KEY  (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $this->_db->query($sql);
    }
    
    private function _addElements()
    {
        // Add the new elements to the Dublin Core element set. 
        $elementSet = $this->_getElementSet();
        
        // Temporarily set the order of all elements to NULL.
        $this->_setNullOrder();
        
        // Iterate through the elements.
        foreach ($this->_elements as $key => $element) {
            
            // The element already exists.
            if (in_array($element['label'], $this->_dcElements)) {
                $e = $this->_getElement($element['label']);
            
            // Build a new element.
            } else {
                $e = new Element;
                $e->record_type_id = $this->_getRecordTypeId('All');
                $e->data_type_id   = $this->_getDataTypeId($element['data_type']);
                $e->element_set_id = $elementSet->id;
                $e->name           = $element['label'];
                $e->description    = $element['description'];
            }
            $e->order = $key + 1;
            $e->save();
        }
    }
    
    private function _insertRelationships()
    {
        foreach ($this->_elements as $element) {
            $elementId = $this->_getElement($element['label'])->id;
            if (isset($element['_refines'])) {
                $refinesElementId = $this->_getElement($element['_refines'])->id;
            } else {
                $refinesElementId = 0;
            }
            $sql = "
            INSERT INTO `{$this->_db->prefix}pbcore_relationships` (
                `element_id` ,
                `refines_element_id`
            ) VALUES (?, ?)";
            $this->_db->query($sql, array($elementId, $refinesElementId));
        }
    }
    
    private function _dropTable()
    {
        $sql = "DROP TABLE IF EXISTS `{$db->prefix}pbcore_relationships`";
        $this->_db->query($sql);
    }
    
    private function _deleteElements()
    {
        // Delete all the elements and element texts.
        foreach ($this->_elements as $element) {
            if (!in_array($element['label'], $this->_dcElements)) {
                $this->_getElement($element['label'])->delete();
            }
        }
    }
    
    private function _resetOrder()
    {
        $this->_setNullOrder();
        
        foreach ($this->_dcElements as $key => $elementName) {
            $e = $this->_getElement($elementName);
            $e->order = $key + 1;
            $e->save();
        }
    }
    
    private function _getElementSet()
    {
        return $this->_db->getTable('ElementSet')->findByName('Dublin Core');
    }
    
    private function _getElement($elementName)
    {
        return $this->_db
                    ->getTable('Element')
                    ->findByElementSetNameAndElementName('Dublin Core', $elementName);
    }
    
    private function _getRecordTypeId($recordTypeName)
    {
        return $this->_db->getTable('RecordType')->findIdFromName($recordTypeName);
    }
    
    private function _getDataTypeId($dataTypeName)
    {
        return $this->_db->getTable('DataType')->findIdFromName($dataTypeName);
    }
    
    private function _setNullOrder()
    {
        $sql = "
        UPDATE `{$this->_db->prefix}elements` e 
        SET e.`order` = NULL 
        WHERE e.`element_set_id` = (
            SELECT es.`id` 
            FROM `{$this->_db->prefix}element_sets` es 
            WHERE es.`name` = 'Dublin Core'
        )";
        $this->_db->query($sql);
    }
}