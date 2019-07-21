<?php

// Upload file
if (isset($_POST['but_submit']) && $_POST['competition_key'] !== "" && $_POST['competition_desc']) {

  global $wpdb;

  if ($_FILES['file']['name'] != '') {

    $uploadedfile = $_FILES['file'];
    $competition_key = $_POST['competition_key'];
    $competition_desc = $_POST['competition_desc'];
    $competition_metric = $_POST['competition_metric'];

    $upload_overrides = array('test_form' => false);

    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
    $fileurl = "";

    if ($movefile && !isset($movefile['error'])) {
      $fileurl = $movefile['file'];

      $wpdb->insert(
        $wpdb->prefix . 'gr_competition',
        array(
          'competition_key' => $competition_key,
          'filename' => $fileurl,
          'competition_desc' => $competition_desc,
          'metric' => $competition_metric
        ),
        array(
          '%s',
          '%s',
          '%s',
          '%s'
        )
      );


      echo "url : " . $fileurl;
    } else {
      echo $movefile['error'];
    }
  }
}

function wp_modify_uploaded_file_names($file)
{
  $info = pathinfo($file['name']);
  $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
  $name = basename($file['name'], $ext);

  $file['name'] = uniqid() . $ext; // uniqid method
  // $file['name'] = md5($name) . $ext; // md5 method
  // $file['name'] = base64_encode($name) . $ext; // base64 method

  return $file;
}


?>
<h1>Upload File</h1>

<!-- Form -->
<form method='post' action='' name='myform' enctype='multipart/form-data'>

  <table>
    <tr>
      <td>Competition Key <input type='text' name='competition_key'> </td>
      <td>Competition Desc <input type='text' name='competition_desc'> </td>
      <td>
        <select name="competition_metric">
          <option value="accuracy" selected>accuracy</option>
        </select>
      </td>
      <td><input type='file' name='file'></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type='submit' name='but_submit' value='Submit'></td>
    </tr>
  </table>
</form>