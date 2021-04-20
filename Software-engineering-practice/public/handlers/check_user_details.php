<?php
    require '../../db_connector.php';
    $conn = getConnection();

    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    if(!empty($email)) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    if(!empty($email)) {
        $statement = $conn->prepare("SELECT sep_user_info.userinfo_id
              FROM sep_user_info JOIN sep_users
              ON sep_user_info.user_id = sep_users.user_id
              WHERE sep_users.user_email = ?");
        $statement->execute(array($email));
        if($statement->rowCount() > 0) {
            echo "true";
        }
    }

?>