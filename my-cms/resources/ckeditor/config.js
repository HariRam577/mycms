/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

// CKEDITOR.editorConfig = function( config )
// {
// 	// Define changes to default configuration here. For example:
// 	// config.language = 'fr';
// 	// config.uiColor = '#AADC6E';
// };


   CKEDITOR.editorConfig = function( config ) {
    config.toolbarGroups = [
                           { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                           { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                           { name: 'links' },
                           { name: 'insert' },
                           { name: 'forms' },
                           { name: 'tools' },
                           { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
                           { name: 'others' },
                           '/',
                           { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                           { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                           { name: 'styles' },
                           { name: 'colors' },
                           { name: 'about' }
    ];

    config.removeButtons = 'Underline,Subscript,Superscript';

    config.format_tags = 'p;h1;h2;h3;pre';

    config.removeDialogTabs = 'image:advanced;link:advanced';

    config.extraPlugins += (config.extraPlugins.length == 0 ? '' : ',') + 'ckeditor_wiris';

   // Check if 'wiris' group already exists in the toolbar_Full
    var wirisGroupExists = config.toolbar_Full && config.toolbar_Full.some(function(group) {
        return group.name === 'wiris';
    });

    // If 'wiris' group does not exist, add it
    if (!wirisGroupExists) {
        config.toolbar_Full = config.toolbar_Full || []; // Ensure toolbar_Full is an array
        config.toolbar_Full.push({ 
name: 'wiris', 
items : [ 'ckeditor_wiris_formulaEditor', 'ckeditor_wiris_CAS' ]
        });
    }
};