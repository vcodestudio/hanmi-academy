<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    const dmap = new daum.Postcode({
        oncomplete: function(data) {
            console.log(data);
            if(data)
                document.querySelector("input[name=address]").value = data.address;
        }
    });
</script>