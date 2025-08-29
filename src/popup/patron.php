<form method="post">
    <div class="row gap-24">
        <p>
            다양한 해택을 안내드립니다.
        </p>
        <div class="row gap-8">
            <p><b>이름</b></p>
            <input type="text" name="pat_name" placeholder="이름"/>
        </div>
        <div class="row gap-8">
            <p><b>이메일</b></p>
            <input type="text" name="email" placeholder="이메일 주소"/>
        </div>
        <div class="row gap-8">
            <p><b>내용</b></p>
            <textarea name="pat_cont"></textarea>
        </div>
        <button type="submit">제출하기</button>
    </div>
</form>
<?php
    // echo do_shortcode('[wpforms id="1512"]');
?>