<h1>Competitions</h1>

<?php
global $wpdb;
$table_name = $wpdb->prefix . 'gr_competition';

$rows = $wpdb->get_results("SELECT * FROM " . $table_name);

?>

<table style="border:1px solid ">
    <tr>
        <th> id </th>
        <th> competition_key </th>
        <th> competition_desc </th>
        <th> filename </th>
        <th> metric </th>
        <th> timestamp </th>
    </tr>

    <?php
    foreach($rows as $row){
        echo "<tr>";
        echo "<td>". $row->id . "</td>";
        echo "<td>". $row->competition_key . "</td>";
        echo "<td>". $row->competition_desc . "</td>";
        echo "<td>". $row->filename . "</td>";
        echo "<td>". $row->metric . "</td>";
        echo "<td>". $row->timestamp . "</td>";
        echo "</tr>"    ;
    }
    
    ?>
</table>