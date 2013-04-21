<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Daftar File</title>
        <style>
            table {
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid #000;
            }
            th, td {
                padding: 3px;
            }
            p.success {color: green;}
        </style>
    </head>
    <body>
        <h1>Daftar File</h1>
        <div style="margin-bottom: 5px;">
            <?php echo anchor("file/add", "Tambah")
            ?>
        </div>
        <?php
        $message = $this->session->flashdata("message");
        echo ($message) ? "<p class='success'>{$message}</p>" : "";
        ?>
        <table width="100%">
            <tr>
                <th>No</th>
                <th>Nama File</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = $offset;
            if ($total > 0) {
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?php echo++$no; ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td>
                            <?php echo anchor("file/edit/".$row['name'], "Edit")?>|
                            <?php echo anchor("file/delete/".$row['name'], "Delete", array("onclick" => "javascript:return confirm('Konfirmasi penghapusan')"))?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' align='center'>Data masih kosong !</td></tr>";
            }
            ?>
        </table>
    </body>
</html>
