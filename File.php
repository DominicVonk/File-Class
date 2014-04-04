<?php
class FileException extends ErrorException { }
class File {
	private $filename;
	private $append;
	private $oldcontent;
	private $content;
	private $saved = false;
	private function fileReportingError ($err_severity, $err_msg, $err_file, $err_line) {
		// error was suppressed with the @-operator
   	 	if (0 === error_reporting()) { return false;}
		throw new FileException ($err_msg, 0, $err_severity, $err_file, $err_line);
	}
	private static function getAbsolutePath($path) {
	        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
	        $absolutes = array();
	        foreach ($parts as $part) {
	            if ('.' == $part) continue;
	            if ('..' == $part) {
	                array_pop($absolutes);
	            } else {
	                $absolutes[] = $part;
	            }
	        }
	        return '/' . implode(DIRECTORY_SEPARATOR, $absolutes);
	}
	function __construct($filename, $append = false) {
		set_error_handler(array($this, 'fileReportingError'));
		if (stripos($filename, DIRECTORY_SEPARATOR) === 0) {
			$this->filename = $filename;
		} else {
			$this->filename = getcwd() . DIRECTORY_SEPARATOR . $filename;
		}
		$this->filename =  self::getAbsolutePath($this->filename);
		$this->append = $append;
		if (file_exists($this->filename)) {
			$content = file_get_contents($this->filename);
		}
		else {
			$content = "";
		}
		$this->oldcontent = $content;
		$this->content = $content;
	}
	public function __set($var, $val) {
		if (strtolower($var) === 'oldcontent') {
			$val = $this->oldcontent;
		}
		$this->{strtolower($var)} = $val;
		if (strtolower($var) !== 'oldcontent') {
			$this->saved = false;
		}
		return $this->{strtolower($var)};
	}
	public function &__get($var) {
		return $this->{strtolower($var)};
	}
	public function __unset($var) {
		$this->{strtolower($var)} = $oldcontent;
	}
	public function __isset($var) {
		return (isset($this->{strtolower($var)}));
	}
	public function save() { 
		if ($this->append) {
			file_put_contents($this->filename, $this->content, FILE_APPEND);
		}
		else {
			file_put_contents($this->filename, $this->content);
		}
		$this->saved = true;
	}
	public function __destruct() {
		if (!$this->saved) {
			if ($this->append) {
				file_put_contents($this->filename, $this->content, FILE_APPEND);
			}
			else {
				file_put_contents($this->filename, $this->content);
			}
		}
	}
	public static function move($filename, $newlocation) {
		if (stripos($filename, DIRECTORY_SEPARATOR) !== 0) {
			$filename = getcwd() . DIRECTORY_SEPARATOR . $filename;
		}
		$filename = self::getAbsolutePath($filename);
		if (stripos($newlocation, DIRECTORY_SEPARATOR) !== 0) {
			$newlocation = getcwd() . DIRECTORY_SEPARATOR . $newlocation;
		}
		$newlocation =  self::getAbsolutePath($newlocation);
		return rename($filename, $newlocation);	
	}
	public static function rename($filename, $newfilename) {
		if (stripos($filename, DIRECTORY_SEPARATOR) !== 0) {
			$filename = getcwd() . DIRECTORY_SEPARATOR . $filename;
		}
		$filename =  self::getAbsolutePath($filename);
		$nfilename = explode(DIRECTORY_SEPARATOR, $filename);
		unset($nfilename[count($nfilename)-1]);
		$nfilename = implode(DIRECTORY_SEPARATOR, $nfilename);
		$nfilename .= DIRECTORY_SEPARATOR . $newfilename;
		return rename($filename, $nfilename);
	}
	public static function copy($filename, $newlocation) {
		if (stripos($filename, DIRECTORY_SEPARATOR) !== 0) {
			$filename = getcwd() . DIRECTORY_SEPARATOR . $filename;
		}
		$filename = self::getAbsolutePath($filename);
		if (stripos($newlocation, DIRECTORY_SEPARATOR) !== 0) {
			$newlocation = getcwd() . DIRECTORY_SEPARATOR . $newlocation;
		}
		$newlocation =  self::getAbsolutePath($newlocation);
		return copy($filename, $newlocation);
	}
	public static function delete($filename) {
		if (stripos($filename, DIRECTORY_SEPARATOR) !== 0) {
			$filename = getcwd() . DIRECTORY_SEPARATOR . $filename;
		}
		$filename = self::getAbsolutePath($filename);
		return unlink ($filename);
	}
	public static function exists($filename) {
		if (stripos($filename, DIRECTORY_SEPARATOR) !== 0) {
			$filename = getcwd() . DIRECTORY_SEPARATOR . $filename;
		}
		$filename = self::getAbsolutePath($filename);
		return file_exists($filename);
	}
}
