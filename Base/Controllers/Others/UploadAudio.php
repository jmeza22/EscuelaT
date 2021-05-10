<?php

class UploadAudio {

    private $url = 'AudioFiles/';
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
                    ($_FILES[$this->filename]["type"] == "audio/mpeg") ||
                    ($_FILES[$this->filename]["type"] == "audio/aac") ||
                    ($_FILES[$this->filename]["type"] == "audio/vnd.dlna.adts") ||
                    ($_FILES[$this->filename]["type"] == "audio/ogg") ||
                    ($_FILES[$this->filename]["type"] == "audio/wav")) &&
                    ($_FILES[$this->filename]["size"] <= $this->length)) {


                if ($this->ext == "") {
                    if (strcmp($_FILES[$this->filename]["type"], "audio/mpeg") == 0) {
                        $this->ext = ".mp3";
                    }

                    if (strcmp($_FILES[$this->filename]["type"], "audio/aac") == 0 || strcmp($_FILES[$this->filename]["type"], "audio/vnd.dlna.adts") == 0) {
                        $this->ext = ".aac";
                    }

                    if (strcmp($_FILES[$this->filename]["type"], "audio/ogg") == 0) {
                        $this->ext = ".ogg";
                    }
                    
                    if (strcmp($_FILES[$this->filename]["type"], "audio/wav") == 0) {
                        $this->ext = ".wav";
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