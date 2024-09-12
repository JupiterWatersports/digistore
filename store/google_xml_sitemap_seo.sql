INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible)
 VALUES ('289', 'Google XML SEO', 'Google XML Sitemap SEO Options', '29', '1');

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES (NULL,'Sitemap file', 'GOOGLE_XML_SITEMAP_SEO', 'Standard', 'Which file to use for the sitemap creation. Use Standard unless it doesn\'t work correctly.<br />(Standard=on Alternate=off)', '289', '1', 'tep_cfg_select_option(array(\'Standard\', \'Alternate\'), ', now(), NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES (NULL,'Enable Manufacturers Map', 'GOOGLE_XML_SITEMAP_CREATE_MANU', 'false', 'Set to true if you would like a site map created for your manufactureres.<br />(true=on false=off)', '289', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES (NULL,'Enable Specials Map', 'GOOGLE_XML_SITEMAP_CREATE_SPECIALS', 'false', 'Set to true if you would like a site map created for your specials.<br />(true=on false=off)', '289', '7', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES (NULL,'Enable Standard Pages Map', 'GOOGLE_XML_SITEMAP_CREATE_PAGES', 'false', 'Set to true if you would like a site map created for your standard pages.<br />(true=on false=off)', '289', '10', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES (NULL,'Enable Diagnostic Output', 'GOOGLE_XML_SITEMAP_SHOW_DIAGNOSTIC', 'false', 'Set to true if you would like debug information displayed. This is useful if the site maps are not being created correctly.<br />(true=on false=off)', '289', '15', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now(), NULL);
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, use_function)
 VALUES (NULL, 'Exclude these pages', 'GOOGLE_XML_SITEMAP_EXCLUDE_PAGES', '', 'Add these pages to the built-in file exclude list. This will prevent the links from being added to the pages site map.', '289', '20', NULL, now(), NULL);

