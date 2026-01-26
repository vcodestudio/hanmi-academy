#!/usr/bin/env python3
"""
사이트맵 보강 - 코드베이스의 페이지 템플릿을 기반으로 추가 URL 생성 및 확인
"""

import requests
import json
import re
from pathlib import Path
from urllib.parse import urljoin

BASE_URL = "http://academy.museumhanmi.or.kr"

# 페이지 템플릿 파일에서 슬러그 추출
def extract_page_slugs():
    """pages 디렉토리에서 페이지 슬러그 추출"""
    pages_dir = Path(__file__).parent / "pages"
    slugs = []
    
    # page-*.php 파일 찾기
    for file in pages_dir.glob("page-*.php"):
        # page-xxx.php -> xxx
        slug = file.stem.replace("page-", "")
        slugs.append(slug)
    
    return slugs

# 추가로 확인할 URL 패턴들
def get_additional_urls():
    """추가로 확인할 URL 패턴들"""
    urls = []
    
    # 로그인 관련
    urls.extend([
        "/login/",
        "/login",
        "/register/",
        "/register",
        "/account/",
        "/account",
        "/mypage/",
        "/mypage",
    ])
    
    # 결제 관련 (functions.php에서 확인)
    urls.extend([
        "/order/",
        "/order",
        "/payment-approval/",
        "/payment-approval",
        "/payment-close/",
        "/payment-close",
        "/payment-detail/",
        "/payment-detail",
        "/payment-noti/",
        "/payment-noti",
    ])
    
    # 페이지 템플릿에서 추출한 슬러그들
    page_slugs = extract_page_slugs()
    for slug in page_slugs:
        # 특수 페이지는 제외 (이미 확인됨)
        if slug not in ['test', 'test-pay', 'ui', 'bulk']:
            urls.append(f"/{slug}/")
            urls.append(f"/{slug}")
    
    # 공지사항 관련
    urls.extend([
        "/post_notice/",
        "/post_notice",
        "/notice/",
        "/notice",
    ])
    
    # 활동사진 관련
    urls.extend([
        "/post_activity/",
        "/post_activity",
        "/activity/",
        "/activity",
    ])
    
    # 컬렉션/갤러리 관련
    urls.extend([
        "/collection/",
        "/collection",
        "/gallery/",
        "/gallery",
    ])
    
    # 아카데미 소개 관련
    urls.extend([
        "/guide/",
        "/guide",
        "/history/",
        "/history",
        "/whats-on/",
        "/whats-on",
    ])
    
    # 출판 관련
    urls.extend([
        "/publish/",
        "/publish",
        "/publish-list/",
        "/publish-list",
    ])
    
    return list(set(urls))  # 중복 제거

def check_url_exists(url):
    """URL이 존재하는지 확인"""
    full_url = urljoin(BASE_URL, url)
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    }
    
    try:
        response = requests.get(full_url, headers=headers, timeout=5, allow_redirects=True)
        # 200, 301, 302는 유효한 페이지로 간주
        if response.status_code in [200, 301, 302]:
            return True, response.status_code, response.url
        return False, response.status_code, None
    except:
        return False, None, None

def main():
    print("사이트맵 보강 시작...\n")
    
    # 기존 사이트맵 로드
    try:
        with open('sitemap.json', 'r', encoding='utf-8') as f:
            existing_data = json.load(f)
            existing_urls = set(existing_data['urls'])
    except:
        existing_urls = set()
        existing_data = {'urls': [], 'categories': {}}
    
    print(f"기존 URL 수: {len(existing_urls)}개\n")
    
    # 추가 URL 확인
    additional_urls = get_additional_urls()
    print(f"확인할 추가 URL 수: {len(additional_urls)}개\n")
    
    new_urls = []
    checked = 0
    
    for url in additional_urls:
        checked += 1
        full_url = urljoin(BASE_URL, url)
        
        # 이미 확인한 URL은 스킵
        if full_url in existing_urls:
            continue
        
        print(f"[{checked}/{len(additional_urls)}] 확인: {full_url}", end=" ... ")
        exists, status, final_url = check_url_exists(url)
        
        if exists:
            # 리다이렉트된 최종 URL 사용
            if final_url and final_url != full_url:
                print(f"✓ 리다이렉트: {final_url}")
                if final_url not in existing_urls:
                    new_urls.append(final_url)
            else:
                print(f"✓ 존재함 ({status})")
                new_urls.append(full_url)
        else:
            print(f"✗ 없음")
    
    # 새로운 URL 추가
    all_urls = sorted(list(existing_urls) + new_urls)
    
    print(f"\n새로 발견된 URL: {len(new_urls)}개")
    print(f"총 URL 수: {len(all_urls)}개")
    
    # JSON 저장
    with open('sitemap.json', 'w', encoding='utf-8') as f:
        json.dump({
            'total': len(all_urls),
            'urls': all_urls,
            'categories': existing_data.get('categories', {})
        }, f, ensure_ascii=False, indent=2)
    
    # 마크다운 업데이트
    from datetime import datetime
    with open('sitemap.md', 'w', encoding='utf-8') as f:
        f.write("# 뮤지엄한미 아카데미 사이트맵\n\n")
        f.write(f"**생성일**: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"**총 페이지 수**: {len(all_urls)}개\n\n")
        f.write("---\n\n")
        
        # 카테고리별 분류
        categories = {
            '메인': [],
            '프로그램': [],
            '전시': [],
            '공지사항': [],
            '활동사진': [],
            '갤러리/컬렉션': [],
            '아카데미 소개': [],
            '회원/계정': [],
            '결제/주문': [],
            '마이페이지': [],
            '기타': []
        }
        
        for url in all_urls:
            path = url.replace(BASE_URL, "").lower()
            
            if path == '/' or path == '':
                categories['메인'].append(url)
            elif '/post_program' in path:
                categories['프로그램'].append(url)
            elif '/post_exhibition' in path:
                categories['전시'].append(url)
            elif '/post_notice' in path or '/notice' in path:
                categories['공지사항'].append(url)
            elif '/post_activity' in path or '/activity' in path:
                categories['활동사진'].append(url)
            elif '/collection' in path or '/gallery' in path:
                categories['갤러리/컬렉션'].append(url)
            elif '/about' in path or '/guide' in path or '/faq' in path or '/history' in path or '/whats-on' in path:
                categories['아카데미 소개'].append(url)
            elif '/login' in path or '/register' in path or '/account' in path or '/find' in path:
                categories['회원/계정'].append(url)
            elif '/payment' in path or '/order' in path:
                categories['결제/주문'].append(url)
            elif '/mypage' in path or '/my-account' in path:
                categories['마이페이지'].append(url)
            else:
                categories['기타'].append(url)
        
        for category, category_urls in categories.items():
            if category_urls:
                f.write(f"## {category} ({len(category_urls)}개)\n\n")
                for url in sorted(category_urls):
                    f.write(f"- {url}\n")
                f.write("\n")
        
        f.write("---\n\n")
        f.write("## 전체 URL 목록\n\n")
        for i, url in enumerate(all_urls, 1):
            f.write(f"{i}. {url}\n")
    
    print(f"\n사이트맵 업데이트 완료!")
    print(f"  - sitemap.json")
    print(f"  - sitemap.md")

if __name__ == "__main__":
    main()
