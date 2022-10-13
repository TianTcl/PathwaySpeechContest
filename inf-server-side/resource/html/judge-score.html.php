<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    
	require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");

    if (isset($_REQUEST['of']) && !empty(trim($_REQUEST['of']))) {
        require($dirPWroot."e/resource/db_connect.php");
        $smID = $db -> real_escape_string(strval(intval(base_convert(base64_decode(ltrim(trim($_REQUEST['of']), "ID")), 36, 10)) / 138));
        $getscore = $db -> query("SELECT CAST(a.p11 AS VARCHAR(5)) AS p11,CAST(a.p12 AS VARCHAR(5)) AS p12,CAST(a.p13 AS VARCHAR(5)) AS p13,CAST(a.p21 AS VARCHAR(5)) AS p21,CAST(a.p22 AS VARCHAR(5)) AS p22,CAST(a.p23 AS VARCHAR(5)) AS p23,CAST(a.p24 AS VARCHAR(5)) AS p24,CAST(a.p31 AS VARCHAR(5)) AS p31,CAST(a.p32 AS VARCHAR(5)) AS p32,CAST(a.p41 AS VARCHAR(5)) AS p41,CAST(a.mark AS VARCHAR(5)) AS mark,a.comment,substring(b.display, 4, LENGTH(b.display)) as judge FROM PathwaySCon_score a INNER JOIN PathwaySCon_organizer b ON a.judge=b.user_id WHERE a.smid=$smID UNION ALL SELECT CAST(AVG(p11) AS VARCHAR(5)) AS p11,CAST(AVG(p12) AS VARCHAR(5)) AS p12,CAST(AVG(p13) AS VARCHAR(5)) AS p13,CAST(AVG(p21) AS VARCHAR(5)) AS p21,CAST(AVG(p22) AS VARCHAR(5)) AS p22,CAST(AVG(p23) AS VARCHAR(5)) AS p23,CAST(AVG(p24) AS VARCHAR(5)) AS p24,CAST(AVG(p31) AS VARCHAR(5)) AS p31,CAST(AVG(p32) AS VARCHAR(5)) AS p32,CAST(AVG(p41) AS VARCHAR(5)) AS p41,CAST(AVG(mark) AS VARCHAR(5)) AS mark,'' AS comment,'Average' as judge FROM PathwaySCon_score WHERE smid=$smID GROUP BY smid");
        $db -> close();
        if (!$getscore || !($getscore -> num_rows)) $exit_msg = array("gray", "No judge marks this speech video yet.<br>You are the first one here.");
    } else $exit_msg = array("yellow", "No participant selected.");

    if (isset($exit_msg)) die('<center class="message '.$exit_msg[0].'">'.$exit_msg[1].'</center>');
?>
<div class="table"><table><thead><tr>
    <th onClick="ro(1)"><?=$_COOKIE['set_lang']=="th"?"ผู้พิจารณา":"Judge"?></th>
    <th onClick="ro(2)"><span>1.1) Accuracy</span></th>
    <th onClick="ro(3)"><span>1.2) Organization</span></th>
    <th onClick="ro(4)"><span>1.3) Creativity</span></th>
    <th onClick="ro(5)"><span>2.1) Vocabulary</span></th>
    <th onClick="ro(6)"><span>2.2) Structure, Connectors</span></th>
    <th onClick="ro(7)"><span>2.3) Stress, Rhythm</th>
    <th onClick="ro(8)"><span>2.4) Tone</span></th>
    <th onClick="ro(9)"><span>3.1) Communication</span></th>
    <th onClick="ro(10)"><span>3.2) Personality</span></th>
    <th onClick="ro(11)"><span>4.1) Duration</span></th>
    <th onClick="ro(12)"><?=$_COOKIE['set_lang']=="th"?"คะแนนรวม":"Total"?></th>
    <th><?=$_COOKIE['set_lang']=="th"?"ข้อความ":"Comment"?></th>
</tr></thead><tbody><?php $rs = 1; $rss = $getscore -> num_rows;
    while($score = $getscore -> fetch_assoc()) { if ($rs++ < $rss) { ?><tr>
        <td><?=$score["judge"]?></td>
        <td><?=$score["p11"]?></td>
        <td><?=$score["p12"]?></td>
        <td><?=$score["p13"]?></td>
        <td><?=$score["p21"]?></td>
        <td><?=$score["p22"]?></td>
        <td><?=$score["p23"]?></td>
        <td><?=$score["p24"]?></td>
        <td><?=$score["p31"]?></td>
        <td><?=$score["p32"]?></td>
        <td><?=$score["p41"]?></td>
        <td><?=$score["mark"]?></td>
        <td><div><?=$score["comment"]?></div></td>
    </tr><?php } else { ?>
</tbody><thead><tr>
    <th><?=$score["judge"]?></th>
    <th><?=strval($score["p11"] + 0)?></th>
    <th><?=strval($score["p12"] + 0)?></th>
    <th><?=strval($score["p13"] + 0)?></th>
    <th><?=strval($score["p21"] + 0)?></th>
    <th><?=strval($score["p22"] + 0)?></th>
    <th><?=strval($score["p23"] + 0)?></th>
    <th><?=strval($score["p24"] + 0)?></th>
    <th><?=strval($score["p31"] + 0)?></th>
    <th><?=strval($score["p32"] + 0)?></th>
    <th><?=strval($score["p41"] + 0)?></th>
    <th><?=strval($score["mark"] + 0)?></th>
    <th></th>
</tr><tr>
    <th>Max pts</th>
    <td><?=$config['criteria'][11]?></td>
    <td><?=$config['criteria'][12]?></td>
    <td><?=$config['criteria'][13]?></td>
    <td><?=$config['criteria'][21]?></td>
    <td><?=$config['criteria'][22]?></td>
    <td><?=$config['criteria'][23]?></td>
    <td><?=$config['criteria'][24]?></td>
    <td><?=$config['criteria'][31]?></td>
    <td><?=$config['criteria'][32]?></td>
    <td><?=$config['criteria'][41]?></td>
    <td>100</td>
    <td></td>
</tr><?php } } ?></thead></table></div>