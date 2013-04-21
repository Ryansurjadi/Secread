<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author Emeraldy
 */
class File extends CI_Controller {

    private $path = 'D:/Testplace/Netbeans/Emeraldy';
    private $total = 0;

    function add() {
        $this->load->library("form_validation");
        // $this->form_validation->set_rules("name", "Nama", "callback_filename_check");
        $this->form_validation->set_rules("name", "Nama", "required|trim");
        $this->form_validation->set_rules("content", "Konten", "trim");

        if ($this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key);
            }
            $sanitizedFileName = $this->security->sanitize_filename($data['name']);
            $fileList = get_filenames($this->path);
            $boolean = TRUE;
            foreach ($fileList as $string => $nama) {
                if (strcmp($sanitizedFileName, $nama) == 0) {
                    $boolean &= FALSE;
                }
            }
            if ($boolean) {
                $filePath = $this->path . DIRECTORY_SEPARATOR . $sanitizedFileName;
                $fileContent = $data['content'];
                $is_success = write_file($filePath, $fileContent, 'w+');
            } else {
                $is_success = FALSE;
            }

            if ($is_success) {
                $this->session->set_flashdata("message", "File berhasil disimpan!");
            } else {
                $this->session->set_flashdata("message", " Maaf, file tidak berhasil disimpan");
            }
            redirect("file");
        } else {
            $this->load->view("file_form");
        }
    }

    function edit($name) {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("name", "Nama", "required|trim");
        $this->form_validation->set_rules("content", "Konten", "trim");

        if ($this->form_validation->run()) {
            foreach ($_POST as $key => $val) {
                $data[$key] = $this->input->post($key);
            }
            $decodedname = urldecode($name);
            $sanitizedFileName = $this->security->sanitize_filename($data['name']);
            if (strcmp($decodedname, $sanitizedFileName)==0) {
                $newPath = $this->path . DIRECTORY_SEPARATOR . $decodedname;
                $newContent = $data['content'];
                $is_success = write_file($newPath, $newContent, 'w+');
            } else {
                $fileList = get_filenames($this->path);
                $boolean = TRUE;
                foreach ($fileList as $string => $nama) {
                    if (strcmp($sanitizedFileName, $nama) == 0) {
                        $boolean &= FALSE;
                    }
                }
                if ($boolean) {
                    $oldPath = $this->path . DIRECTORY_SEPARATOR . $decodedname;
                    rename($oldPath, $sanitizedFileName);
                    $filePath = $this->path . DIRECTORY_SEPARATOR . $sanitizedFileName;
                    $fileContent = $data['content'];
                    $is_success = write_file($filePath, $fileContent, 'w+');
                } else {
                    $is_success = FALSE;
                }
            }

            if ($is_success) {
                $this->session->set_flashdata("message", "File berhasil diubah!");
            } else {
                $this->session->set_flashdata("message", "Maaf, file tidak berhasil diubah");
            }
            redirect("file");
        } else {
            $decodedname = urldecode($name);
            $decodedpath = $this->path . DIRECTORY_SEPARATOR . $decodedname;
            $fileList = get_filenames($this->path);
            $boolean = TRUE;
            foreach ($fileList as $string => $nama) {
                if (strcmp($decodedname, $nama) == 0) {
                    $boolean &= FALSE;
                }
            }
            if (!$boolean) {
                $data = array(
                    'name' => $decodedname,
                    'content' => read_file($decodedpath),
                );
                $this->load->view("file_form", $data);
            }
        }
    }

    function delete($name) {
        $decodedname = urldecode($name);
        $decodedpath = $this->path . DIRECTORY_SEPARATOR . $decodedname;
        $is_success = unlink($decodedpath);

        if ($is_success) {
            $this->session->set_flashdata("message", "File berhasil dihapus!");
        } else {
            $this->session->set_flashdata("message", "Maaf, file gagal dihapus!");
        }

        redirect("file");
    }

    function index($offset = 0) {
        $param['offset'] = $offset;
        $param["data"] = get_dir_file_info($this->path);

        foreach ($param['data'] as $file => $isi) {
            $this->total += 1;
        }
        $param['total'] = $this->total;
        $this->load->view("file_table", $param);
    }

}

?>
