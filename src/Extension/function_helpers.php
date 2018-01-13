<?php

if (!function_exists('mapping_path')) {
	function mapping_path () {
		return base_path() . DIRECTORY_SEPARATOR . "mappings";
	}
}