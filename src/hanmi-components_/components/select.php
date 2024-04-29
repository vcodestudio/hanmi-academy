<?php
/**
 * [
 *  "type"=>"key",
 *  "options"=>[
 *      ["value","label"],
 *      ...
 * ]
 */
    $name = $arg['name'] ?? $arg["type"] ?? 'post_type';
    $options = $arg["options"] ?? [];
    $selected = $_GET[$name] ?? $_POST[$name] ?? $options[0][0] ?? 0;
?>
<div class="select-box" type="<?= $name ?>" v-click-outside="closeOptions" ref="root">
    <select v-model="selected" ref="select" vchange="<?= $arg["change"] ?? "" ?>" name="<?= $arg["name"] ?? '' ?>">
        <?php
            foreach ($options as $idx=>$item):
            ?>
                <option value="<?= $item[0] ?>" <?= ($item[0]==$selected)?"selected":"" ?>><?= $item[1] ?? $item[0] ?? "select-${idx}" ?></option>
            <?php
            endforeach;
        ?>
    </select>
    <div :class="`select ${open?'open':''}`" @click="open ^= 1">
        <span>
            {{selectedItem.label}}
        </span>
    </div>
    <ul class="option" v-show="open">
        <li v-for="(item,i) in options" :key="i" @click="(selected = item.value) && (open = 0)">{{item.label}}</li>
    </ul>
</div>