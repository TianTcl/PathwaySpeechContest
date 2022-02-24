<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    
	require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");

    if (isset($_REQUEST['of']) && !empty(trim($_REQUEST['of']))) {
        require($dirPWroot."e/resource/db_connect.php");
        $spcID = ltrim(trim($_REQUEST['of']), "ID");
        $smID = $db -> real_escape_string(strval(intval(base_convert(base64_decode($spcID), 36, 10)) / 138));
        $getgrade = $db -> query("SELECT CAST(AVG(p11) AS VARCHAR(5)) AS p11,CAST(AVG(p12) AS VARCHAR(5)) AS p12,CAST(AVG(p13) AS VARCHAR(5)) AS p13,CAST(AVG(p21) AS VARCHAR(5)) AS p21,CAST(AVG(p22) AS VARCHAR(5)) AS p22,CAST(AVG(p23) AS VARCHAR(5)) AS p23,CAST(AVG(p24) AS VARCHAR(5)) AS p24,CAST(AVG(p31) AS VARCHAR(5)) AS p31,CAST(AVG(p32) AS VARCHAR(5)) AS p32,CAST(AVG(p41) AS VARCHAR(5)) AS p41,CAST(AVG(mark) AS VARCHAR(5)) AS mark FROM PathwaySCon_score WHERE smid=$smID GROUP BY smid");
        $getcmt = $db -> query("SELECT scid,comment FROM PathwaySCon_score WHERE smid=$smID AND NOT comment=''");
        $db -> close();
        if ($getgrade && ($getgrade -> num_rows)) $readgrade = $getgrade -> fetch_array(MYSQLI_ASSOC);
        else $exit_msg = array("gray", "This submission cannot be graded.<br>No judge marks this speech video yet.");
    } else $exit_msg = array("yellow", "No participant selected.");

    if (isset($exit_msg)) die('<center class="message '.$exit_msg[0].'">'.$exit_msg[1].'</center>');
?>
<div class="video">
    <div class="wrapper">
        <iframe src="/e/Pathway-Speech-Contest/resource/upload/video?view=ID<?=$spcID?>"></iframe>
    </div>
</div>
<div class="mark table"><table>
    <thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);">
        <th><?=$_COOKIE['set_lang']=="th"?"หัวข้อ":"Critition"?></th>
        <th><?=$_COOKIE['set_lang']=="th"?"คะแนนที่ได้":"Score"?></th>
        <th><?=$_COOKIE['set_lang']=="th"?"คะแนนเต็ม":"Max points"?></th>
    </tr></thead>
    <thead><tr>
        <th>Content</th>
        <th><output type="number" name="s:1"></output></th>
        <th><?=$config['criteria'][10]?> pts</th>
    </tr></thead>
    <tbody>
        <tr>
            <td>Accuracy and Consistency</td>
            <td><output type="number" name="p11"><?=strval($readgrade["p11"] + 0)?></output></td>
            <td><?=$config['criteria'][11]?> pts</td>
        </tr><tr>
            <td>Form & Organization Of Speech</td>
            <td><output type="number" name="p12"><?=strval($readgrade["p12"] + 0)?></output></td>
            <td><?=$config['criteria'][12]?> pts</td>
        </tr><tr>
            <td>Creativity</td>
            <td><output type="number" name="p13"><?=strval($readgrade["p13"] + 0)?></output></td>
            <td><?=$config['criteria'][13]?> pts</td>
        </tr>
    </tbody>
    <thead><tr>
        <th>Language Competence & Fluency</th>
        <th><output type="number" name="s:2"></output></th>
        <th><?=$config['criteria'][20]?> pts</th>
    </tr></thead>
    <tbody>
        <tr>
            <td>Vocabulary</td>
            <td><output type="number" name="p21"><?=strval($readgrade["p21"] + 0)?></output></td>
            <td><?=$config['criteria'][21]?> pts</td>
        </tr><tr>
            <td>Structure & Connectors</td>
            <td><output type="number" name="p22"><?=strval($readgrade["p22"] + 0)?></output></td>
            <td><?=$config['criteria'][22]?> pts</td>
        </tr><tr>
            <td>Pronunciation, Stress, Intonation, Rhythm, Pausing and Pace</td>
            <td><output type="number" name="p23"><?=strval($readgrade["p23"] + 0)?></output></td>
            <td><?=$config['criteria'][23]?> pts</td>
        </tr><tr>
            <td>Tone</td>
            <td><output type="number" name="p24"><?=strval($readgrade["p24"] + 0)?></output></td>
            <td><?=$config['criteria'][24]?> pts</td>
        </tr>
    </tbody>
    <thead><tr>
        <th>Presentation</th>
        <th><output type="number" name="s:3"></output></th>
        <th><?=$config['criteria'][30]?> pts</th>
    </tr></thead>
    <tbody>
        <tr>
            <td>Communicaton</td>
            <td><output type="number" name="p31"><?=strval($readgrade["p31"] + 0)?></output></td>
            <td><?=$config['criteria'][31]?> pts</td>
        </tr><tr>
            <td>Personality</td>
            <td><output type="number" name="p32"><?=strval($readgrade["p32"] + 0)?></output></td>
            <td><?=$config['criteria'][32]?> pts</td>
        </tr>
    </tbody>
    <thead><tr>
        <th>Time</th>
        <th><output type="number" name="s:4"></output></th>
        <th><?=$config['criteria'][40]?> pts</th>
    </tr></thead>
    <tbody>
        <tr>
            <td>Speech duration</td>
            <td><output type="number" name="p41"><?=strval($readgrade["p41"] + 0)?></output></td>
            <td><?=$config['criteria'][41]?> pts</td>
        </tr>
    </tbody>
    <thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);">
        <th>Total</th>
        <th><output type="number" name="mark"><?=strval($readgrade["mark"] + 0)?></output></th>
        <th>100 pts</th>
    </tr></thead>
</table></div>
<?php if ($getcmt && $getcmt -> num_rows) { ?>
    <details class="comment message gray">
        <summary><?=$_COOKIE['set_lang']=="th"?"ความคิดเห็น":"Comments"?></summary>
        <ul>
            <?php while($readcmt = $getcmt -> fetch_assoc()) echo '<hr><li name="'.base_convert(intval($readcmt['scid']) * 138, 10, 36).'">'.$readcmt['comment'].'</li>'; ?>
        </ul>
    </details>
<?php } ?>