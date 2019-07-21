<style>
.grgr th {
    border : 0px;
}

.grgr tr {
    border : 0px;
}

.grgr td {
    border : 0px;
}
</style>


<!-- Form -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/table.min.css" integrity="sha256-oVdx55VoqJ/ONE3ehd7/NUko5KBRzuAfdxPBwp9qE4w=" crossorigin="anonymous" />
<form method='post' action='' name='myform' enctype='multipart/form-data'>

    <table class="grgr ui celled table">
        <thead>
            <tr style="height:50px">
                <th> Avatar </th>
                <th> Name </th>
                <th> Score </th>
                <th> Rank </th>
            </tr>
        </thead>

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
            echo "<td><img style='width:50px;height:50px' src = '" . get_avatar_url($row->user_id) . "'></td>";
            echo "<td>" . $row->user_name . "</td>";
            echo "<td>" . number_format($row->score,5) . "</td>";
            echo "<td>" . $rank++ . "</td>";
            echo "</tr>";
        }

        ?>

    </table>



</form>