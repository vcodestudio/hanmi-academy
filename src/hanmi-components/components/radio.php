<div class="radio_group">
    <?php
    $id = $arg['id'];
    $item = $arg['item'];
    foreach($item as $key=>$value):
    ?>
            <div class="item">
                <input type="radio" name="<?= $id ?>" id="<?= "${id}-${key}" ?>" value="<?= $value[1] ?>" change="<?= $arg["change"] ?>"/>
                <label class="w" for="<?= "${id}-${key}" ?>">
                    <?= $value['label'] ?? $value[0] ?? '' ?>
                </label>
            </div>
    <?php endforeach; ?>
        </div>