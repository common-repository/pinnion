(function() {
	tinymce.create('tinymce.plugins.buttonPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mcebutton', function() {
				ed.windowManager.open({
					file : url + '/pinnion_post_popup.php', // file that contains HTML for our modal window
					width : 400 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 595 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('pinnion_tinymce_button', {title : 'Insert Pinnion', cmd : 'mcebutton', image: url + '/images/pinnion_menu_icon.png' });
		},

		getInfo : function() {
			return {
				longname : 'Pinnion Insert Button',
				author : 'Pinnion',
				authorurl : 'http://pinnion.com',
				infourl : 'http://pinnion.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('pinnion_tinymce_button', tinymce.plugins.buttonPlugin);

})();