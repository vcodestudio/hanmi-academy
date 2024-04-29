<?php
$style=$arg['class'];
$pi = $arg['post'] ?? $post ?? get_post(); //WP_Post class
$tags = [];
if($ds = getDateState($pi)) {
    switch($ds['slug']) {
        case "before":
            $tags[] = [
                "label"=>"D-".$ds['remain']
            ];
        break;
        case "current":
            if($ds['remain'] < 10) {
                $tags[] = [
                    "label"=>"종료임박",
                    "class"=>"dark"
                ];
            }
        break;
        case "end":
            $tags[] = [
                "label"=>"종료"
            ];
        break;
    }
}
$tags[] = [
    "link"=>"/",
    "label"=>get_post_type_label($pi)
];
$fields = ["location", "program_theme", "relative_program", "relative_exhibition"];
foreach ($fields as $field) {
    if ($f = get_field($field, $pi)) {
        $tags[] = [
            "label" => $f->name
        ];
    }
}
$tags = array_slice($tags, 0, 3);
?>
<div class="col gap-8">
    <?php foreach($tags as $tag): ?>
    <div href="<?= $tag['link'] ?? 'javascript:void();' ?>" class="tag <?= $style ?? "" ?> <?= $tag["class"] ?? "" ?>">
        <?= $tag['label'] ?>
    </div>
    <?php endforeach; ?>
</div>