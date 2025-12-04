<?php
/**
 * example
 * [
 *  ["label 01","artist",Terms array], -> keyboard checkbox
 *  ["label 02","publish",Terms array], -> double sliders
 *  ["label 03","process",[(int) from,(int) to]] -> checkboxes
 * ]
 */
$headers = $arg["filter"] ?? [];
$query = $arg["query"] ?? false;
$class = $arg["class"] ?? "";

if($headers):
?>
<div v-show="utils.filterOpen" class="v-filter filter row <?= $class ?>" ref="filter">
    <?php
        if(count($headers) > 0):
    ?>
    <?php
        // 실제로 표시될 chip 개수 계산
        $visible_chips = 0;
        foreach($headers as $arg) {
            if(count($arg[2] ?? []) > 0) {
                $visible_chips++;
            }
        }
    ?>
    <!-- chip이 1개일 때 상단 탭 완전히 숨김 -->
    <div class="f_head col gap-16" ref="header" <?= ($visible_chips <= 1) ? 'style="display: none;"' : '' ?>>
        <?php 
            foreach($headers as $i=>$arg):
                if(count($arg[2] ?? []) > 0):
        ?>
            <?= comp('chip',['id'=>"filt-${i}","label"=>$arg[0],'input_attr'=>['v-model'=>'curFilter','value'=>$arg[1]]]) ?>
        <?php
                endif;
            endforeach;
        ?>
    </div>
    <?php
        endif;
    ?>
    <div class="f_body row" ref="body">
        <?php
            foreach($headers as $filter):
                switch($filter[1]):
                    case "artist":
        ?>
        <!-- artist -->
        <div class="row" name="artist" v-show="curFilter == 'artist'">
            <div class="col gap-8">
                <div class="box" v-for="(item,i) in utils.alphabets">
                    <input v-model="subFilters.artist_first" type="radio" :id="`check-${i}`" :value="item" />
                    <label :for="`check-${i}`">{{item.toUpperCase()}}</label>
                </div>
            </div>
            <div class="col-6 gap-16 checkbox-grid">
                <?php
                    $artists = $filter[2] ?? [];
                    foreach($artists as $i=>$term):
                        $label = $term->name;
                        $slug = $term->slug;
                        ?>
                <?= comp('checkbox',['label'=>$label,'id'=>"artist-${i}",'attr'=>["v-show"=>"subFilters.artist_first == '' || cho('${label}') == subFilters.artist_first"],'input_attr'=>[':value'=>"{label:'${label}',slug:'${slug}'}","v-model"=>"filters.artists"]]) ?>
                <?php
                    endforeach;
                    ?>
            </div>
        </div>
        <?php
    break;
    case "publish":
        $from = $filter[2][0] ?? 1880;
        $to = $filter[2][1] ?? date('Y');
?>
        <!-- publish -->
        <div class="row gap-0 f-year" name="publish" v-show="curFilter == 'publish' || !curFilter">
            <div>
                <h4 class="reg">
                    {{subFilters.years[0]}}년 - {{subFilters.years[1]}}년
                </h4>
            </div>
            <div class="slider-double"
            @mousedown="ymdown"
            @mousemove="ymmove"
            @mouseup="utils.ymdown = 0"
            @mouseout="utils.ymdown = 0"
            
            @touchstart.self="ymdown"
            @touchmove.self="ymmove"
            @touchend="utils.ymdown = 0"
            >
                <div class="wrap">
                    <div class="line"></div>
                    <div class="line black"
                        :style="`left:${yearToPercent(subFilters.years[0])}%;right:${100 - yearToPercent(subFilters.years[1])}%`">
                    </div>
                    <div class="bullet prev" ref="prev" :style="`left:${yearToPercent(subFilters.years[0])}%`"></div>
                    <div class="bullet next" ref="next" :style="`left:${yearToPercent(subFilters.years[1])}%`"></div>
                </div>
            </div>
            <div class="col gap-16">
                <div>
                    <input type="text" v-model="subFilters.years[0]" @input="updateYearFilters" />
                    ~
                    <input type="text" v-model="subFilters.years[1]" @input="updateYearFilters" />
                </div>
                <input type="hidden" name="from" ref="year_from" value="<?= intval($from) ?>"/>
                <input type="hidden" name="to" ref="year_to" value="<?= intval($to) ?>"/>
            </div>
        </div>
        <?php
    break;
    case "process":
?>
        <!-- process -->
        <div class="row" name="process" v-show="curFilter == 'process'">
            <div class="col-6 gap-16 checkbox-grid">
                <?php
                $processes = $filter[2] ?? [];
                foreach($processes as $i=>$term):
                $label = $term->name;
                $slug = $term->slug;
                ?>
                <?= comp('checkbox',['label'=>$label,'id'=>"process-${i}",'input_attr'=>[':value'=>"{label:'${label}',slug:'${slug}'}","v-model"=>"filters.process"]]) ?>
                <?php
                                        endforeach;
                    ?>
            </div>
        </div>
        <?php
        break;
        endswitch;
            endforeach;
        ?>
    </div>
    <?php
        // 기간필터만 있는지 확인 (publish 타입의 필터가 1개이고 다른 타입이 없는 경우)
        $only_publish_filter = false;
        if ($visible_chips == 1) {
            foreach($headers as $arg) {
                if(count($arg[2] ?? []) > 0 && $arg[1] === 'publish') {
                    $only_publish_filter = true;
                    break;
                }
            }
        }
    ?>
    <div class="f_footer row gap-16 <?= ($only_publish_filter) ? 'only-publish' : '' ?>">
        <!-- filters (tags) -->
        <div class="col gap-16 tags-area" v-show="filter_num > 0" <?= ($only_publish_filter) ? 'style="display: none;"' : '' ?>>
            <div class="button w r filter-btn" v-for="(item,i) in filters.artists" @click="filters.artists.splice(i,1)">
                {{item.label}}
                <?= icon('close') ?>
            </div>
            <div class="button w r filter-btn" v-if="filters.published?.length > 1" @click="filters.published = []">
                {{filters.published[0]}} - {{filters.published[1]}}
                <?= icon('close') ?>
            </div>
            <div class="button w r filter-btn" v-for="(item,i) in filters.process" @click="filters.process.splice(i,1)">
                {{item.label}}
                <?= icon('close') ?>
            </div>
        </div>
        <div :class="`submit col gap-16 align_r`">
            <button class="w" @click="reset" :disabled="(filter_num == 0)">초기화</button>
            <a class="button" :disabled="(filter_num == 0)" :href="submitLink ? `./?<?= ($query)?implode("&",$query)."&":"" ?>${submitLink}` : './'">적용</a>
        </div>
    </div>
</div>
<?php
    endif;
?>

<script>
// 안전한 초기화: from/to가 없을 때 기본 연도 범위 설정 및 기본 탭 보정
document.addEventListener('DOMContentLoaded', function () {
    const safelyInit = () => {
        const vm = window.filter;
        if (!vm) return;

        // 기본 탭: publish가 있고 현재 탭이 비었으면 선택
        try {
            if (!vm.curFilter && vm.$el?.querySelector('[name="publish"]')) {
                vm.curFilter = 'publish';
            }
        } catch (e) {}

        // from/to 파라미터가 없을 때 기본값으로 보정
        try {
            const params = new URLSearchParams(location.search);
            const hasFrom = params.has('from') && params.get('from') !== '';
            const hasTo = params.has('to') && params.get('to') !== '';

            const min = parseInt(vm.$refs?.year_from?.value, 10) || 1880;
            const max = parseInt(vm.$refs?.year_to?.value, 10) || new Date().getFullYear();

            vm.subFilters = vm.subFilters || {};
            vm.filters = vm.filters || {};

            if (!(hasFrom && hasTo)) {
                if (!Array.isArray(vm.subFilters.years) || isNaN(vm.subFilters.years[0]) || isNaN(vm.subFilters.years[1])) {
                    vm.subFilters.years = [min, max];
                }
                if (!Array.isArray(vm.filters.published) || vm.filters.published.length < 2) {
                    vm.filters.published = [min, max];
                }
            }
        } catch (e) {}
    };

    // Vue 인스턴스 로딩을 기다린 뒤 초기화 (경쟁상황 방지)
    let tries = 0;
    const timer = setInterval(() => {
        if (window.filter) {
            clearInterval(timer);
            safelyInit();
        } else if (++tries > 40) {
            clearInterval(timer);
        }
    }, 50);
});
</script>
<style>
/* 기간필터만 존재할 때 하단 태그 영역 숨김 (안전망) */
.v-filter .f_footer.only-publish .tags-area { display: none !important; }
</style>