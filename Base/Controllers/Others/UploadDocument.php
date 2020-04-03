<?php

class UploadDocument {

    private $url = 'DocumentFiles/';
    private $prefix = '';
    private $length = 52428800;
    private $ext = '';
    private $filename = '';
    private $newname = '';
    private $fullname = '';
    private $name = '';
    private $error = '';

    public function __construct() {
        
    }

    public function setMaxLength($length = null) {
        if ($length !== null && is_numeric($length)) {
            $this->length = $length;
        }
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

    public function setFullName($name) {
        if (is_string($name)) {
            $this->fullname = $name;
        }
    }

    public function getOutputName() {
        return $this->name;
    }

    public function Delete($document = null) {
        $result = false;
        if ($document == null) {
            $output_path = $this->url . $this->name;
        } else {
            $output_path = $this->url . $document;
        }
        if (file_exists($output_path)) {
            $result = unlink($output_path);
        }
        return $result;
    }

    public function getInfo($print = false) {
        if (isset($_FILES[$this->filename]) && $_FILES[$this->filename] != null) {
            if ($print) {
                print_r($_FILES);
            }
            return $_FILES;
        }
    }

    public function Upload() {
        $result = false;
        if (isset($_FILES[$this->filename]) && $_FILES[$this->filename] != null) {
            if ((
                    ($_FILES[$this->filename]["type"] == "text/plain") ||
                    ($_FILES[$this->filename]["type"] == "application/pdf") ||
                    ($_FILES[$this->filename]["type"] == "application/msword") ||
                    ($_FILES[$this->filename]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") ||
                    ($_FILES[$this->filename]["type"] == "application/vnd.ms-powerpoint") ||
                    ($_FILES[$this->filename]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation") ||
                    ($_FILES[$this->filename]["type"] == "application/vnd.ms-excel") ||
                    ($_FILES[$this->filename]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")) &&
                    ($_FILES[$this->filename]["size"] <= $this->length)) {

                if ($this->ext === "") {

                    if ($_FILES[$this->filename]["type"] === "text/plain") {
                        $this->ext = ".txt";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/pdf") {
                        $this->ext = ".pdf";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/msword") {
                        $this->ext = ".doc";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                        $this->ext = ".docx";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/vnd.ms-powerpoint") {
                        $this->ext = ".ppt";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
                        $this->ext = ".pptx";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/vnd.ms-excel") {
                        $this->ext = ".xls";
                    }
                    if ($_FILES[$this->filename]["type"] === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                        $this->ext = ".xlsx";
                    }
                }

                if ($_FILES[$this->filename]["error"] > 0) {
                    $this->error = $_FILES[$this->filename]["error"];
                } else {
                    if ($this->prefix === '' && $this->newname === '') {
                        $this->name = $this->fullname;
                    } else {
                        $this->name = $this->prefix . $this->newname . "" . $this->ext;
                    }
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

?>