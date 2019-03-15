<?php

ob_start();

class UploadImage {

    private $url = 'ImageFiles/';
    private $prefix = 'Image';
    private $length = 52428800;
    private $ext = '.jpg';
    private $filename = '';
    private $newname = '';
    private $name = '';
    private $error = '';

    public function __construct() {
    }

    public function setURL($url) {
        if (is_string($url)) {
            $this->url = $url;
        }
    }

    public function setPrefix($prefix) {
        if (is_string($prefix)) {
            $this->prefix = $prefix;
        }
    }

    public function setLength($length) {
        if (is_numeric($length)) {
            $this->length = $length;
        }
    }

    public function setExtension($ext) {
        if (is_string($ext)) {
            $this->ext = $ext;
        }
    }

    public function setFileName($file) {
        if (is_string($file)) {
            $this->filename = $file;
        }
    }

    public function setNewName($name) {
        if (is_string($name)) {
            $this->newname = $name;
        }
    }

    public function getOutputName() {
        return $this->name;
    }

    public function Delete($image = null) {
        $result = false;
        if ($image == null) {
            $output_path = $this->url . $this->name;
        } else {
            $output_path = $this->url . $image;
        }
        if (file_exists($output_path)) {
            $result = unlink($output_path);
        }
        return $result;
    }

    public function Upload() {
        $result = false;
        if (isset($_FILES[$this->filename]) && $_FILES[$this->filename] != null) {
            if ((
                    ($_FILES[$this->filename]["type"] == "image/jpeg") ||
                    ($_FILES[$this->filename]["type"] == "image/pjpeg") ||
                    ($_FILES[$this->filename]["type"] == "image/png") ||
                    ($_FILES[$this->filename]["type"] == "image/gif")) &&
                    ($_FILES[$this->filename]["size"] <= $this->length)) {

                $this->ext = "";
                if (strcmp($_FILES[$this->filename]["type"], "image/jpeg") == 0 || strcmp($_FILES[$this->filename]["type"], "image/pjpeg") == 0) {
                    $this->ext = ".jpg";
                }

                if (strcmp($_FILES[$this->filename]["type"], "image/gif") == 0) {
                    $this->ext = ".gif";
                }

                if (strcmp($_FILES[$this->filename]["type"], "image/png") == 0) {
                    $this->ext = ".png";
                }

                if ($_FILES[$this->filename]["error"] > 0) {
                    $this->error = $_FILES[$this->filename]["error"];
                } else {
                    $this->name = $this->prefix . $this->newname . "" . $this->ext;
                    $input_path = $_FILES[$this->filename]["tmp_name"];
                    $output_path = $this->url . $this->name;
                    if (!file_exists($this->url)) {
                        mkdir($this->url);
                    }
                    $this->Delete();
                    $result = move_uploaded_file($input_path, $output_path);
                }
            } else {
                
            }
        }
        return $result;
    }

}

ob_end_flush();
?>