<div class="row gap-64">

    <div class="row gap-32 detail w-limit">
        <?= comp("slider-banner", ["imgs" => _acf("detail_imgs") ?: _acf("imgs"), "forceSlider" => true, "showBullets" => true]) ?>
        <div class="col-2 gap-32 detail">
            <div class="row gap-24">
                <div class="row gap-12">
                    <h3><?= get_the_title() ?></h3>
                    <h6 class="light"><?= getDateRange() ?></h6>
                </div>
            </div>
            <?php 
            $additional_info = _acf("additional_info");
            $has_content = false;
            if ($additional_info && is_array($additional_info)) {
                foreach ($additional_info as $info) {
                    // <br> 태그만 제거하고 trim
                    $title = isset($info['title']) ? trim(preg_replace('/<br\s*\/?>/i', '', $info['title'])) : '';
                    $description = isset($info['description']) ? trim(preg_replace('/<br\s*\/?>/i', '', $info['description'])) : '';
                    if (!empty($title) || !empty($description) || 
                        (!empty($info['has_attachments']) && !empty($info['attachments']))) {
                        $has_content = true;
                        break;
                    }
                }
            }
            if ($has_content): ?>
            <div class="row gap-24">
                <div class="metabox row gap-24">
                    <?php foreach($additional_info as $info): 
                        // <br> 태그만 제거하고 trim
                        $title = isset($info['title']) ? trim(preg_replace('/<br\s*\/?>/i', '', $info['title'])) : '';
                        $description = isset($info['description']) ? trim(preg_replace('/<br\s*\/?>/i', '', $info['description'])) : '';
                        $has_info_content = !empty($title) || !empty($description) || 
                                           (!empty($info['has_attachments']) && !empty($info['attachments']));
                        if (!$has_info_content) continue;
                        
                        // 첨부파일이 실제로 있는지 확인
                        $has_real_attachments = false;
                        if (!empty($info['has_attachments']) && !empty($info['attachments'])) {
                            foreach ($info['attachments'] as $attachment) {
                                if (!empty($attachment['file'])) {
                                    $has_real_attachments = true;
                                    break;
                                }
                            }
                        }
                    ?>
                        <?php if(!empty($title) || !empty($description)): ?>
                        <div class="flex gap-24">
                            <?php if(!empty($title)): ?>
                                <p class="bold"><?= $title ?></p>
                            <?php endif; ?>
                            <?php if(!empty($description)): ?>
                                <p><?= $description ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php if($has_real_attachments): ?>
                            <div class="row gap-16">
                                <?php foreach($info['attachments'] as $attachment): ?>
                                    <?php if(!empty($attachment['file'])): ?>
                                        <?php
                                        $file = $attachment['file'];
                                        $file_url = $file['url'] ?? '';
                                        $file_label = !empty($attachment['file_label']) 
                                            ? trim($attachment['file_label']) 
                                            : ($file['filename'] ?? '다운로드');
                                        ?>
                                        <div class="flex">
                                            <?= comp("download", [
                                                'label' => $file_label,
                                                'link' => $file_url
                                            ]) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="w-limit row gap-24">
        <?php 
        $desc = _acf("desc");
        // <br> 태그만 제거, 나머지 HTML은 유지
        $desc = $desc ? trim(preg_replace('/<br\s*\/?>/i', '', $desc)) : '';
        if (!empty($desc)): ?>
        <div class="row gap-16">
            <?= $desc ?>
        </div>
        <?php endif; ?>

        <?php if ($f = _acf("imgs")): ?>
        <!-- gallery -->
        <div class="col-4 gap-24 _square" gallery>
            <?php foreach ($f as $item): ?>
            <div class="item">
                <?= img($item, "thumb") ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="w-limit row gap-32">
        <hr />
        <div class="flex">
            <a href="<?= getPage("exhibition")->permalink ?>" class="button">
                목록으로
            </a>
        </div>
    </div>
</div>