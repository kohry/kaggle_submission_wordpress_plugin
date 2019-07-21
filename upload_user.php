<style>
.grgr_upload {
    border : 1px solid #dedede;
    border-radius: 10px;
}

.grgr_upload td {
    border : 0px;
    border-radius: 5px;
}
</style>


<?php

require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

function score_from_file($ground_truth_fileurl, $fileurl, $metric)
{

    $arr = array();
    $gt_arr = array();

    if (($handle = fopen($fileurl, 'r')) !== FALSE) { // Check the resource is valid
        while (($data = fgetcsv($handle, ",")) !== FALSE) { // Check opening the file is OK!
            for ($i = 0; $i < count($data); $i++) { // Loop over the data using $i as index pointer
                $arr = $arr + array($data[0] => $data[1]);
            }
        }
        fclose($handle);
    }

    if (($handle = fopen($ground_truth_fileurl, 'r')) !== FALSE) { // Check the resource is valid
        while (($data = fgetcsv($handle, ",")) !== FALSE) { // Check opening the file is OK!
            for ($i = 0; $i < count($data); $i++) { // Loop over the data using $i as index pointer
                $gt_arr = $gt_arr + array($data[0] => $data[1]);
            }
        }
        fclose($handle);
    }

    if (sizeof($arr) !== sizeof($gt_arr)) return 0;

    //TODO: metric과 같은 경우 여러가지를 추가할수있다. 
    if ($metric == "accuracy") {
        return get_accuracy($gt_arr, $arr);
    } else {
        return 0;
    }
}

function calc_score_from_metric($score, $metric) {
    if ($metric == "accurcy") return $score;
    else return $score;
}

function get_accuracy($gt, $arr)
{

    $index = 0;
    $is_right = 0;

    foreach ($gt as $key => $answer) {
        
        $predict = $arr[$key];
        
        if ($predict === $answer) {
            $is_right = $is_right + 1;
        }

        $index++;
    }


    return number_format($is_right / $index, 5);
}


$competition_key = shortcode_atts(array('competition_key' => ''), $atts)['competition_key'];

// Upload file
if (isset($_POST['but_submit']) && $competition_key !== '' && is_user_logged_in() ) {

    global $wpdb;

    if ($_FILES['file']['name'] != '') {
        $uploadedfile = $_FILES['file'];
        
        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        
        $fileurl = "";

        if ($movefile && !isset($movefile['error'])) {
            $fileurl = $movefile['file'];

            //현재 답지로 저장되어 있는 파일 가져옴
            $ground_truth_fileurl = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'gr_competition'
                . " WHERE competition_key = '"
                . $competition_key
                . "'  ORDER BY timestamp DESC LIMIT 1");

            $score = score_from_file($ground_truth_fileurl[0]->filename, $fileurl, $ground_truth_fileurl[0]->metric);
            $calc_score = calc_score_from_metric($score, $ground_truth_fileurl[0]->metric);

            //리더보드에 삽입
            $wpdb->insert(
                $wpdb->prefix . 'gr_leaderboard',
                array(
                    'competition_key' => $competition_key,
                    'filename' => $fileurl,
                    'score' => $score,
                    'calc_score' => $calc_score,
                    'metric' => $ground_truth_fileurl[0]->metric,
                    'desc' => '',
                    'user_name' => wp_get_current_user()->display_name,
                    'user_id' => wp_get_current_user()->ID
                ),
                array('%s', '%s', '%s')
            );

            echo "Succesfully Submitted, Score : " . $score;
        } else {
            echo $movefile['error'];
        }
    }
}

?>


<!-- Form -->
<form method='post' action='' name='myform' enctype='multipart/form-data' class = "grgr_upload">

    <table>
        <tr>
            <td><input type='file' name='file'></td>
        </tr>
        <tr>
            <td><input type='submit' name='but_submit' value='submit'></td>
        </tr>
    </table>
</form>