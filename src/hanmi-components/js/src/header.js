import $ from "jquery";
import Vue from "vue";
class Header {
    constructor() {
        this.data = {
        }
    }
    static init() {
        if($('.header').length) {
            const vue = new Vue({
                el:'.header',
                data() {
                    return {
                        search:{
                            active:0,
                            text:''
                        },
                        hambug:0,
                        loginPanel:0,
                    }
                },
                computed:{
                },
                mounted() {
                },
                watch:{
                    search:{
                        deep:true,
                        handler(v) {
                            if(v.active) $('body').addClass('search-form');
                            else $('body').removeClass('search-form');
                        }
                    },
                    hambug(v) {
                        $('body').toggleClass("menu-open");
                    }
                },
                methods:{
                }
            });
            window.header = vue;
        }
    }
}
export default Header;