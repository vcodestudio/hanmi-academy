//fetch
const pages = 10;
let html = [];

async function dr() {
    for await (let ii of Array.from(Array(pages).keys())) {
        let idx = ii+1;
        var url = `http://photomuseum.or.kr/front/collectionWorkList.do?currentPage=${idx}&searchType=a&search=`;
        const req = await fetch(url);
        const r = await req.text();
        var d = new DOMParser().parseFromString(r, "text/xml");
        $(d).find("ul.list_content li div img").remove();
        $(d).find("ul.list_content li").each(async function(i, dd) {
            let item = [];
            item.push(`http://photomuseum.or.kr${$(dd).find("img").attr("src")}`);
            item.push(`${$(dd).find("div p").text()}`);
            //item.push($(dd).find("div span").text())
            
            const meta_req = await fetch(`http://photomuseum.or.kr/front/${$(dd).find("a").attr("href")}`);
            const meta = await meta_req.text();

            var ddd = new DOMParser().parseFromString(meta, "text/xml");
            const alt = $(ddd).find("p.zoom_in img").attr("alt");
            const imgs = $(ddd).find(".thumbnail_list img").map((i_,d_)=>`http://photomuseum.or.kr${$(d_).attr("largeimg")}`);

            item.push(imgs.toArray().join(","));
            item.push(alt ?? "");

            const itemstr = item.join("\t");

            html.push(itemstr);
        });
    }

    var parse = html.join("\n");
    console.log(parse);

    var txt = document.createElement("textarea");
    document.body.appendChild(txt);
    txt.value = parse;
    txt.select();
    document.execCommand("copy");
    document.body.removeChild(txt);
}
dr();