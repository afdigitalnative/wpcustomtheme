(function() {
   tinymce.create('tinymce.plugins.video', {
      init : function(ed, url) {
         ed.addButton('video', { // Name for function
            title : 'Add video', // Name of Button
            image : url+'/images/clapperboard.png', // Icon image
            onclick : function() {
               var vidURL = prompt("Video URL", "http://");
               var format = prompt("Video Type (youtube or mp4)", "");

               if (format != null && format != ''){
                  if (vidURL != null && vidURL != '')
                     ed.execCommand('mceInsertContent', false, '[video url="'+vidURL+'" format="'+format+'"]');
                  else
                     ed.execCommand('mceInsertContent', false, '[video]'+format+'[/video]');
               }
               else{
                  if (vidURL != null && vidURL != '')
                     ed.execCommand('mceInsertContent', false, '[video url="'+vidURL+'"]');
                  else
                     ed.execCommand('mceInsertContent', false, '[video]');
               }
            }
         });
      }
   });
   tinymce.PluginManager.add('video', tinymce.plugins.video);
})();