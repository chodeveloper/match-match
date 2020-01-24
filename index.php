<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>SIC Sample #2</title>
</head>
<body>

    <div class="container">
        <div class="row mt-4">
            <div class="column mx-auto">
            <h1>Heejeong's Awesome Card Game</h1>
            <br>           
            <h4 class="text-muted text-center">🃏 vs 🧠</h4>
            <br>
        </div>
        </div>
        <div class="row">
            <div class="column mt-3 mx-auto">
          
<?php
//memory matching card game (Emoji ver.)

$emoji = "🤢,🌞,🍺,😍,😎,👿,☠️,🐸,⛄,🎶,";
$emoji .= "👽,👾,👻,🌍,🐳,🐥,👓,🧞,🍩,🍍,";
$emoji .= "🎄,🎁,🎃,🎮,🧸,🌈,🍕,🌭,🍣,💙";

session_start();

if (empty($_GET['numPair']) && !isset($_SESSION['shuffled_pairs'])) {
    print <<<HERE
    <form class="mt-5">
        <div class="form-group" style="min-width:300px;">
            <label for="numPair">Number of pairs</label>
            <input type="number" class="form-control" id="numPair" name="numPair" min="4" max="20">
        </div>
        <div class="form-group my-4 text-right">
            <input type="submit" name="submit" class="btn btn-warning" value="Play">
        </div>
    </form>
HERE;

} else {
    if (isset($_GET['numPair'])) $numPair = $_GET['numPair'];   
    else $numPair = $_SESSION['numPair'];

    //store the associative array in a session
    if (!isset($_SESSION['shuffled_pairs'])) {
        $pair = explode(",", $emoji);
        shuffle($pair);
        for($i=0;$i<$numPair;$i++) {
            $pairA["A".$i] = $pair[$i];
            $pairB["B".$i] = $pair[$i];
        }
        $pairs = array_merge($pairA, $pairB);
        $shuffled_pairs = array();
        $keys = array_keys($pairs);
        shuffle($keys);
        foreach ($keys as $key) $shuffled_pairs[$key] = $pairs[$key];
        $_SESSION['shuffled_pairs'] = $shuffled_pairs;
        $_SESSION['left_pairs'] = $shuffled_pairs;
        $_SESSION['numPair'] = $numPair;
    }  
    
    //answer
    //var_dump($_SESSION['left_pairs']);

    if (!isset($_GET['selected'])) $selected = null;
    else $selected = $_GET['selected'];

    if (isset($_GET['select'])) {
        if ($_GET['selected'] == null) {
            $selected = $_GET['select'];
        } elseif($_GET['selected'] == $_GET['select']) {
            $selected == null;
        } elseif($_GET['selected'] != $_GET['select']) {
            if (substr($selected, 1) == substr($_GET['select'], 1)) {
                unset($_SESSION['left_pairs'][$selected]);
                unset($_SESSION['left_pairs'][$_GET['select']]);
                $_SESSION['shuffled_pairs'][$selected] = "X";
                $_SESSION['shuffled_pairs'][$_GET['select']] = "X";
                $selected = null;
            } else {
                $selected = null;
            }
        }
    } else {
        $selected = null;
    }
 
    if (count($_SESSION['left_pairs']) != 0) {
        $left = count($_SESSION['left_pairs'])/2;
        print "<h5>$left pair(s) left!</h5><br>";
        print '<form method="post" action="page.php" class="mb-3">';
        print '<input type="submit" class="btn btn-warning" value="Play New Game">';
        print '<p class="small text-muted mt-1">❕ All your cards left will be store in a session unless you start a new game.</p></form>';
        print '<form>';   
        
        foreach ($_SESSION['shuffled_pairs'] as $code => $pic) {
            
            if ($pic == "X") {
                print '<label class="btn btn-link py-3 px-3 m-1" style="font-size:40px;width:80px;height:100px;">';
                print '<input type="checkbox" name="select" class="d-none">';
                print "</label>";
            } else {
                print '<label class="btn btn-light shadow-sm py-3 px-3 m-1" style="font-size:40px;width:80px;height:100px;cursor:pointer;">';
                print '<input type="checkbox" name="select" class="d-none" value="'.$code.'" onchange="this.form.submit();">';
                if ((isset($_GET['selected']) && $_GET['selected'] == $code) || (isset($_GET['select']) && $_GET['select'] == $code)) {
                    print "$pic</label>";
                } else {
                    print "</label>";
                }  
            }
                   
        }
         
   
        print <<<HERE
        <input type="hidden" name="numPair" value="$numPair">
        <input type="hidden" name="selected" value="$selected">
        </form>
HERE;
    } else {
        session_unset();
        print <<<HERE
        <h5>You Win! You have a photographic memory✨</h5><br>
        <button class="btn btn-warning" onClick="document.location.href='.'">Play Again</button>
        
HERE;

    }
}
?>

          </div>
        </div>
    </div>

    <script type="text/javascript">

      

        
    </script>

</body>
</html>
