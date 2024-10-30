(function ($) {
    'use strict';
    $(function() {
        // Página de listados
        $('#filtrar').click(function() {
            if ( ($('#select_provee').val() != '0') && ( ($('#select_catg').val() != '0') || ($('#select_mes').val() != '0') || ($('#select_trim').val() != '0') || ($('#select_year').val() != '0') ) ) {
                alert("Selecione SÓLO una opción");
                return false;
            } else if ( ($('#select_catg').val() != '0') && ( ($('#select_provee').val() != '0') || ($('#select_mes').val() != 0) || ($('#select_trim').val() != 0) || ($('#select_year').val() != 0) ) ) {
                alert("Selecione SÓLO una opción");
                return false;
            } else if ( ($('#select_mes').val() != 0) && ( ($('#select_provee').val() != '0') || ($('#select_catg').val() != '0') || ($('#select_trim').val() != 0) ) ) {
                alert("Selecione SÓLO una opción");
                return false;
            } else if ( ($('#select_mes').val() != 0) && ($('#select_year').val() == 0) ) {
                alert("Selecione mes y año");
                return false;
            } else if ( ($('#select_trim').val() != 0) && ( ($('#select_provee').val() != '0') || ($('#select_catg').val() != '0') || ($('#select_mes').val() != 0) ) ) {
                alert("Selecione SÓLO una opción");
                return false;
            } else if ( ($('#select_trim').val() != 0) && ($('#select_year').val() == 0) ) {
                alert("Selecione trimestre y año");
                return false;
            } else if ( ($('#select_year').val() != 0) && ( ($('#select_catg').val() != '0') || ($('#select_provee').val() != '0') ) ) {
                alert("Selecione SÓLO una opción");
                return false;
            }
        });
    });
})(jQuery);