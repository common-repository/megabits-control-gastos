<?php 
function mgb_gastos_pagination($gastos, $p, $gastos_max_display, $top = 0) {
	$gastos_count = count($gastos);
	if ($gastos_count > 0) $gastos_pages = ceil($gastos_count/$gastos_max_display);
	$gastos_pages = isset($gastos_pages) ? $gastos_pages : 1;
	$html = '<div class="pagination_div">';
	$html .= '<span>'.$gastos_count.' facturas </span>';
	if ($gastos_pages <= 10) {
		for ( $i = 1; $i <= $gastos_pages; $i++ ) {
			$html .= mgb_actual_page($i, $p);
		}
	} else {
		for ( $i = 1; $i <= $gastos_pages; $i++ ) {
			if ($p < 3) { 
				if ($i <=3) $html .= mgb_actual_page($i, $p);
				if ($i == $gastos_pages) $html .= ' . . . <input form="gastos_listing" name="post_page" type="submit" value="'.$i.'" class="boton_pagina" /> ';
			} elseif ($p <= $gastos_pages-2) {
				if ((($i >= $p-1) && ($i <= $p+1)) || ($i == 1)) {
					$html .= mgb_actual_page($i, $p);
					if (($p-1 != 2) && ($i == 1)) $html .= ' . . . ';
				}
				if (($p < $gastos_pages-2) && ($i == $gastos_pages)) $html .= ' . . . ';
				if ($i == $gastos_pages) $html .= '<input form="gastos_listing" name="post_page" type="submit" value="'.$i.'" class="boton_pagina" /> ';
			} else {
				if (($i == 1) || ($i > $gastos_pages-3)) {
					$html .= mgb_actual_page($i, $p);
					if ($i == 1) $html .= ' . . . ';
				}
			}
		}
	}
	$html .= '</div>';
	return print_r($html);
}
function mgb_actual_page($i, $p) {
	if ($i == $p) {
		$html .= '<input form="gastos_listing" name="post_page" type="submit" value="'.$i.'" class="boton_pagina pagination_actual_page" /> ';
	} else {
		$html .= '<input form="gastos_listing" name="post_page" type="submit" value="'.$i.'" class="boton_pagina" /> ';
	}
	return $html;
}
?>