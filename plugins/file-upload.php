<?php
//! delete

/** Edit fields ending with "_path" by <input type="file"> and link to the uploaded files from select
* @link https://www.adminer.org/plugins/#use
* @author Jakub Vrana, https://www.vrana.cz/
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerFileUpload {
	/** @access protected */
	var $uploadPath, $displayPath, $extensions;

	/**
	* @param string prefix for uploading data (create writable subdirectory for each table containing uploadable fields)
	* @param string prefix for displaying data, null stands for $uploadPath
	* @param string regular expression with allowed file extensions
	*/
	function __construct($uploadPath = "../static/data/", $displayPath = null, $extensions = "[a-zA-Z0-9]+") {
		$this->uploadPath = $uploadPath;
		$this->displayPath = ($displayPath !== null ? $displayPath : $uploadPath);
		$this->extensions = $extensions;
	}

	function editFunctions($field) {
		if (preg_match('~(.*)_path$~', $field["field"], $regs)) {
			return array("string");
		}
	}

	function editInput($table, $field, $attrs, $value) {
		if (preg_match('~(.*)_path$~', $field["field"], $regs)) {
			return "<a href='$this->displayPath$_GET[edit]/$regs[1]-$value'>" . $value . "</a><input type='hidden'$attrs value='$value'><br /><input type='file'$attrs>";
		}
	}

	function processInput($field, $value, $function = "") {
		$fname = $field["field"];
		// check $_FILES[$name], because search by this field does not have $_FILES
		if (isset($_FILES["fields"]["name"][$fname]) && preg_match('~(.*)_path$~', $fname, $regs)) {
			$table = ($_GET["edit"] != "" ? $_GET["edit"] : $_GET["select"]);

			if ($_FILES["fields"]["error"][$fname] || !preg_match("~(\\.($this->extensions))?\$~", $_FILES["fields"]["name"][$fname], $regs2)) {
				return false;
			}
			//! unlink old
			$filename = uniqid() . $regs2[0];
			if (!move_uploaded_file($_FILES["fields"]["tmp_name"][$fname], "$this->uploadPath$table/$regs[1]-$filename")) {
				return false;
			}
			return q($filename);
		}
	}

	function selectVal($val, &$link, $field, $original) {
		if ($val != "" && preg_match('~(.*)_path$~', $field["field"], $regs)) {
			$link = "$this->displayPath$_GET[select]/$regs[1]-$val";
		}
	}

}
