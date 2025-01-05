<?php
session_start();

	if (isset($_SESSION['accessLevel'])) {
	  echo json_encode(['success' => true, 'accessLevel' => $_SESSION['accessLevel']]);
	  } else {
	  echo json_encode(['success' => false]);
	}
?>
