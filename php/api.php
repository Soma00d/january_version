<?php

///////////////////////////////////////////////////////////////////
//connection base de donnée en PDO/////////////////////////////////
///////////////////////////////////////////////////////////////////
$VALEUR_hote = 'localhost';
$VALEUR_port = '';
$VALEUR_nom_bd = 'testbench2';
$VALEUR_user = 'root';
$VALEUR_mot_de_passe = '';

$connexion = new PDO('mysql:host=' . $VALEUR_hote . ';port=' . $VALEUR_port . ';dbname=' . $VALEUR_nom_bd, $VALEUR_user, $VALEUR_mot_de_passe);



///////////////////////////////////////////////////////////////////
//Recuperation des variables GET pour traitement ultérieur/////////
///////////////////////////////////////////////////////////////////
if (isset($_GET["function"])) {
    $function = $_GET["function"];
}
if (isset($_GET["param1"])) {
    $param1 = $_GET["param1"];
}
if (isset($_GET["param2"])) {
    $param2 = $_GET["param2"];
}
if (isset($_GET["param3"])) {
    $param3 = $_GET["param3"];
}
if (isset($_GET["param4"])) {
    $param4 = $_GET["param4"];
}
if (isset($_GET["param5"])) {
    $param5 = $_GET["param5"];
}


///////////////////////////////////////////////////////////////////
//Catalogue de fonction ///////////////////////////////////////////
///////////////////////////////////////////////////////////////////
//retourne les infos d'un tsui en fonction du part number
$getTsui = function ($part_number, $model, $type, $connexion) {
    if ($type != "") {
        $resultats = $connexion->query("SELECT * FROM tsui WHERE part_number = '$part_number' AND model ='$model' AND type='$type'");
    } else {
        $resultats = $connexion->query("SELECT * FROM tsui WHERE part_number = '$part_number' AND model ='$model'");
    }
    $resultats->execute();
    $result = $resultats->fetchAll();

    return json_encode($result);
};

$getTsuiRepair = function ($part_number, $connexion) {

    $resultats = $connexion->query("SELECT * FROM tsui WHERE part_number = '$part_number' ");

    $resultats->execute();
    $result = $resultats->fetchAll();

    return json_encode($result);
};

//retourne un dictionnaire complet en fonction d'une family id
$getDictionariesById = function ($id, $connexion) {
    $resultats = $connexion->query("SELECT * FROM dictionaries WHERE family_id = $id ORDER BY id");
    $resultats->execute();
    $result = $resultats->fetchAll();

    return json_encode($result);
};

//retourne les elements du test final en fonction d'une family id
$getFinalTest = function ($id, $connexion) {
    $resultats = $connexion->query("SELECT * FROM dictionaries WHERE family_id = $id AND is_final = '1' ORDER BY RAND()");
    $resultats->execute();
    $result = $resultats->fetchAll();

    return json_encode($result);
};

//enregistre les log d'un pretest
$saveLogPretest = function ($connexion) {
    $jsonlog = $_POST['jsonlog'];
    $user_sso = 'testuser';
    $pn = 'testPN';
    $serial = 'testSerial';
    $role = 'repair';
    $type = 'pretest';

    $stmt = $connexion->prepare("INSERT INTO global_log (json_log, part_number, serial_number, user_sso, role, type, date) VALUES (:jsonlog, :partnumber, :serialnumber, :user_sso, :role, :type, NOW())");
    $stmt->bindParam(':jsonlog', $jsonlog);
    $stmt->bindParam(':partnumber', $pn);
    $stmt->bindParam(':serialnumber', $serial);
    $stmt->bindParam(':user_sso', $user_sso);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':type', $type);

    $stmt->execute();
};

//enregistre les log d'un testfinal
$saveLogFinal = function ($connexion) {
    $jsonlog = $_POST['jsonlog'];
    $user_sso = $_POST['sso'];
    $pn = $_POST['pn'];
    $serial = $_POST['sn'];
    $role = 'repair';
    $type = 'finaltest';
    $fw_fct_version = $_POST['FWfctV'];
    $fw_calib_version = $_POST['FWfctV'];
    $sw_version = $_POST['SWv'];

    $alim_tsui = $_POST['alim_tsui'];
    $alim_testbench = $_POST['alim_testbench'];
    $enable_tens = $_POST['enable_tens'];
    $enable_freq = $_POST['enable_freq'];

    $stmt = $connexion->prepare("INSERT INTO global_log (json_log, part_number, serial_number, user_sso, role, type, fw_fct_version, fw_calib_version, sw_version, alim_tsui, alim_testbench, enable_tens, enable_freq, date) VALUES (:jsonlog, :partnumber, :serialnumber, :user_sso, :role, :type,:fwfctv, :fwcalibv, :swv, :alimtsui, :alimtestbench, :enabletens, :enablefreq, NOW())");
    $stmt->bindParam(':jsonlog', $jsonlog);
    $stmt->bindParam(':partnumber', $pn);
    $stmt->bindParam(':serialnumber', $serial);
    $stmt->bindParam(':user_sso', $user_sso);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':fwfctv', $fw_fct_version);
    $stmt->bindParam(':fwcalibv', $fw_calib_version);
    $stmt->bindParam(':swv', $sw_version);
    $stmt->bindParam(':alimtsui', $alim_tsui);
    $stmt->bindParam(':alimtestbench', $alim_testbench);
    $stmt->bindParam(':enabletens', $enable_tens);
    $stmt->bindParam(':enablefreq', $enable_freq);

    $stmt->execute();
};

//retourne les elements du test final en fonction d'une family id
$getGlobalLog = function ($param1, $param2, $param3, $param4, $connexion) {
    $queryLog = "SELECT * FROM global_log WHERE json_log != '' ";
    if ($param1 != "") {
        $queryLog .= "AND part_number='$param1' ";
    }
    if ($param2 != "") {
        $queryLog .= "AND serial_number='$param2' ";
    }
    if ($param3 != "") {
        $queryLog .= "AND user_sso='$param3' ";
    }
    if ($param4 != "") {
        $queryLog .= "AND date >='$param4' ";
    }
    $resultats = $connexion->query($queryLog);

    $resultats->execute();
    $result = $resultats->fetchAll();

    return json_encode($result);
};

//check if SN exist in database
$getSN = function ($serialNumber, $connexion) {
    $resultats = $connexion->query("SELECT * FROM log_sn WHERE serial_number = '$serialNumber' ");
    $resultats->execute();
    $result = $resultats->fetchAll();
    return json_encode($result);
};

//add new SN in database
$addSN = function ($serialNumber, $connexion) {

    $stmt = $connexion->prepare("INSERT INTO log_sn (serial_number, date) VALUES (:serialnumber, NOW())");
    $stmt->bindParam(':serialnumber', $serialNumber);
    $stmt->execute();
};

//add new USER in database
$createUser = function ($connexion) {
    $name = $_POST['name'];
    $sso = $_POST['sso'];
    $description = $_POST['description'];
    $permission = $_POST['permission'];
    $isAdmin = $_POST['isadmin'];
    
    $stmt = $connexion->prepare("INSERT INTO user (user_name, user_sso,user_description, user_role, user_is_admin,  user_date) VALUES (:name,:sso,:description,:permission,:admin, NOW())");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':sso', $sso);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':permission', $permission);
    $stmt->bindParam(':admin', $isAdmin);
    
    $stmt->execute();
};

//update SN in database
$updateSN = function ($serialNumber, $connexion) {
    $serialNumber = $serialNumber;
    $commentary = $_POST['commentary'];

    $sql = "UPDATE log_sn SET commentary = '$commentary', date_commentary = NOW() WHERE serial_number = '$serialNumber'";
    $stmt = $connexion->prepare($sql);

    $stmt->execute();
};

//update USER in database
$updateUserById = function ($connexion) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sso = $_POST['sso'];
    $description = $_POST['description'];
    $permission = $_POST['permission'];
    $isAdmin = $_POST['isadmin'];

    $sql = "UPDATE user SET user_name = '$name', user_sso = '$sso', user_description = '$description', user_role = '$permission', user_is_admin = '$isAdmin', user_date = NOW() WHERE id = '$id'";
    $stmt = $connexion->prepare($sql);  
    $stmt->execute();
};

//delete line by id and by table
$deleteLineById = function ($connexion) {
    $idLine = $_POST['id'];
    $tableName = $_POST['table'];

    $sql = "DELETE FROM $tableName WHERE id = '$idLine'";
    $stmt = $connexion->prepare($sql);
    $stmt->execute();
};

//check if SSO exist in database and GET rights
$checkUserSSO = function ($userSSO, $connexion) {
    $resultats = $connexion->query("SELECT * FROM user WHERE user_sso = '$userSSO' ");
    $resultats->execute();
    $result = $resultats->fetchAll();
    return json_encode($result);
};

//recupère toutes les entrées de la table user
$getAllUser = function ($connexion) {
    $resultats = $connexion->query("SELECT * FROM user");
    $resultats->execute();
    $result = $resultats->fetchAll();
    return json_encode($result);
};

///////////////////////////////////////////////////////////////////
//Routeur des fonctions appelées en ajax via des param get en url//
///////////////////////////////////////////////////////////////////
if (isset($_GET["function"])) {
    switch ($function) {
        case "get_tsui":
            echo $getTsui($param1, $param2, $param3, $connexion);
            break;
        case "get_tsui_repair":
            echo $getTsuiRepair($param1, $connexion);
            break;
        case "get_dictionaries_by_id":
            echo $getDictionariesById($param1, $connexion);
            break;
        case "save_log_pretest":
            $saveLogPretest($connexion);
            break;
        case "save_log_final":
            $saveLogFinal($connexion);
            break;
        case "get_final_test":
            echo $getFinalTest($param1, $connexion);
            break;
        case "get_global_log":
            echo $getGlobalLog($param1, $param2, $param3, $param4, $connexion);
            break;
        case "get_sn":
            echo $getSN($param1, $connexion);
            break;
        case "add_sn":
            $addSN($param1, $connexion);
            break;
        case "update_sn":
            $updateSN($param1, $connexion);
            break;
        case "update_user_by_id":
            $updateUserById($connexion);
            break;
        case "create_user":
            $createUser($connexion);
            break;
        case "delete_line_by_id":
            $deleteLineById($connexion);
            break;
        case "check_user_sso":
            echo $checkUserSSO($param1, $connexion);
            break;
        case "get_all_user":
            echo $getAllUser($connexion);
            break;
        default:
            echo "no param";
    }
}


