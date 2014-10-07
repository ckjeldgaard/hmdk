(function($) {
  $('.quickedit').each(function() {
    var elm = $(this),
        className = this.className.match(/edit-(\w+)-(\d+)-?(\d+)?/),
        type = className[1] || '',
        id = className[2] || 0,
        addon = (className[3]) ? '/'+ className[3] : '',
        link,
        path;
        
    switch(type) {
      case 'node':
        path = '/node/'+ id +'/edit'+ addon;
        break;
      case 'term':
        path = '/taxonomy/term/'+ id + '/edit';
        break;
    }
    
    if(path) {
      link = $('<a href="'+ path +'" class="editbutton">Redigér</a>');
      elm.prepend(link.click(function(e) { e.stopPropagation(); }));
    }

  });
})(jQuery);