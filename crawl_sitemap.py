#!/usr/bin/env python3
"""
사이트맵 크롤러 - academy.museumhanmi.or.kr의 모든 페이지를 찾습니다.
"""

import requests
from bs4 import BeautifulSoup
from urllib.parse import urljoin, urlparse
import json
import time
from collections import deque

BASE_URL = "http://academy.museumhanmi.or.kr"
visited_urls = set()
all_urls = set()
queue = deque([BASE_URL + "/"])

def is_valid_url(url):
    """유효한 URL인지 확인"""
    parsed = urlparse(url)
    # 같은 도메인인지 확인
    if parsed.netloc != "academy.museumhanmi.or.kr":
        return False
    # 파일 확장자 제외 (이미지, CSS, JS 등)
    excluded_extensions = ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.css', '.js', '.pdf', '.zip', '.ico']
    path = parsed.path.lower()
    if any(path.endswith(ext) for ext in excluded_extensions):
        return False
    # 관리자 페이지 제외
    if '/wp-admin' in path or '/wp-content' in path or '/wp-includes' in path:
        return False
    # API 엔드포인트 제외
    if '/wp-json' in path or '/wp-json/' in path:
        return False
    return True

def extract_links(html, current_url):
    """HTML에서 링크 추출"""
    soup = BeautifulSoup(html, 'html.parser')
    links = set()
    
    # a 태그에서 href 추출
    for tag in soup.find_all('a', href=True):
        href = tag['href']
        absolute_url = urljoin(current_url, href)
        # 프래그먼트 제거
        absolute_url = absolute_url.split('#')[0]
        if is_valid_url(absolute_url):
            links.add(absolute_url)
    
    return links

def crawl():
    """사이트 크롤링"""
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    }
    
    max_pages = 500  # 최대 페이지 수 제한
    page_count = 0
    
    print(f"크롤링 시작: {BASE_URL}")
    print(f"대기 중인 URL: {len(queue)}개\n")
    
    while queue and page_count < max_pages:
        url = queue.popleft()
        
        if url in visited_urls:
            continue
            
        visited_urls.add(url)
        page_count += 1
        
        try:
            print(f"[{page_count}] 크롤링: {url}")
            response = requests.get(url, headers=headers, timeout=10, allow_redirects=True)
            
            if response.status_code == 200:
                all_urls.add(url)
                links = extract_links(response.text, url)
                
                new_links = links - visited_urls
                for link in new_links:
                    if link not in queue:
                        queue.append(link)
                
                print(f"  ✓ 발견된 링크: {len(links)}개 (신규: {len(new_links)}개)")
            else:
                print(f"  ✗ 상태 코드: {response.status_code}")
                
        except Exception as e:
            print(f"  ✗ 오류: {str(e)}")
        
        time.sleep(0.5)  # 서버 부하 방지
    
    print(f"\n크롤링 완료!")
    print(f"총 방문한 페이지: {len(visited_urls)}개")
    print(f"유효한 페이지: {len(all_urls)}개")
    
    return sorted(all_urls)

def categorize_urls(urls):
    """URL을 카테고리별로 분류"""
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
    
    for url in urls:
        path = urlparse(url).path.lower()
        
        if path == '/' or path == '':
            categories['메인'].append(url)
        elif '/post_program' in path:
            categories['프로그램'].append(url)
        elif '/post_exhibition' in path:
            categories['전시'].append(url)
        elif '/post_notice' in path:
            categories['공지사항'].append(url)
        elif '/post_activity' in path:
            categories['활동사진'].append(url)
        elif '/collection' in path or '/gallery' in path:
            categories['갤러리/컬렉션'].append(url)
        elif '/about' in path or '/guide' in path or '/faq' in path or '/history' in path:
            categories['아카데미 소개'].append(url)
        elif '/login' in path or '/register' in path or '/account' in path or '/find' in path:
            categories['회원/계정'].append(url)
        elif '/payment' in path or '/order' in path:
            categories['결제/주문'].append(url)
        elif '/mypage' in path or '/my-account' in path:
            categories['마이페이지'].append(url)
        else:
            categories['기타'].append(url)
    
    return categories

if __name__ == "__main__":
    urls = crawl()
    
    # JSON으로 저장
    with open('sitemap.json', 'w', encoding='utf-8') as f:
        json.dump({
            'total': len(urls),
            'urls': urls,
            'categories': categorize_urls(urls)
        }, f, ensure_ascii=False, indent=2)
    
    # 마크다운으로 저장
    categories = categorize_urls(urls)
    
    with open('sitemap.md', 'w', encoding='utf-8') as f:
        f.write("# 뮤지엄한미 아카데미 사이트맵\n\n")
        f.write(f"**생성일**: {time.strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"**총 페이지 수**: {len(urls)}개\n\n")
        f.write("---\n\n")
        
        for category, category_urls in categories.items():
            if category_urls:
                f.write(f"## {category} ({len(category_urls)}개)\n\n")
                for url in sorted(category_urls):
                    f.write(f"- {url}\n")
                f.write("\n")
        
        f.write("---\n\n")
        f.write("## 전체 URL 목록\n\n")
        for i, url in enumerate(urls, 1):
            f.write(f"{i}. {url}\n")
    
    print(f"\n사이트맵 저장 완료:")
    print(f"  - sitemap.json")
    print(f"  - sitemap.md")
