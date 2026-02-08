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
    $defaultLabel = $arg["defaultLabel"] ?? "전체";
    // GET 파라미터에서 값 가져오기
    $get_value = $_GET[$name] ?? $_POST[$name] ?? null;
    // GET 값이 options 배열에 있는지 확인
    $selected = null;
    if ($get_value !== null) {
        foreach ($options as $option) {
            if ($option[0] == $get_value) {
                $selected = $get_value;
                break;
            }
        }
    }
    // options 배열에 없으면 기본값 사용
    if ($selected === null) {
        $selected = $options[0][0] ?? "";
    }
?>
<div class="select-box" type="<?= $name ?>" default-label="<?= htmlspecialchars($defaultLabel) ?>" v-click-outside="closeOptions" ref="root">
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
        <li v-for="(item,i) in options.filter(o => o.label !== defaultLabel)" :key="i" @click="(selected = item.value) && (open = 0)">{{item.label}}</li>
    </ul>
</div>