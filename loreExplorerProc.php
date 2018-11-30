<?php

/*
error_reporting(-1);
ini_set('display_errors', '1');
*/

require 'vendor/autoload.php';


//bringing in my api keys from elsewhere, you can get your own from mashape.com
require_once('../../api/loreApi.php');

$cardQuery = $_GET['queryBox'];


$client = new GuzzleHttp\Client(['base_uri' => 'https://omgvamp-hearthstone-v1.p.mashape.com/cards/search/',
'verify' => 'certs/cacert.pem', 'headers' => $headers, 'http_errors' => false

]);

$response = $client->request('GET', $cardQuery);

$statuscode = $response->getStatusCode();

$contents = (string) $response->getBody();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lore Explorer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <section id=banner>
        <h4>The Lore Explorer</h4>
        <form action="loreExplorer.php">
           <button>Search Again</button>     
        </form>
    </section>
    <div id="demo">

    </div>

    <script>
    
    resp = <?php echo $contents ?>

    if (<?php echo $statuscode ?> == 404){
        demo.innerHTML="<h3>No minions found matching these search terms :(<h3>"
    }
    
    else if (<?php echo $statuscode ?> != 404) {

        for (let i = 0; i < resp.length; i++) {
            if (resp[i].type == "Minion") {
                pic = document.createElement('img')
                let picUrl = resp[i].img
                if (picUrl.charAt(4)!="s") {
                    console.log("mixed security image")
                    let suffix = picUrl.substring(4)
                    pic.src = "https" + suffix
                }
                pic.alt = "No image in HearthStone DataBase : ("
                pic.addEventListener("error", brokenPic)
                pic.addEventListener("click", showBody)
                pic.lore = resp[i].flavor
                demo.appendChild(pic)
            }
        }
    }

    function brokenPic() {
        event.target.style.display = "none"
    }

    function showBody() {
        if (event.target.lore != null) {
            loreBox.innerHTML = event.target.lore
        } else {
            loreBox.innerHTML = "No lore reported for this minion"
        }
    }

    </script>

    <div id="loreBox"></div>

</body>
</html>

