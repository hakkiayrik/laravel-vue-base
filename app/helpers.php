<?php

use Illuminate\Support\Facades\Storage;

function create_file_name($path, $extension)
{
	$fileName = uniqid('media_') . "." .$extension;

	return $fileName;
}