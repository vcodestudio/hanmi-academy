import $ from "jquery";
import Vue from "vue";
import * as QueryString from "query-string";
class CommonFilter {
    constructor() {
    }
    static init() {
        if($('.v-filter').length) {
            const vue = new Vue({
                el:'.v-filter',
                data() {
                    return {
                        page:1,
                        category:'',
                        curFilter:"",
                        subFilters:{
                            artist_first:"",
                            years:[1880,new Date().getFullYear()],
                            yearRange:[1880,new Date().getFullYear()]
                        },
                        f_headers:[
                            {name:"작가명", slug:"artist"},
                            {name:"제작연도", slug:"published"},
                            {name:"프로세스 & 재료", slug:"process"},
                        ],
                        filterData:{
                            artists:[],
                            published:[],
                            process:[]
                        },
                        filters:{
                            artists:[],
                            published:[],
                            process:[],
                            search:""
                        },
                        utils:{
                            alphabets:("ㄱㄴㄷㄹㅁㅂㅅㅇㅈㅊㅋㅌㅍㅎabcdefghijklmnopqrstuvwxyz").split("").map(a=>a.toUpperCase()),
                            filterOpen:0,
                            isGallery:1,
                            ymdown:0,
                            ymtarget:'min',
                            list:[],
                            ids:[]
                        },
                        submitLink:'',
                    }
                },
                computed:{
                    filter_num() {
                        return Object.entries(this.filters).map(a=>a[1]?.length ?? 0).reduce((a,b)=>a+b);
                    },
                    list_check() {
                        const max = this.utils.ids.length;
                        return (this.utils.list.length == max);
                    }
                },
                mounted() {
                    // 동적으로 연도 범위 설정 (PHP에서 전달된 값 사용)
                    const rawMin = this.$refs.year_from?.value;
                    const rawMax = this.$refs.year_to?.value;
                    
                    let min = Math.floor(rawMin || 1880);
                    let max = Math.floor(rawMax || new Date().getFullYear());
                    
                    if (isNaN(min)) min = 1880;
                    if (isNaN(max)) max = new Date().getFullYear();
                    
                    // 기본값 설정
                    this.subFilters.years = [min, max];
                    this.subFilters.yearRange = [min, max];
                    this.filters.published = [min, max];
                    
                    // URL 파라미터가 있으면 덮어쓰기
                    const queries = QueryString.parse(location.search,{arrayFormat:'comma'});
                    if (queries.from && queries.to) {
                        const fromNum = parseInt(queries.from, 10);
                        const toNum = parseInt(queries.to, 10);
                        if (!isNaN(fromNum) && !isNaN(toNum)) {
                            this.subFilters.years = [fromNum, toNum];
                            this.filters.published = [fromNum, toNum];
                        }
                    }
                    
                    // Support both WP `s` and our `search` param, trim for cleanliness
                    this.filters.search = (queries.s || queries.search || '').toString().trim();
                    
                    // header ref가 있는 경우에만 처리
                    if (this.$refs.header) {
                        const firstChild = this.$refs.header.querySelector("input");
                        if (firstChild) {
                            this.curFilter = "publish";
                        }
                    }

                    this.$el.classList.add("ready");
                    //checkbox
                    // this.utils.ids = this.$refs.list_all.dataset?.ids?.split(",") ?? [];
                    
                },
                watch:{
                    filters:{
                        deep:true,
                        handler(v) {
                            let filter = {};
                            
                            // artists 필터가 있으면 추가
                            if (v.artists && v.artists.length > 0) {
                                filter.artists = v.artists.map(a=>a.slug).join(",");
                            }
                            
                            // published 필터가 있으면 추가
                            if (v.published && v.published.length >= 2 && v.published[0] && v.published[1]) {
                                filter.from = v.published[0];
                                filter.to = v.published[1];
                            }
                            
                            // process 필터가 있으면 추가
                            if (v.process && v.process.length > 0) {
                                filter.process = v.process.map(a=>a.slug).join(",");
                            }
                            
                            // search 필터가 있으면 추가
                            if (v.search && v.search.trim() !== '') {
                                filter.search = v.search.trim();
                            }
                            
                            const out = QueryString.stringify(filter);
                            this.submitLink = out;
                        }
                    }
                },
                methods:{
                    listExport() {
                        window.alert(`export ids : ${this.utils.list.join(",")}`);
                    },
                    listAllCheck() {
                        if(this.utils.list.length != this.utils.ids.length)
                            this.utils.list = [...this.utils.ids];
                        else
                            this.utils.list = [];
                    },
                    reset() {
                        // 기본값으로 되돌리기
                        const min = Math.floor(this.$refs.year_from?.value) || 1880;
                        const max = Math.floor(this.$refs.year_to?.value) || new Date().getFullYear();
                        
                        this.subFilters.years = [min, max];
                        this.filters = {
                            artists:[],
                            published:[min, max],
                            process:[],
                            search:''
                        };
                        
                        // URL에서 필터 파라미터 제거하고 기본값으로 이동
                        const url = new URL(window.location);
                        url.searchParams.delete('from');
                        url.searchParams.delete('to');
                        url.searchParams.delete('artists');
                        url.searchParams.delete('process');
                        url.searchParams.delete('search');
                        
                        window.location.href = url.toString();
                    },
                    // 전시 필터 전용 메서드들
                    updateFilters() {
                        this.filters.published = [...this.subFilters.years];
                    },
                    applyDateFilter() {
                        if (this.subFilters.years[0] && this.subFilters.years[1]) {
                            const url = new URL(window.location);
                            url.searchParams.set('from', this.subFilters.years[0]);
                            url.searchParams.set('to', this.subFilters.years[1]);
                            
                            // 기존 date 파라미터 제거 (전체 검색을 위해)
                            url.searchParams.delete('date');
                            
                            // 페이지 이동
                            window.location.href = url.toString();
                        }
                    },
                    clearDateFilter() {
                        this.filters.published = [];
                        this.subFilters.years = [1880, new Date().getFullYear()];
                        
                        const url = new URL(window.location);
                        url.searchParams.delete('from');
                        url.searchParams.delete('to');
                        
                        window.location.href = url.toString();
                    },
                    // 일반 필터용 메서드들 (태그만 반영)
                    updateYearFilters() {
                        this.filters.published = [...this.subFilters.years];
                    },
                    applyYearFilters() {
                        if (this.subFilters.years[0] && this.subFilters.years[1]) {
                            const url = new URL(window.location);
                            url.searchParams.set('from', this.subFilters.years[0]);
                            url.searchParams.set('to', this.subFilters.years[1]);
                            
                            // 기존 date 파라미터 제거 (전체 검색을 위해)
                            url.searchParams.delete('date');
                            
                            // 페이지 이동
                            window.location.href = url.toString();
                        }
                    },
                    ymdown(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        this.utils.ymdown = 1;
                        const x = e.offsetX ?? (e.touches[0].clientX - e.target.parentElement.getBoundingClientRect().left);
                        const dist = Math.abs(x - this.$refs.prev.getBoundingClientRect().left)/(Math.abs(x - this.$refs.next.getBoundingClientRect().left)+1);
                        const target = (dist > 1)?'max':'min';
                        this.utils.ymtarget = target;
                    },
                    ymmove(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if(!this.utils.ymdown) return;
                        const x = e.offsetX ?? (e.touches[0].clientX - e.target.parentElement.getBoundingClientRect().left);
                        const target = this.utils.ymtarget;
                        
                        const percent = x/e.target.getBoundingClientRect().width;
                        const year = this.percentToYear(percent);
                        let out;
                        const step = 1;
                        switch(target) {
                            case 'max':
                                out = Math.max(year,this.subFilters.years[0])/step;
                                out = Math.min(this.subFilters.yearRange[1],Math.floor(out)*step);
                                this.subFilters.years = [this.subFilters.years[0],out];
                                break;
                            case 'min':
                                out = Math.min(year,this.subFilters.years[1])/step;
                                out = Math.max(this.subFilters.yearRange[0],Math.floor(out)*step);
                                this.subFilters.years = [out,this.subFilters.years[1]];
                                break;
                        }
                        
                        // 슬라이더 변경 시 태그만 반영 (페이지 이동 없음)
                        this.filters.published = [...this.subFilters.years];
                    },
                    percentToYear(percent) {
                        const range = this.subFilters.yearRange.reduce((a,b)=>b-a);
                        let out = this.subFilters.yearRange[0] + range*percent;
                        return out;
                    },
                    yearToPercent(year) {
                        const range = this.subFilters.yearRange.reduce((a,b)=>b-a);
                        let out = (year-this.subFilters.yearRange[0])/range*100;
                        out = Math.max(0,Math.min(100,out));
                        return out;
                    },
                    cho(str) {
                            str = str.split("")[0];
                            const cho = ["ㄱ","ㄲ","ㄴ","ㄷ","ㄸ","ㄹ","ㅁ","ㅂ","ㅃ","ㅅ","ㅆ","ㅇ","ㅈ","ㅉ","ㅊ","ㅋ","ㅌ","ㅍ","ㅎ"];
                            let result = str.toLowerCase();
                            for(var i=0;i<str.length;i++) {
                                let code = str.charCodeAt(i)-44032;
                                if(code>-1 && code<11172) result = cho[Math.floor(code/588)];
                            }
                            return result;
                    },
                    submit() {
                        const filter = {
                            artists:this.filters.artists.map(a=>a.slug).join(","),
                            from:this.filters.published[0],
                            to:this.filters.published[1],
                            process:this.filters.process.map(a=>a.slug).join(","),
                        }
                        const out = QueryString.stringify(filter);
                        location.href = `${location.origin}/?${out}`;
                    }
                }
            });
            window.filter = vue;
        }
    }
}
export default CommonFilter;