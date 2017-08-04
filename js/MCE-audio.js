(function() {
   tinymce.create('tinymce.plugins.audio', {
      init : function(ed, url) {
         ed.addButton('audio', { // Name for function
            title : 'Add audio', // Name of Button
            image : url+'/images/headphone.png', // Icon image
            onclick : function() {
               var audURL = prompt("Audio URL", "http://");

               ed.execCommand('mceInsertContent', false, '[audio url="'+audURL+'"]');
            }
         });
      }
   });
   tinymce.PluginManager.add('audio', tinymce.plugins.audio);
})();