<?php
/**
 * example
 * [
 *  ["label 01","artist",Terms array], -> keyboard checkbox
 *  ["label 02","publish",Terms array], -> double sliders
 *  ["label 03","process",[(int) from,(int) to]] -> checkboxes
 * ]
 */
$headers = $arg["filter"];
$query = $arg["query"] ?? false;

if($headers):
?>
<div v-show="utils.filterOpen" class="v-filter filter row" ref="filter">
    <?php
        if(count($headers) > 0):
    ?>
    <div class="f_head col gap-16" ref="header">
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
?>
        <!-- publish -->
        <div class="row gap-0 f-year" name="publish" v-show="curFilter == 'publish'">
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
                    <input type="text" v-model="subFilters.years[0]" />
                    ~
                    <input type="text" v-model="subFilters.years[1]" />
                </div>
                <input type="hidden" name="from" ref="year_from" value="<?= $from ?>"/>
                <button class="w" @click="filters.published = [...subFilters.years]">적용</button>
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
    <div class="f_footer row gap-16">
        <!-- filters -->
        <div class="col gap-16" v-show="filter_num > 0">
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
            <a class="button" :disabled="(filter_num == 0)" :href="`./?<?= ($query)?implode("&",$query)."&":"" ?>${submitLink}`">적용</a>
        </div>
    </div>
</div>
<?php
    endif;
?>