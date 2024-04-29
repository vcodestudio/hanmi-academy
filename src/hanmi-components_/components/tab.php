<?php
/**
 * $tabs = [
 *  ["key","label"],
 *  ["key","label"],
 *  ["key","label"]
 * ]
 */
    $tabs = $arg["options"] ?? $arg["items"] ?? [];
    $curtab = $_GET["tab"] ?? $tabs[0][0];
	$curtab = array_search($curtab,array_map("tabKeys",$tabs))?$curtab:$tabs[0][0];
?>
<div class="col tab">
    <?php
		foreach($tabs as $i=>$tab):
	?>
		<a href="./?tab=<?= $tab[0] ?>" class="<?= ($curtab == $tab[0])?"selected":"" ?>">
			<?= $tab[1] ?>
		</a>
	<?php
		endforeach;
	?>
</div>