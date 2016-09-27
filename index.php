<?php

require 'conf.php';

function recursiveDisplay($categories, $parentId = null) {
	foreach ($categories as $category) {
		if ($category['parent_id'] == $parentId) {
			echo "<ul>";
			echo "<li>" . htmlentities($category['name']);
			recursiveDisplay($categories, $category['id']);
			echo "</li>";
			echo "</ul>";
		}
	}
}

try {
	// open connection
	$db = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$db->exec("CREATE TABLE IF NOT EXISTS category (
		id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
		parent_id INT(11) DEFAULT NULL,
		name VARCHAR(100)
	);");
	$stmt = $db->prepare('SELECT * FROM category ORDER BY name, id');
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	recursiveDisplay($categories);	
}
catch (Exception $e) {
	die($e->getMessage());
}

