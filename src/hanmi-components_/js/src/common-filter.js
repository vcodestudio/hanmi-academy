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
                    
                    this.subFilters.years[0] = Math.floor(this.$refs.year_from?.value) ?? 1880;
                    this.subFilters.yearRange[0] = Math.floor(this.$refs.year_from?.value) ?? 1880;
                    // $('.filter, .list, .gallery').addClass('ready');

                    const queries = QueryString.parse(location.search,{arrayFormat:'comma'});
                    this.filters.search = queries.s ?? '';
                    const firstChild = this.$refs.header.querySelector("input");
                    this.curFilter = firstChild.value;

                    this.$el.classList.add("ready");
                    //checkbox
                    // this.utils.ids = this.$refs.list_all.dataset?.ids?.split(",") ?? [];
                    
                },
                watch:{
                    filters:{
                        deep:true,
                        handler(v) {
                            let filter = {
                                artists:v.artists.map(a=>a.slug).join(","),
                                from:v.published[0],
                                to:v.published[1],
                                process:v.process.map(a=>a.slug).join(","),
                            };
                            filter = Object.fromEntries(Object.entries(filter).filter(([_, v]) =>( v!= null && v != [])));
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
                        this.filters = {
                            artists:[],
                            published:[],
                            process:[],
                            search:''
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