(function ($) {
    'use strict';
    $(function() {
    var origin  = window.location.origin;
    var pdf_img_src = origin+'/wp-content/plugins/megabits-control-gastos/assets/pdf-file.jpg';
    var pdf_data = $('.file-info a');
    var pdf_href = pdf_data.attr('href');
    var pdf_height = $('#postbox-container-2 #normal-sortables').height();

    $('#postbox-container-2').before('<iframe id="visor-pdf" src="" width="59%" height="'+pdf_height+'"></iframe>');
    if (pdf_href) {
        $('#visor-pdf').attr('src', pdf_href);
        $('#visor-pdf').attr('height', pdf_height);
    } else {
        $('#visor-pdf').attr('height', pdf_height);
        $('#visor-pdf').attr('src', pdf_img_src);
    }
    
    const targetNode = pdf_data[0];
    const config = { attributes: true, childList: false, subtree: false };
    const callback = (mutationList, observer) => {
      for (const mutation of mutationList) {
        if (mutation.type === 'attributes') {
            pdf_href = pdf_data.attr('href');
            pdf_height = $('#postbox-container-2 #normal-sortables').height();
            if (pdf_href) {
                $('#visor-pdf').attr('src', pdf_href);
                $('#visor-pdf').attr('height', pdf_height);
            } else {
                $('#visor-pdf').attr('height', pdf_height);
                $('#visor-pdf').attr('src', pdf_img_src);
            }
        }
      }
    };
    const observer = new MutationObserver(callback);
    observer.observe(targetNode, config);

    });
    $('#publish').click(function() {
        var proveedor = $('#select2-acf-field_609d9b809d69c-container').attr('title');
        var categoria = $('#select2-acf-field_609d98853932e-container').attr('title');
        $('#title').val(proveedor+' - '+categoria);
    });
})(jQuery);