<?php
get_header();
?>
<div class="row ui-test">
    <!-- checkbox -->
    <div class="col" data-name="checkbox">
        <?= comp('checkbox',['label'=>'체크박스','attr'=>['id'=>'checkbox-1']]) ?>
    </div>
    <!-- text -->
    <div class="col">
        <?= comp('text',['attr'=>['placeholder'=>'test','class'=>'']]) ?>
        <?= comp('text',['attr'=>['placeholder'=>'test','class'=>'search']]) ?>
    </div>
    <!-- buttons -->
    <div class="col">
        <button>버튼</button>
        <button disabled>버튼 비활성</button>
    </div>
    <div class="col">
        <button class="w">버튼</button>
        <button class="w" disabled>버튼 비활성</button>
    </div>
    <div class="col">
    <?= comp('download',['label'=>'다운로드']) ?>
    </div>
    <!-- chip -->
    <div class="col">
    <?php foreach((['chip-1','chip-2','chip-3']) as $t): ?>
        <?= comp('chip',['id'=>$t,'label'=>$t,'attr'=>['name'=>'test-0']]) ?>
    <?php endforeach; ?>
    </div>
    <!-- radiogroup -->
    <div class="col">
    <?= comp('radio',['item'=>[
        ['label'=>'option 1'],
        ['label'=>'option 2'],
    ],'id'=>'rg']) ?>
    </div>
    <!--radio-->
    <div>
    <?= comp('radios',['item'=>[
        ['label'=>'option 1'],
        ['label'=>'option 2'],
    ],'id'=>'rg']) ?>
    </div>
    <!-- tab -->
    <div class="col">
        <?= comp('tab',get_field('category','option')) ?>
    </div>
    <!-- table -->
    <div class="col">
        <table class="table">
            <tbody>
                <tr>
                    <td>fff</td>
                    <td colspan="2">fff</td>
                </tr>
                <tr>
                    <td>dddd</td>
                    <td>dddd</td>
                    <td>dddd</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- filter -->
    <div class="col v-filter">
        <button :class="`w ${utils.filterOpen?'open':''}`" @click="utils.filterOpen = !utils.filterOpen">
            상세필터
            <?= icon('chevron/up','up') ?>
            <?= icon('chevron/down','down') ?>
        </button>
    </div>
    <div>
    </div>
    <!-- endrow -->
    <!--페이지네이션-->
    <?= comp('pagination',[
        'current'=>$args['paged'] ?? 1,
        'max_page'=>$posts->max_num_pages
    ]) ?>
</div>
<?php
get_footer();