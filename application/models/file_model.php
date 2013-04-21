<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file_model
 *
 * @author Emeraldy
 */
class File_model extends CI_Model {

    private $table = "files";

    function getFileInfo($path) {
        return get_file_info($path);
    }

    function getFileName($path) {
        $info = get_file_info($path);
        return $info['name'];
    }

    function getFileContent($path) {
        return read_file($path);
    }

    function addFile($filePath, $fileContent) {
        return write_file($filePath, $fileContent, 'w+');
    }

    function deleteFile($filePath) {
        return unlink($filePath);
    }

}

?>
