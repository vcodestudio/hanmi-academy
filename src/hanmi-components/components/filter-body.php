<?php
$headers = [
    ['작가명','artist'],
    ['제작연도','published'],
    ['프로세스 & 재료','process']
];
$artists = get_terms(['taxonomy'=>'artist','hide_empty'=>true]);
$processes = get_terms(['taxonomy'=>'process','hide_empty'=>true]);
?>
<div v-show="utils.filterOpen" class="filter row" ref="filter">
    <div class="f_head col gap-16">
        <!-- <input type="radio" v-model="curFilter" :value="item.slug" :id="`filt-${i}`"/>
                <label class="button r w" :for="`filt-${i}`">{{item.name}}</label> -->
        <?php 
                foreach($headers as $i=>$arg):
                ?>
        <?= comp('chip',['id'=>"filt-${i}","label"=>$arg[0],'input_attr'=>['v-model'=>'curFilter','value'=>$arg[1]]]) ?>
        <?php endforeach; ?>
    </div>
    <div class="f_body row">
        <!-- artist -->
        <div class="row" v-show="curFilter == '<?= $headers[0][1] ?>'">
            <div class="col gap-8">
                <div class="box" v-for="(item,i) in utils.alphabets">
                    <input v-model="subFilters.artist_first" type="radio" :id="`check-${i}`" :value="item" />
                    <label :for="`check-${i}`">{{item.toUpperCase()}}</label>
                </div>
            </div>
            <div class="col-6 gap-16 checkbox-grid">
                <?php
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
        <!-- publish -->
        <div class="row gap-0 f-year" v-show="curFilter == '<?= $headers[1][1] ?>'">
            <div>
                <h4 class="reg">
                    {{subFilters.years[0]}}년 - {{subFilters.years[1]}}년
                </h4>
            </div>
            <div class="slider-double" @mousedown="ymdown" @mousemove="ymmove" @mouseout="utils.ymdown = 0"
                @mouseup="utils.ymdown = 0">
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
                <button class="w" @click="filters.published = [...subFilters.years]">적용</button>
            </div>
        </div>
        <!-- process -->
        <div class="row" v-show="curFilter == '<?= $headers[2][1] ?>'">
            <div class="col-6 gap-16 checkbox-grid">
                <?php
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
            <a class="button" :disabled="(filter_num == 0)" :href="submitLink">적용</a>
        </div>
    </div>
</div>