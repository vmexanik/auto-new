<?php
/**
* $Id: catalog_adm.php,v 2.0 2005/09/08 19:32:45 Kuzma Exp $
*
* Copyright (C) 2005 Kuzma Feskov <kuzma@russofile.ru>
*
* This file may be distributed and/or modified under the terms of the
* "GNU General Public License" version 2 as published by the Free
* Software Foundation and appearing in the file LICENSE included in
* the packaging of this file.
*
* This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
* THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
* PURPOSE.
*
* The "GNU General Public License" (GPL) is available at
* http:*www.gnu.org/copyleft/gpl.html.
*/

ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Catalog demo</title>
<meta http-equiv="content-type" content="text/html; charset=cp1251">
</head>
<body>
<h2>Catalog demo</h2>
[<a href="catalog.php?mode=branch">Visual demo (Branch)</a>]
<?php

include('../connect_dev.php');
/* ------------------------ NEW OBJECT ------------------------ */

require_once('dbtree.class.php');

// Create new object
$dbtree = new dbtree('cat_bmw', 't', $db);

function dbtree_par($t_id, $par){
	global $db, $dbtree;
	return $db->GetOne	(
	" \n select val"
	."\n from ".$dbtree->table."_par"
	."\n where t_id=".$t_id." and par='".$par."'" 
	);
}

/* ------------------------ NAVIGATOR ------------------------ */
$navigator = 'You are here: ';
if (!empty($_GET['t_id'])) {
	$dbtree->Parents((int)$_GET['t_id'], array('t_id'));

	// Check class errors
	if (!empty($dbtree->ERRORS_MES)) {
		echo 'DB Tree Error!';
		echo '<pre>';
		print_r($dbtree->ERRORS_MES);
		if (!empty($dbtree->ERRORS)) {
			print_r($dbtree->ERRORS);
		}
		echo '</pre>';
		exit;
	}

	while ($item = $dbtree->NextRow()) {
				if (@$_GET['t_id'] <> $item['t_id']) {
			$navigator .= '<a href="catalog_adm?t_id=' . $item['t_id'] . '">' . dbtree_par($item['t_id'], "name") . '</a> > ';
		} else {
			$navigator .= '<strong>' . dbtree_par($item['t_id'], "name") . '</strong>';
		}
	}
}

/* ------------------------ MOVE ------------------------ */

/* ------------------------ MOVE 2 ------------------------ */

// Method 2: Assigns a node with all its children to another parent.
if (!empty($_GET['action']) && 'move_2' == $_GET['action']) {

	// Move node ($_GET['t_id']) and its children to new parent ($_POST['section2_id'])
	$dbtree->MoveAll((int)$_GET['t_id'], (int)$_POST['section2_id']);

	// Check errors
	if (!empty($dbtree->ERRORS_MES)) {
		echo 'DB Tree Error!';
		echo '<pre>';
		print_r($dbtree->ERRORS_MES);
		if (!empty($dbtree->ERRORS)) {
			print_r($dbtree->ERRORS);
		}
		echo '</pre>';
		exit;
	}

	header('Location:catalog_adm.php');
	exit;
}

/* ------------------------ MOVE 1 ------------------------ */

// Method 1: Swapping nodes within the same level and limits of one parent with all its children.
if (!empty($_GET['action']) && 'move_1' == $_GET['action']) {

	// Change node ($_GET['t_id']) position and all its childrens to
	// before or after ($_POST['position']) node 2 ($_POST['section2_id'])
	$dbtree->ChangePositionAll((int)$_GET['t_id'], (int)$_POST['section2_id'], $_POST['position']);

	// Check class errors
	if (!empty($dbtree->ERRORS_MES)) {
		echo 'DB Tree Error!';
		echo '<pre>';
		print_r($dbtree->ERRORS_MES);
		if (!empty($dbtree->ERRORS)) {
			print_r($dbtree->ERRORS);
		}
		echo '</pre>';
		exit;
	}

	header('Location:catalog_adm.php');
	exit;
}

/* ------------------------ MOVE FORM------------------------ */

// Move section form
if (!empty($_GET['action']) && 'move' == $_GET['action']) {

	// Prepare the restrictive data for the first method:
	// Swapping nodes within the same level and limits of one parent with all its children
	$current_section = $dbtree->GetNodeInfo((int)$_GET['t_id']);
	$dbtree->Parents((int)$_GET['t_id'], array('t_id'), array('and' => array('t_level = ' . ($current_section[2] - 1))));

	// Check class errors
	if (!empty($dbtree->ERRORS_MES)) {
		echo 'DB Tree Error!';
		echo '<pre>';
		print_r($dbtree->ERRORS_MES);
		if (!empty($dbtree->ERRORS)) {
			print_r($dbtree->ERRORS);
		}
		echo '</pre>';
		exit;
	}

	$item = $dbtree->NextRow();
	$dbtree->Branch($item['t_id'], array('t_id', 'name'), array('and' => array('t_level = ' . $current_section[2])));

	// Create form
    ?>
    <table border="1" cellpadding="5" align="center">
        <tr>
            <td>
                Move section
            </td>
        </tr>
        <tr>
            <td>
                <form action="catalog_adm.php?action=move_1&t_id=<?=$_GET['t_id']?>" method="POST">
                <strong>1) Swapping nodes within the same level and limits of one parent with all its children.</strong><br>
                Choose second section:
                <select name="section2_id">
    <?php

    while ($item = $dbtree->NextRow()) {

        ?>
                    <option value="<?=$item['t_id']?>"><?=dbtree_par($item['t_id'], "name")?> <?php echo $item['t_id'] == (int)$_GET['t_id'] ? '<<<' : ''?></option>
        <?php

    }

    ?>
                </select><br>
                Choose position:
                <select name="position">
                    <option value="after">After</option>
                    <option value="before">Before</option>
                </select><br>
                <center><input type="submit" value="Apply"></center><br>
                </form>
                <form action="catalog_adm.php?action=move_2&t_id=<?=$_GET['t_id']?>" method="POST">
                <strong>2) Assigns a node with all its children to another parent.</strong><br>
                Choose second section:
                <select name="section2_id">
    <?php

    // Prepare the data for the second method:
    // Assigns a node with all its children to another parent
    //$dbtree->Full(array('t_id', 't_level', 'name'), array('or' => array('t_left <= ' . $current_section[0], 't_right >= ' . $current_section[1])));

    // Check class errors
    if (!empty($dbtree->ERRORS_MES)) {
    	echo 'DB Tree Error!';
    	echo '<pre>';
    	print_r($dbtree->ERRORS_MES);
    	if (!empty($dbtree->ERRORS)) {
    		print_r($dbtree->ERRORS);
    	}
    	echo '</pre>';
    	exit;
    }

    while ($item = $dbtree->NextRow()) {

        ?>
                    <option value="<?=$item['t_id']?>"><?=str_repeat('&nbsp;', 6 * $item['t_level'])?><?=dbtree_par($item['t_id'], "name")?> <?php echo $item['t_id'] == (int)$_GET['t_id'] ? '<<<' : ''?></option>
        <?php

    }

    ?>
                </select><br>
                <center><input type="submit" value="Apply"></center><br>
                </form>
            </td>
        </tr>
    </table>
    <?php

}

/* ------------------------ DELETE ------------------------ */

// Delete node ($_GET['t_id']) from the tree wihtout deleting it's children
// All children apps to one level
if (!empty($_GET['action']) && 'delete' == $_GET['action']) {
	$dbtree->Delete((int)$_GET['t_id']);

	// Check class errors
	if (!empty($dbtree->ERRORS_MES)) {
		echo 'DB Tree Error!';
		echo '<pre>';
		print_r($dbtree->ERRORS_MES);
		if (!empty($dbtree->ERRORS)) {
			print_r($dbtree->ERRORS);
		}
		echo '</pre>';
		exit;
	}

	header('Location:catalog_adm.php');
	exit;
}

/* ------------------------ EDIT ------------------------ */

/* ------------------------ EDIT OK ------------------------ */

// Update node ($_GET['t_id']) info
if (!empty($_GET['action']) && 'edit_ok' == $_GET['action']) {
	$sql = 'SELECT * FROM catalog WHERE t_id = ' . (int)$_GET['t_id'];
	$res = $db->Execute($sql);

	// Check adodb errors
	if (FALSE === $res) {
		echo 'internal_error';
		echo '<pre>';
		print_r(array(2, 'SQL query error.', __FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '::' . __LINE__, 'SQL QUERY: ' . $sql, 'SQL ERROR: ' . $db->ErrorMsg()));
		echo '</pre>';
		exit;
	}

	if (0 == $res->RecordCount()) {
		echo 't_not_found';
		exit;
	}
	$sql = $db->GetUpdateSQL($res, $_POST['section']);
	if (!empty($sql)) {
		$res = $db->Execute($sql);

		// Check adodb errors
		if (FALSE === $res) {
			echo 'internal_error';
			echo '<pre>';
			print_r(array(2, 'SQL query error.', __FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '::' . __LINE__, 'SQL QUERY: ' . $sql, 'SQL ERROR: ' . $db->ErrorMsg()));
			echo '</pre>';
			exit;
		}

	}
	header('Location:catalog_adm.php');
	exit;
}

/* ------------------------ EDIT FORM ------------------------ */

// Node edit form
if (!empty($_GET['action']) && 'edit' == $_GET['action']) {
	$sql = 'SELECT name FROM catalog WHERE t_id = ' . (int)$_GET['t_id'];
	$res = $db->GetOne($sql);

	// Check adodb errors
	if (FALSE === $res) {
		echo 'internal_error';
		echo '<pre>';
		print_r(array(2, 'SQL query error.', __FILE__ . '::' . __CLASS__ . '::' . __FUNCTION__ . '::' . __LINE__, 'SQL QUERY: ' . $sql, 'SQL ERROR: ' . $db->ErrorMsg()));
		echo '</pre>';
		exit;
	}

    ?>
    <table border="1" cellpadding="5" align="center">
        <tr>
            <td>
                Edit section
            </td>
        </tr>
        <tr>
            <td align="center">
                <form action="catalog_adm.php?action=edit_ok&t_id=<?=$_GET['t_id']?>" method="POST">
                Section name:<br>
                <input type="text" name="section[name]" value="<?=$res?>"><br><br>
                <input type="submit" name="submit" value="Submit">
                </form>
            </td>
        </tr>
    </table>
    <?php
}

/* ------------------------ ADD ------------------------ */

/* ------------------------ ADD OK ------------------------ */

// Add new node as children to selected node ($_GET['t_id'])
if (!empty($_GET['action']) && 'add_ok' == $_GET['action']) {

	// Add new node
	$dbtree->Insert((int)$_GET['t_id'], '', $_POST['section']);

	// Check class errors
	if (!empty($dbtree->ERRORS_MES)) {
		echo 'DB Tree Error!';
		echo '<pre>';
		print_r($dbtree->ERRORS_MES);
		if (!empty($dbtree->ERRORS)) {
			print_r($dbtree->ERRORS);
		}
		echo '</pre>';
		exit;
	}

	header('Location:catalog_adm.php');
	exit;
}

/* ------------------------ ADD FORM ------------------------ */

// Add new node form
if (!empty($_GET['action']) && 'add' == $_GET['action']) {

    ?>
    <table border="1" cellpadding="5" align="center">
        <tr>
            <td>
                New section
            </td>
        </tr>
        <tr>
            <td align="center">
                <form action="catalog_adm.php?action=add_ok&t_id=<?=$_GET['t_id']?>" method="POST">
                Section name:<br>
                <input type="text" name="section[name]" value=""><br><br>
                <input type="submit" name="submit" value="Submit">
                </form>
            </td>
        </tr>
    </table>
    <?php

}

/* ------------------------ LIST ------------------------ */

// Prepare data to view all tree
//$dbtree->Full('');

if (!isset($_GET['t_id'])) {
	$_GET['t_id'] = 1;
}

// Prepare data to view ajar tree
//   $dbtree->Branch((int)$_GET['t_id'], array('t_id', 't_level', 'name') );
$dbtree->Branch((int)$_GET['t_id'], array('t_id', 't_left', 't_right', 't_level'), ' and a.t_level=B.t_level+1 ');
// Check class errors


if (!empty($dbtree->ERRORS_MES)) {
	echo 'DB Tree Error!';
	echo '<pre>';
	print_r($dbtree->ERRORS_MES);
	if (!empty($dbtree->ERRORS)) {
		print_r($dbtree->ERRORS);
	}
	echo '</pre>';
	exit;
}

    ?>
    <h3>Manage tree:</h3>
    <table border="1" cellpadding="5" width="100%">
        <tr>
            <td width="100%">ID/Section name</td>
            <td colspan="4">Actions</td>
        </tr>
    <?php
    echo $navigator . '<br><br>';
    $counter = 1;
    while ($item = $dbtree->NextRow()) {
    	if ($counter % 2) {
    		$bgcolor = 'lightgreen';
    	} else {
    		$bgcolor = 'yellow';
    	}
    	$counter++;

        ?>
        <tr>
            <td bgcolor="<?=$bgcolor?>">
                <?=str_repeat('&nbsp;', 6 * $item['t_level']) . '<strong>'. '<a href="catalog_adm?t_id=' . $item['t_id'] . '">' . $item['t_id'] . '</a>' .'/'. dbtree_par($item['t_id'], "name")?></strong> [<strong><?=$item['t_left']?></strong>, <strong><?=$item['t_right']?></strong>, <strong><?=$item['t_level']?></strong>]
            </td>
            <td bgcolor="<?=$bgcolor?>">
                <a href="catalog_adm.php?action=add&t_id=<?=$item['t_id']?>">Add</a>
            </td>
            <td bgcolor="<?=$bgcolor?>">
                <a href="catalog_adm.php?action=edit&t_id=<?=$item['t_id']?>">Edit</a>
            </td>
            <td bgcolor="<?=$bgcolor?>">
            
            <?php
            if (0 == $item['t_level']) {
            	echo 'Delete';
            } else {

                ?>
                <a href="catalog_adm.php?action=delete&t_id=<?=$item['t_id']?>">Delete</a>
                <?php
            }
            ?>
            
            </td>
            <td bgcolor="<?=$bgcolor?>">
            
            <?php
            if (0 == $item['t_level']) {
            	echo 'Move';
            } else {

                ?>
                <a href="catalog_adm.php?action=move&t_id=<?=$item['t_id']?>">Move</a>
                <?php
            }
            ?>

            </td>
        </tr>
        <?php
    }

    ?>
    </table>
</body>
</html>
<?php
ob_flush();
$db->Close();
?>
