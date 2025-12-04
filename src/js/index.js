import $ from "jquery";
import Vue from "vue";
import vClickOutside from "v-click-outside";
import Header from "../hanmi-components/js/src/header";
import CommonFilter from "../hanmi-components/js/src/common-filter";
import Swiper from "swiper/bundle";
import { gsap } from "gsap";
import "../css/tailwind.css";
import "swiper/css/bundle";
import "../css/global.less";

window.$ = $;
window.tabs = [];
window.selectboxes = {};
window.addEventListener("load", () => {
  Header.init();
  CommonFilter.init();
  const listeners = ["click", "change"];
  listeners.forEach((l) => {
    $(`*[${l}]`).each((idx, obj) => {
      var evt = $(obj).attr(l);
      $(obj).removeAttr(l);
      console.log(obj);
      if (l == "click") {
        $(obj).addClass("cursor-pointer");
      }
      obj.addEventListener(l, (e) => {
        // console.log(e);
        if (l == "click") e.preventDefault();
        e.stopPropagation();
        try {
          // console.log(evt);
          eval(evt);
        } catch (a) {
          console.log(a);
        }
      });
    });
  });
  $(".button.toggle, button.toggle").on("click", (e) => {
    $(e.currentTarget).toggleClass("open");
  });

  // academy
  academyInit();
});
$(window).on("load", () => {
  //tabs
  $(".tabs-tab").each((idx, obj) => {
    window.tabs.push(obj);
  });
  //img detail
  document.querySelectorAll('*[zoom]').forEach((element) => {
    element.addEventListener('click', (e) => {
      if (e.target.tagName == 'IMG') {
        // 배너 슬라이더의 캡션 토글
        const bannerCaption = e.target.parentElement.querySelector('.banner-caption');
        if (bannerCaption) {
          bannerCaption.style.display = bannerCaption.style.display === 'none' ? 'block' : 'none';
        }
        
        const iov = document.querySelector(".img_overlay:not(.gall_overlay)");
        if (iov) {
          iov.classList.add("active");
          const imgElement = iov.querySelector(".img");
          const captionElement = iov.querySelector(".caption");
          if (imgElement) imgElement.setAttribute("src", e.target.src);
          if (captionElement) captionElement.textContent = e.target.alt ?? "";
        }
      }
    });
  });
  // gallery
  window.gallOv = {};
  window.gallOv.imgs = [];
  document.querySelectorAll("*[gallery]").forEach((obj) => {
    // img list
    const imgs = [...obj.querySelectorAll("img")];
    imgs.forEach((img, i) => {
      img.style.cursor = "pointer";
      img.dataset.idx = i;
      img.addEventListener("click", (e, idx) => {
        window.gallOv.imgs = imgs;
        window.gallOv.idx = i;
        const iov = document.querySelector(".img_overlay.gall_overlay");
        if (iov) {
          iov.classList.add("active");
          const imgElement = iov.querySelector(".img");
          const captionElement = iov.querySelector(".caption");
          const flexContainer = iov.querySelector(".flex");
          if (imgElement) imgElement.setAttribute("src", e.target.src);
          if (captionElement) {
            captionElement.textContent = e.target.alt ?? "";
          }
          // 이미지가 1개일 때 화살표와 caption을 포함한 flex 컨테이너 숨김
          if (flexContainer) {
            if (imgs.length === 1) {
              flexContainer.style.display = "none";
            } else {
              flexContainer.style.display = "";
            }
          }
        }
      });
    });
  });
  window.gallOv.move = (to) => {
    let idx = window.gallOv.idx;
    const len = window.gallOv.imgs.length;
    let next = (idx + to + len) % len; // 음수도 순환
    window.gallOv.imgs[next]?.click();
    // 이미지가 1개일 때 화살표와 caption을 포함한 flex 컨테이너 숨김
    const iov = document.querySelector(".img_overlay.gall_overlay");
    if (iov) {
      const flexContainer = iov.querySelector(".flex");
      if (flexContainer) {
        if (len === 1) {
          flexContainer.style.display = "none";
        } else {
          flexContainer.style.display = "";
        }
      }
    }
  };
  //img gall
  $("*[gall]").each(function () {
    this.addEventListener("click", (e) => {
      const iov = $(".img_overlay");
      iov.addClass("active");
      iov.attr("type", "gall");
      const imgs =
        e.currentTarget
          .querySelector("code")
          ?.textContent.replaceAll("\n", "")
          ?.trim()
          ?.split(",") ?? [];

      const slider = Object.values(sliders).find((a) => {
        return a.el.classList.contains("ov_gall");
      });
      slider.removeAllSlides();
      imgs.forEach((a) => {
        slider.appendSlide(
          `<div class="swiper-slide static"><img src="${a}"></div>`
        );
      });
      slider.update();
      slider.slideTo(0);

      iov
        .find(".gall_ .desc")
        ?.text(e.currentTarget.querySelector("h4")?.textContent);
    });
  });

  //common vue
  if ($(".v-init").length) {
    const vobj = new Vue({
      el: ".v-init",
      data() {
        return {
          checks: [],
          check: [],
          radios: [],
          radio: 1,
        };
      },
      computed: {
        checkedAll() {
          return this.check.sort().join() == this.checks.sort().join();
        },
      },
      mounted() {
        this.checks = [
          ...document.querySelectorAll(".v-init input[type=checkbox]"),
        ]
          ?.map((a) => a.getAttribute("value"))
          .filter((a) => a);
        // this.radios = [...document.querySelectorAll(".v-init input[type=radio]")]?.map(a=>a.getAttribute("value")).filter(a=>(a));
      },
      methods: {
        checkAll() {
          this.check =
            this.check.join() == this.checks.join() ? [] : this.checks;
        },
      },
      updated() {
        console.log(this.radio);
      },
    });
  }
  //slider-common
  window.sliders = {};
  document.querySelectorAll(".swiper").forEach((obj, i) => {
    const id = `slider-${i}`;
    obj.classList.add(id);
    let attr = {
      // loop:true,
      speed: 1000,
      navigation: {
        nextEl: `.${id} .swiper-button-next`,
        prevEl: `.${id} .swiper-button-prev`,
      },
      pagination: {
        el: `.${id} .swiper-pagination`,
        clickable: true,
      },
      // 슬라이드가 1개일 때도 슬라이더가 작동하도록 설정
      allowSlidePrev: true,
      allowSlideNext: true,
    };
    if (obj.dataset) {
      const perview = +obj.dataset["slidesperview"] ?? 1;
      const slides = obj.dataset["mslidesperview"]
        ? +obj.dataset["mslidesperview"]
        : Math.min(1.5, Math.max(perview * 0.5, 1));
      const loop = obj.dataset["loop"] ? +obj.dataset["loop"] : 0;
      let dt = {
        loop: Math.floor(loop),
        breakpoints: {
          600: {
            slidesPerView: perview,
            spaceBetween: obj.dataset["spacebetween"]
              ? +obj.dataset["spacebetween"]
              : 24,
          },
          0: {
            slidesPerView: slides,
            spaceBetween: 16,
          },
        },
        effect: obj.dataset["effect"] ?? "slide",
      };
      if (obj.dataset["auto"]) {
        dt["autoplay"] = {
          delay: obj.dataset["auto"],
          disableOnInteraction: false,
        };
      }
      Object.assign(attr, dt);
    }
    const slides = $(`.${id} .swiper-slide`).length;
    // 슬라이드가 1개일 때도 슬라이더가 작동하도록 설정
    if (slides === 1 && !attr.loop) {
      // 슬라이드가 1개일 때는 navigation 버튼을 숨기거나 비활성화
      // 하지만 슬라이더 자체는 유지
      attr.allowSlidePrev = false;
      attr.allowSlideNext = false;
    }
    const sw = new Swiper(`.${id}`, attr);
    if (obj.classList.contains("main-slider")) {
      sw.on("transitionStart", () => {
        $(".main-slider .slider-loader").removeClass("anim");
        $(".main-slider .text .item").removeClass("active");
      });
      sw.on("transitionEnd", (obj) => {
        const idx = ((obj.activeIndex + 1) % slides) + 1;

        $(".main-slider .slider-loader").addClass("anim");
        $(`.${id} .text > .item:nth-child(${idx})`).addClass("active");
      });
    }
    if (obj.classList.contains("main-slider-what")) {
      let t = $(".what-slide");
      sw.on("transitionEnd", (obj) => {
        // const idx = (obj.activeIndex%slides)%4+1;
        // t.attr("idx",idx);
        const color = obj.slides[obj.activeIndex].dataset.color ?? "#000";
        // t.css({backgroundColor:color});
      });
    }
    const sname = obj.dataset["name"] ?? id;
    if (sname) {
      const target = $(`.idx[target=${sname}]`);
      sw.on("transitionEnd", (obj) => {
        const idx = ((obj.activeIndex + 1) % slides) + 1;
        target.html(idx.toString().padStart(2, "0"));
      });
    }
    sliders[sname] = sw;
  });
  //slider-pic
  document.querySelectorAll(".swiper-pic").forEach((obj, i) => {
    const id = `slider-pic-${i}`;
    obj.classList.add(id);
    let attr = {
      navigation: {
        nextEl: `.${id} .swiper-button-next`,
        prevEl: `.${id} .swiper-button-prev`,
      },
      spaceBetween: 24,
      breakpoints: {
        1280: {
          slidesPerView: 5,
        },
        980: {
          slidesPerView: 2.5,
        },
        0: {
          slidesPerView: 1.5,
          spaceBetween: 16,
        },
      },
    };
    const sw = new Swiper(`.${id}`, attr);
  });
  function getQueries() {
    const s = location.search
      ?.slice(1)
      ?.split("&")
      ?.map((a) => a.split("="));
    let obj = Object.fromEntries(s ?? []);
    return obj;
  }
  function queryToString(query = {}) {
    const q = Object.entries(query)
      .filter((a) => a[0] && a[1] && a[1].length)
      .map((a) => `${a[0]}=${a[1]}`)
      .join("&");
    return q;
  }
  document.querySelectorAll(".select-box").forEach((obj, idx) => {
    obj.id = `select-${idx}`;
    let options = [...obj.querySelector("select").options].map((a) => ({
      value: a.value,
      label: a.label,
    }));
    const name = obj.getAttribute("type") ?? "";
    const queries = getQueries();
    const defaultLabel = obj.getAttribute("default-label") || "전체";
    // GET 파라미터 값이 있으면 사용, 없으면 selected 속성이 있는 option 사용, 둘 다 없으면 첫 번째 옵션
    const getSelectedValue = queries[name] || obj.querySelector("select option[selected]")?.value || options[0]?.value || "";
    const sl = new Vue({
      el: `#${obj.id}`,
      data() {
        return {
          options,
          selected: getSelectedValue,
          open: 0,
          type: obj.getAttribute("type"),
          evt: "",
          defaultLabel: defaultLabel,
        };
      },
      directives: {
        clickOutside: vClickOutside.directive,
      },
      methods: {
        closeOptions() {
          this.open = 0;
        },
        queryLinkTo() {
          let query = getQueries();
          // 선택된 값이 빈 문자열이면 해당 키를 쿼리에서 제거
          if (this.selected && this.selected.length > 0) {
            query[name] = this.selected;
          } else {
            delete query[name];
          }
          let str = `./`;
          if (queryToString(query)?.length) str = `./?${queryToString(query)}`;
          location.href = str;
        },
      },
      watch: {
        selected(v) {
          const input = document.querySelector(`form.search input[name="${this.type}"]`);
          if (input) input.value = v;
          eval(this.evt);
          this.closeOptions();
        },
        options(v) {
          this.selected = v[0].value;
        },
      },
      mounted() {
        // this.$refs.select.click();
        this.evt = this.$refs.select.getAttribute("vchange");
        this.$refs.select.removeAttribute("vchange");
        this.$refs.root?.classList?.add("loaded");
      },
      computed: {
        selectedItem() {
          const found = this.options.find((a) => a.value == this.selected);
          if (found) {
            // 빈 값일 때는 원본 라벨 이름 표시
            if (found.value === "" || found.value === null) {
              return {
                value: "",
                label: this.defaultLabel,
              };
            }
            return found;
          }
          // 기본값: 빈 값일 때 원본 라벨 표시
          return {
            value: "",
            label: this.defaultLabel,
          };
        },
      },
    });
    const vol = name.length ? name : obj.id;
    window.selectboxes[vol] = sl;
  });
  if (location.href.includes("#debug")) {
    const dev = document.createElement("div");
    dev.className = "debug";
    document.body.appendChild(dev);
    document.body.classList.add("dev");
    window.addEventListener("mouseover", (e) => {
      dev.style.display = "block";
      dev.textContent = `${e.target.tagName} ${[...e.target.classList]
        .map((a) => `.${a}`)
        .join(" ")}`;
    });
  }
  // about-sections 내부의 hide 클래스는 제외 (탭 기능을 위해 유지)
  $(".hide").not(".about-sections .hide").removeClass("hide");
});
$(window).on("load resize", () => {
  // slider update
  Object.values(sliders).forEach((sw) => {
    sw.update();
  });

  const h = $(".header").outerHeight();
  $(".content_wrap").css({ marginTop: `${h}px` });
  //floater
  if ($(".floater").length) {
    const floater = $(".floater");
    const footer = $(".footer");
    const fspace = $(".floater_space");
    fspace.css({ height: floater.outerHeight() });
    window.addEventListener("scroll", (e) => {
      const ft = footer[0].getBoundingClientRect().top;
      const fs = fspace[0].getBoundingClientRect().top;
      const sh = window.innerHeight;
      if (fs > sh - floater.outerHeight()) {
        // floater.css({position:"fixed",top:Math.min(sh,ft)});
        floater.css({ position: "fixed" });
      } else floater.css({ position: "relative" });
    });
  }
});
$(window).on("load scroll", (e) => {
  if (document.documentElement.scrollTop > 60) {
    $(".header").addClass("active");
  } else {
    $(".header").removeClass("active");
  }
});
$(window).on("load", () => {
  //fitmainslider
  fitMainSlider();
  function random(min, max) {
    return max - (max - min) * Math.random();
  }
  const ms = $(".main-slider");
  ms.find(".swiper-slide").each((i, obj) => {
    const r = i % 3;
    $(obj).addClass(`slider-${r}`);
    console.log(r);
  });
  const mf = document.querySelectorAll(".main_bottom .text-wrap span");
  const miw = $(".main_bottom .img-wrap > *");
  let mint;
  mf.forEach((d, i) => {
    $(d).on("mouseover", (e) => {
      clearTimeout(mint);
      miw.removeClass("active");
      if (miw[i]) miw[i].classList.add("active");
    });
    $(d).on("mouseout", (e) => {
      mint = setTimeout(() => {
        miw.removeClass("active");
      }, 10);
    });
  });
  //final
  $(".page-wrap,.index,.what-fadeout").addClass("ready");
});
function fitMainSlider() {
  const header = $(".header").outerHeight();
  const floater = $(".floater").outerHeight();
  $(".index > .slider-wrap:first-child").outerHeight(
    window.innerHeight - header - floater + "px"
  );
}
function whatFadeIn(e) {
  e.preventDefault();
  e.stopPropagation();
  const cur = document.createElement("div");
  cur.className = "what-curtain";
  document.body.appendChild(cur);
  setTimeout(() => {
    location.href = e.target.href;
  }, 501);
}
function closePanel() {
  header.search.active = 0;
  header.hambug = 0;
  // dimmer.classList.remove("active");
}
function changeTab(i) {
  console.log(i);
  $(".tabs-tab").removeClass("active");
  $(tabs[i]).addClass("active");
}

function popup(open = false, name = "") {
  panel.querySelector(".cont").innerHTML = "";
  panel.querySelector(".title").innerHTML = " ";

  if (open && ajaxurl) {
    $(panel).addClass("active loading");
    // dimmer.classList.add("active");
    $.post(ajaxurl, { action: "loadPanel", name }).done((r) => {
      if (r.result) {
        panel.classList.remove("loading");
        panel.querySelector(".title").innerHTML = r.title;
        panel.querySelector(".cont").innerHTML = r.content;
      } else {
        popup(false);
      }
    });
  } else {
    panel.classList.remove("active");
  }
}
window.popup = popup;

function archiveToggle(e) {
  e.currentTarget.parentNode.classList.toggle("active");
  setTimeout(() => {
    Object.values(sliders).forEach((a) => a.update());
  }, 100);
}

// fade on scroll
function fadeOnScroll() {
  const f = document.querySelectorAll("*[fade]");
  f.forEach((obj) => {
    const t = obj.getBoundingClientRect().top;
    const h = window.innerHeight * 0.75;
    if (t < h) {
      obj.classList.add("fade-active");
    }
  });
}
window.addEventListener("scroll", fadeOnScroll);

//academy
function academyInit() {
  //sticky
  const stks = document.querySelectorAll(".sticky-wrapper");
  stks.forEach((stk) => {
    const target = stk.querySelector(".sticky");
    addEventListener("scroll", (e) => {
      const stkTop = stk.getBoundingClientRect().top;
      console.log(stkTop);

      const headerHeight = document.querySelector(".header").offsetHeight;

      target.style.top = `${Math.max(0, headerHeight - stkTop)}px`;
    });
  });

  document.querySelectorAll(".m-hori_scroll").forEach((el) => {
    let sanim;
    let prevent = (e) => {
      e.preventDefault();
    };
    el.querySelectorAll("a").forEach((a) => {
      let coro;
      // disable drag
      a.addEventListener("dragstart", (e) => {
        if (window.innerWidth > 980) return;
        e.preventDefault();
        coro = setTimeout(() => {
          a.addEventListener("click", prevent);
        }, 30);
      });

      // disable link when drag end
      a.addEventListener("dragend", (e) => {
        e.preventDefault();
        clearTimeout(coro);
        a.removeEventListener("click", prevent);
      });
    });
    el.addEventListener("touchend", (e) => {
      const target = e.currentTarget;
      const nearestItem = [...target.querySelectorAll(".item")].sort((a, b) => {
        const out =
          Math.abs(a.getBoundingClientRect().x) -
          Math.abs(b.getBoundingClientRect().x);
        return out;
      });
      if (nearestItem[0]) {
        sanim = gsap.to(target, {
          scrollLeft: nearestItem[0].offsetLeft - 15,
        });
      }
    });
    el.addEventListener("touchstart", (e) => {
      if (sanim.stop) sanim.stop();
    });

    let isMouseDown = false;
    el.addEventListener("mousedown", (e) => {
      isMouseDown = true;
    });
    const ls = ["mouseup", "mouseout"];
    ls.forEach((l) => {
      el.addEventListener(l, (e) => {
        if (isMouseDown) {
          const target = e.currentTarget;
          const nearestItem = [...target.querySelectorAll(".item")].sort(
            (a, b) => {
              const out =
                Math.abs(a.getBoundingClientRect().x) -
                Math.abs(b.getBoundingClientRect().x);
              return out;
            }
          );
          if (nearestItem[0]) {
            sanim = gsap.to(target, {
              scrollLeft: nearestItem[0].offsetLeft - 15,
            });
          }
          isMouseDown = false;
        }
      });
    });
    el.addEventListener("mousemove", (e) => {
      // drag
      if (isMouseDown) {
        const target = e.currentTarget;
        target.scrollLeft -= e.movementX;
      }
    });
  });
}
