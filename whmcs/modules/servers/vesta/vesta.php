<?php
// This module sponsered by our good friends from wexcloud.com
// DomainScript by Nosov


function vestacp_ConfigOptions() {

    $configarray = array(
     "Package Name" => array( "Type" => "text", "Default" => "default"),
     "SSH Access" => array( "Type" => "yesno", "Description" => "Tick to grant access", ),
     "IP Address (optional)" => array( "Type" => "text" ),
     "DomainScript"=>array("Type"=>"textarea"),
    );
    return $configarray;

}

function vestacp_CreateAccount($params) {

    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-add-user',
          'arg1' => $params["username"],
          'arg2' => $params["password"],
          'arg3' => $params["clientsdetails"]["email"],
          'arg4' => $params["configoption1"],
          'arg5' => $params["clientsdetails"]["firstname"],
          'arg6' => $params["clientsdetails"]["lastname"],
        );
        $postdata = http_build_query($postvars);

        // Create user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);

        logModuleCall('vesta','CreateAccount_UserAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

        // Enable ssh access
        if(($answer == 'OK') && ($params["configoption2"] == 'on')) {
            $postvars = array(
              'user' => $params["serverusername"],
              'password' => $params["serverpassword"],
              'hash' => $params["serveraccesshash"],
              'cmd' => 'v-change-user-shell',
              'arg1' => $params["username"],
              'arg2' => 'bash'
            );
            $postdata = http_build_query($postvars);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $answer = curl_exec($curl);

            logModuleCall('vesta','CreateAccount_EnableSSH','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);
        }

        // Add domain
        if(($answer == 'OK') && (!empty($params["domain"]))) {

            vestacp_DomainScript($params);

            $postvars = array(
              'user' => $params["serverusername"],
              'password' => $params["serverpassword"],
              'hash' => $params["serveraccesshash"],
              'cmd' => 'v-add-domain',
              'arg1' => $params["username"],
              'arg2' => $params["domain"],
              'arg3' => $params["configoption3"],
            );
            $postdata = http_build_query($postvars);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $answer = curl_exec($curl);

            logModuleCall('vesta','CreateAccount_AddDomain','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);
        }
    }

    if($answer == 'OK') {
        $result = "Hospedagem foi Gerada!";
    } else {
        $result = $answer;
    }

    return $result;
}

function vestacp_TerminateAccount($params) {

    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-delete-user',
          'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Delete user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('vesta','TerminateAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "Hospedagem Encerrada";
    } else {
        $result = $answer;
    }

    return $result;
}

function vestacp_SuspendAccount($params) {

    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-suspend-user',
          'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Susupend user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('vesta','SuspendAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "Hospedagem Suspensa";
    } else {
        $result = $answer;
    }

    return $result;
}

function vestacp_UnsuspendAccount($params) {

    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-unsuspend-user',
          'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Unsusupend user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('vesta','UnsuspendAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "Hospedagem Reativada";
    } else {
        $result = $answer;
    }

    return $result;
}

function vestacp_ChangePassword($params) {

    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-change-user-password',
          'arg1' => $params["username"],
          'arg2' => $params["password"]
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('vesta','ChangePassword','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "Senha de acesso a Hospedagem foi Modificada";
    } else {
        $result = $answer;
    }

    return $result;
}

function vestacp_ChangePackage($params) {

    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-change-user-package',
          'arg1' => $params["username"],
          'arg2' => $params["configoption1"]
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('vesta','ChangePackage','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "Plano da Hospedagem foi alterado";
    } else {
        $result = $answer;
    }

    return $result;
}

function vestacp_ClientArea($params) {

    $code = '<form action="https://'.$params["serverhostname"].':8083/login/" method="post" target="_blank">
<input type="hidden" name="user" value="'.$params["username"].'" />
<input type="hidden" name="password" value="'.$params["password"].'" />
<input type="submit" value="Login to Control Panel" />
<input type="button" value="Login to Webmail" onClick="window.open(\'http://'.$params["serverhostname"].'/webmail\')" />
</form>';
    return $code;

}

function vestacp_AdminLink($params) {

    $code = '<form action="https://'.$params["serverhostname"].':8083/login/" method="post" target="_blank">
<input type="hidden" name="user" value="'.$params["serverusername"].'" />
<input type="hidden" name="password" value="'.$params["serverpassword"].'" />
<input type="submit" value="Acessar WHM" />
</form>';
    return $code;

}

/*function vestacp_LoginLink($params) {

    echo "<a href=\"https://".$params["serverhostname"].":8083/login/\" target=\"_blank\" style=\"color:#cc0000\">control panel</a>";

}*/

function vestacp_UsageUpdate($params) {

    // Prepare variables
    $postvars = array(
      'user' => $params["serverusername"],
      'password' => $params["serverpassword"],
      'hash' => $params["serveraccesshash"],
      'cmd' => 'v-list-users',
      'arg1' => 'json'
    );
    $postdata = http_build_query($postvars);

    // Get user stats
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);

    // Decode json data
    $results = json_decode($answer, true);

    // Loop through results and update DB
    foreach ($results AS $user=>$values) {
        update_query("tblhosting",array(
          "diskusage"=>$values['U_DISK'],
          "disklimit"=>$values['DISK_QUOTA'],
          "bwusage"=>$values['U_BANDWIDTH'],
          "bwlimit"=>$values['BANDWIDTH'],
          "lastupdate"=>"now()",
        ),array("server"=>$params['serverid'], "username"=>$user));
    }

}

function vestacp_DomainScript($params) {

    if ($params["server"] == 1&&!empty($params["configoption4"])) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-make-tmp-file',
          'arg1' => $params["configoption4"],
          'arg2' => '/usr/local/vesta/data/deploy/v_'.$params["domain"].'.sh'
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }
    logModuleCall('vesta','DomainScript','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "Instalação realizada com sucesso!";
    } else {
        $result = $answer;
    }

    return $result;
}

?>
