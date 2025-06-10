<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aid = $_POST['aid'];
    $tooth1 = $_POST['tooth1'];
    $tooth2 = $_POST['tooth2'];
    $tooth3 = $_POST['tooth3'];
    $tooth4 = $_POST['tooth4'];
    $tooth5 = $_POST['tooth5'];
    $tooth6 = $_POST['tooth6'];
    $tooth7 = $_POST['tooth7'];
    $tooth8 = $_POST['tooth8']; 
    $tooth9 = $_POST['tooth9'];
    $tooth10 = $_POST['tooth10'];
    $tooth11 = $_POST['tooth11'];
    $tooth12 = $_POST['tooth12'];
    $tooth13 = $_POST['tooth13'];
    $tooth14 = $_POST['tooth14'];
    $tooth15 = $_POST['tooth15'];
    $tooth16 = $_POST['tooth16'];
    $tooth17 = $_POST['tooth17'];
    $tooth18 = $_POST['tooth18'];
    $tooth19 = $_POST['tooth19'];
    $tooth20 = $_POST['tooth20'];
    $tooth21 = $_POST['tooth21'];
    $tooth22 = $_POST['tooth22'];
    $tooth23 = $_POST['tooth23'];
    $tooth24 = $_POST['tooth24'];
    $tooth25 = $_POST['tooth25'];
    $tooth26 = $_POST['tooth26'];
    $tooth27 = $_POST['tooth27'];
    $tooth28 = $_POST['tooth28'];
    $tooth29 = $_POST['tooth29'];
    $tooth30 = $_POST['tooth30'];
    $tooth31 = $_POST['tooth31'];
    $tooth32 = $_POST['tooth32'];
    $user_id = $_POST['user_id'];

     $response = file_get_contents('http://localhost:5000/setFindings?' .
        '&tooth1=' . urlencode($tooth1) .
        '&tooth2=' . urlencode($tooth2) .
        '&tooth3=' . urlencode($tooth3) .
        '&tooth4=' . urlencode($tooth4) .
        '&tooth5=' . urlencode($tooth5) .
        '&tooth6=' . urlencode($tooth6) .
        '&tooth7=' . urlencode($tooth7) .
        '&tooth8=' . urlencode($tooth8) .
        '&tooth9=' . urlencode($tooth9) .
        '&tooth10=' . urlencode($tooth10) .
        '&tooth11=' . urlencode($tooth11) .
        '&tooth12=' . urlencode($tooth12) .
        '&tooth13=' . urlencode($tooth13) .
        '&tooth14=' . urlencode($tooth14) .
        '&tooth15=' . urlencode($tooth15) .
        '&tooth16=' . urlencode($tooth16) .
        '&tooth17=' . urlencode($tooth17) .
        '&tooth18=' . urlencode($tooth18) .
        '&tooth19=' . urlencode($tooth19) .
        '&tooth20=' . urlencode($tooth20) .
        '&tooth21=' . urlencode($tooth21) .
        '&tooth22=' . urlencode($tooth22) .
        '&tooth23=' . urlencode($tooth23) .
        '&tooth24=' . urlencode($tooth24) .
        '&tooth25=' . urlencode($tooth25) .
        '&tooth26=' . urlencode($tooth26) .
        '&tooth27=' . urlencode($tooth27) .
        '&tooth28=' . urlencode($tooth28) .
        '&tooth29=' . urlencode($tooth29) .
        '&tooth30=' . urlencode($tooth30) .
        '&tooth31=' . urlencode($tooth31) .
        '&tooth32=' . urlencode($tooth32) .
        '&user_id=' . urlencode($user_id)
    );
    $response = json_decode($response, true);
    if ($response) {
        header('Location: ../customer_details.php?aid='.$aid); 
    }
}
