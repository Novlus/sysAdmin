<?php
function curl_get($url, array $headers = [])
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (!empty($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
function trierParDateStart($tableau)
{
    usort($tableau, function ($a, $b) {
        $dateA = strtotime($a['start']['date']);
        $dateB = strtotime($b['start']['date']);
        return $dateA - $dateB;
    });
    return $tableau;
}
function formatDateFR($date)
{
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

$code = $_GET['code'];
?>
<br>
<?php
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.googleapis.com/oauth2/v4/token?code=' . $code . '&client_id=124418609635-jnj52lf7rb1vmc36e3sktl94dqacf29p.apps.googleusercontent.com&client_secret=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&redirect_uri=http://localhost/sysAdmin/td2/InterfaceCalendar.php&grant_type=authorization_code',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{}
',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));
$response = curl_exec($curl);
curl_close($curl);

$token = json_decode($response, true);

$dateMin = gmdate('Y-m-d\TH:i:s\Z');
$dateMax = gmdate('Y-m-d\TH:i:s\Z', strtotime('+30 days'));

$test = curl_get('https://www.googleapis.com/calendar/v3/calendars/primary/events?timeMin=' . $dateMin . '&timeMax=' . $dateMax . '&access_token=' . $token['access_token']);
$test2 = json_decode($test, true);
$test3 = $test2['items'];
$test4 = trierParDateStart($test3);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <?php for ($i = 0; $i < count($test4); $i++) { ?>
            <div class="card">
                <div class="card-header"><?php echo $test4[$i]['summary']; ?></div>
                <div class="card-body">
                    <p class="card-text"><strong>Créateur:</strong> <?php echo $test4[$i]['creator']['email']; ?></p>
                    <p class="card-text"><strong>Début:</strong> <?php echo formatDateFR($test4[$i]['start']['date']); ?></p>
                    <p class="card-text"><strong>Fin:</strong> <?php echo formatDateFR($test4[$i]['end']['date']); ?></p>
                </div>
            </div>
            <br>
        <?php } ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</html>