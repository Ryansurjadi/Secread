<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Kelola File</title>
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
        label, input, texarea {
            display: block;
        }
        p.error {           
            color: red;         
        }
    </style>
</head>
<body>
    <h1>Kelola File</h1>
 
    <?php echo anchor("file", "Kembali") ?> <br />
   
    <?php echo form_open(uri_string()); ?>
    <label for="nama">Nama</label>
    <input type="text" name="name" id="nama" value="<?php if (isset($name)) {echo set_value("name", $name);} ?>" />
    <?php echo form_error("name", "<p class='error'>", "</p>") ?>

    <label for="content">Konten</label>
    <textarea name="content" id="content" cols="100" rows="20"><?php if (isset($content)) {echo set_value("content", $content);} ?></textarea> 
   
    <input type="submit" value="Simpan" />
   
    <?php echo form_close() ?>
    
    <code>
                    <?php echo anchor('/file/logout', 'Logout') ?>
                </code>
</body>
</html>
