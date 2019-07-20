<h1>LeaderBoard</h1>

<!-- Form -->
<form method='post' action='' name='myform' enctype='multipart/form-data'>

    <table>
        <tr>
            <th> Name </th>
            <th> Score </th>
            <th> Rank </th>
        </tr>

        <?php
        global $wpdb;
        $competition_key = shortcode_atts(array('competition_key' => ''), $atts)['competition_key'];

        if ($competition_key !== "") {
            $rows = $wpdb->get_results("SELECT A.* FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY score desc) AS RN FROM " 
            . $wpdb->prefix . 'gr_leaderboard' . " WHERE competition_key = '" 
            . $competition_key ."') AS A WHERE A.RN = 1"
            . " ORDER BY SCORE DESC");
        }

        $rank = 1; 

        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row->user_name . "</td>";
            echo "<td>" . number_format($row->score,5) . "</td>";
            echo "<td>" . $rank++ . "</td>";
            echo "</tr>";
        }

        ?>

    </table>



</form>