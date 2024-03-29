<?

//--------------------------------------------------------------------------------------------------
//|	placeholder which is shown when the actual image is not yet available
//--------------------------------------------------------------------------------------------------
//opt: size - name of a preset image size, default is width300 [string]
//opt: display - css disply type (block|inline) [string]

function images_unavailable($args) {

	$display = 'block';						//%	css display type [string]
	$size = 'width300';						//%	image size label [string]
	$style = '';							//%	per-image style attribute [string]
	$html = '';								//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments
	//----------------------------------------------------------------------------------------------
	if (true == array_key_exists('size', $args)) { $size = $args['size']; }
	if (true == array_key_exists('display', $args)) { $display = $args['display']; }
	
	if ('inline' == $display) { $style = " style='display: inline;'"; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$html = ''
	 . "<img"
	 . " src='%%serverPath%%data/images/unavailable/unavailable_" . $size . ".jpg' "
	 . " class='rounded'"
	 . $style
	 . " border='0'"
	 . " />";

	return $html;
}

?>
