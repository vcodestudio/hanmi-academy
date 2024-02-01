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
if($f = get_field("location",$pi)) $tags[] = [
    "label"=>$f->name
];
if($f = get_field("program_theme",$pi)) $tags[] = [
    "label"=>$f->name
];
if($f = get_field("relative_program",$pi)) $tags[] = [
    "label"=>"연계 프로그램"
];
if($f = get_field("relative_exhibition",$pi)) $tags[] = [
    "label"=>"연계 전시"
];
$tags = array_slice($tags,0,3);
?>
<div class="col gap-8">
    <?php foreach($tags as $tag): ?>
    <div href="<?= $tag['link'] ?? 'javascript:void();' ?>" class="tag <?= $style ?? "" ?> <?= $tag["class"] ?? "" ?>">
        <?= $tag['label'] ?>
    </div>
    <?php endforeach; ?>
</div>