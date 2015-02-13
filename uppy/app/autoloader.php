<?php
function autoloader($class) {
	if (file_exists('uppy/app/' . $class . '.php'))
	{
		
		include 'uppy/app/' . $class . '.php';
	}
}