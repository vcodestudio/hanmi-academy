<?php
    get_header();

    // ACF 필드에서 데이터 가져오기
    $about_intro = get_field('about_intro');
    $about_tabs = get_field('about_tabs');
?>
<div class="page-wrap row detail">
    <?php if ($about_intro): 
        // 인라인 스타일 제거 (글씨 크기, 행간, 자간 등은 CSS 클래스로 제어)
        $about_intro_clean = preg_replace('/style\s*=\s*["\'][^"\']*["\']/i', '', $about_intro);
        // font 태그 제거 (글씨 크기 등은 CSS로 제어)
        $about_intro_clean = preg_replace('/<\/?font[^>]*>/i', '', $about_intro_clean);
        // strong 태그를 b 태그로 변환
        $about_intro_clean = str_replace(['<strong>', '</strong>'], ['<b>', '</b>'], $about_intro_clean);
        // p 태그에 클래스 추가 (WYSIWYG에서 나온 p 태그에 클래스 적용)
        // m:break-keep-all - 모바일에서 단어 단위 줄바꿈
        $about_intro_clean = preg_replace('/<p>/i', '<p class="text-[22px] m:text-[16px] m:[word-break:keep-all] m:[&_br]:hidden">', $about_intro_clean);
        // p 태그가 없는 경우 전체를 p 태그로 감싸기
        if (!preg_match('/<p[^>]*>/i', $about_intro_clean)) {
            $about_intro_clean = '<p class="text-[22px] m:text-[16px] m:[word-break:keep-all] m:[&_br]:hidden">' . $about_intro_clean . '</p>';
        }
    ?>
    <div class="row py-[60px] m:py-[24px] m:pt-0">
        <?= wp_kses_post($about_intro_clean) ?>
    </div>
    <?php endif; ?>

    <?php if ($about_tabs && !empty($about_tabs)): ?>
    <div class="row">
        <div class="about-tabs flex [&>*]:flex-1 m:[&>*]:flex-auto flex-wrap m:[&>*]:basis-[150px]">
            <?php foreach ($about_tabs as $index => $tab): 
                $is_last = ($index === count($about_tabs) - 1);
                $tab_name = $tab['tab_name'] ?? '';
            ?>
            <button class="button !rounded-none !py-4<?= !$is_last ? ' pc:!border-r-0' : '' ?>" data-tab="<?= esc_attr($index) ?>"><?= esc_html($tab_name) ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="about-sections">
    <?php foreach ($about_tabs as $index => $tab): 
        $is_first = ($index === 0);
        $image = $tab['image'] ?? null;
        $image_url = '';
        
        // 이미지 처리: ACF 이미지 배열만 사용
        if ($image && is_array($image) && isset($image['url'])) {
            $image_url = $image['url'];
        } elseif ($image && is_numeric($image)) {
            $image_url = wp_get_attachment_image_url($image, 'full');
        }
        
        // ACF 이미지가 없을 경우 탭 이름에 따라 미디어 라이브러리의 기본 이미지 할당
        if (empty($image_url)) {
            $tab_name = $tab['tab_name'] ?? '';
            $default_image_ids = [
                '정규과정' => 3697,
                '단기과정' => 3698,
                '이론과정' => 3699,
                '창작 스튜디오' => 3700,
                '창작스튜디오' => 3700,
            ];
            
            if (isset($default_image_ids[$tab_name])) {
                $image_url = wp_get_attachment_image_url($default_image_ids[$tab_name], 'full');
            }
        }
    ?>
    <div class="row gap-32 py-[20px] m:gap-16 m:py-[16px]<?= !$is_first ? ' hide' : '' ?>" data-tab="<?= esc_attr($index) ?>">
        <div class="flex gap/8 justify-between flex-wrap gap-y-8 m:gap-x-0 m:gap-y-4">
            <?php if ($image_url): ?>
            <img class="w-full h-auto m:w-[328px] m:order-2" src="<?= esc_url($image_url) ?>" alt="<?= esc_attr($tab['title'] ?? '') ?> 이미지" />
            <?php endif; ?>
            <div class="flex-auto m:order-1">
                <h4 class="bold"><?= esc_html($tab['title'] ?? '') ?></h4>
            </div>
            <div class="flex-none max-w-[640px] w-full m:order-3 keep-all">
                <?= wpautop(esc_html($tab['content'] ?? '')) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <script>
    (function(){
      const tabs = document.querySelectorAll('.about-tabs button');
      const sections = document.querySelectorAll('.about-sections [data-tab]');

      function showTab(key){
        // toggle button styles
        tabs.forEach(b=>b.classList.add('w'));
        const activeBtn = [...tabs].find(b=>b.getAttribute('data-tab')===key);
        if(activeBtn) activeBtn.classList.remove('w');
        // show/hide sections
        sections.forEach(sec=>{
          if(sec.getAttribute('data-tab') === key){
            sec.classList.remove('hide');
          } else {
            sec.classList.add('hide');
          }
        });
      }

      tabs.forEach(btn=>{
        btn.addEventListener('click', function(){
          const key = this.getAttribute('data-tab');
          showTab(key);
        });
      });

      // initialize on load: show first tab
      if(tabs.length > 0) {
        const firstTab = tabs[0].getAttribute('data-tab');
        if(firstTab) showTab(firstTab);
      }
    })();
    </script>
</div>
<?php
    get_footer();
?>
