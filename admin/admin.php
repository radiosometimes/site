
<?php
$realm = 'Restricted area';

//user => password
$users = json_decode(file_get_contents('/var/www/radiosometimes/data/users.json'), true);


if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('You cancelled.');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']]))
    die('<a target="_external" href="https://twitter.com/dril/status/922321981">No</a>');


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
    die('<a target="_external" href="https://twitter.com/dril/status/922321981">No</a>');

// ok, valid username & password
echo 'You are logged in as: ' . $data['username'];


// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $file = '/var/www/radiosometimes/data/data.json';
    $data = json_decode(file_get_contents($file), true);
    echo '<form method="post" action="admin.php"><p>Radio?</p><label>On</label><input type="radio" id="radio_toggle_on" name="radio_toggle" value="on"';
    if ($data["radio"] === 'on') echo 'checked';
    echo '>';
    echo '<label>Off</label><input type="radio" id="radio_toggle_off" name="radio_toggle" value="off"';
    if ($data["radio"] === 'off') echo 'checked';
    echo '>';
    echo '<p>Video?</p><label>On</label><input type="radio" id="video_toggle_on" name="video_toggle" value="on"';
    if ($data["video"] === 'on') echo 'checked';
    echo '>';
    echo '<label>Off</label><input type="radio" id="video_toggle_off" name="video_toggle" value="off"';
    if ($data["video"] === 'off') echo 'checked';
    echo '>';
    echo '<p>Video Link</p><input type="text" id="video_link" name="video_link" value="'.
    $data["video_link"].'">';
    echo '<p>Image</p><input type="text" id="img_src" name="img_src" value="'.
    $data["img_src"].'">';
    echo '<p>Caption</p><input type="text" id="caption" name="caption" value="'.
    $data["caption"].'">';
    

    echo '<br /><br /><input type="Submit" value="Submit"></form>';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = '{"radio": "'.$_POST['radio_toggle'].'", "video": "'.$_POST['video_toggle'].'", "video_link": "'.$_POST['video_link'].'", "img_src": "'.$_POST['img_src'].'", "caption": "'.$_POST['caption'].'"}';
    $dir = '/var/www/radiosometimes/data/';
    file_put_contents($dir."data.json", $data);
    echo '<p>Settings updated!</p><p><a href="admin.php">Back</a></p>';
}

?>
