#!/usr/bin/env python3
"""
사이트맵 정리 - 중복 제거 및 정리
"""

import json
from urllib.parse import urlparse, unquote
from datetime import datetime

def normalize_url(url):
    """URL 정규화"""
    parsed = urlparse(url)
    # 쿼리 파라미터 정렬 및 정규화
    path = parsed.path.rstrip('/')
    if not path:
        path = '/'
    
    # 쿼리 파라미터가 있는 경우
    if parsed.query:
        # redirect_to 파라미터는 제거 (로그인 리다이렉트는 별도로 관리)
        if 'redirect_to' in parsed.query:
            return None  # 리다이렉트 URL은 별도 카테고리로 분류
    
    return f"{parsed.scheme}://{parsed.netloc}{path}"

def categorize_url(url):
    """URL 카테고리 분류"""
    path = urlparse(url).path.lower()
    
    if path == '/' or path == '':
        return '메인'
    elif '/post_program' in path:
        return '프로그램 상세'
    elif '/post_exhibition' in path:
        return '전시 상세'
    elif '/post_notice' in path or path == '/notice' or path == '/notice/':
        return '공지사항'
    elif '/post_activity' in path or '/activity' in path or '/page_gallery' in path:
        return '활동사진/갤러리'
    elif '/program' in path and '/post_program' not in path:
        return '프로그램 목록'
    elif '/exhibition' in path and '/post_exhibition' not in path:
        return '전시 목록'
    elif '/about' in path or '/faq' in path or '/guide' in path or '/history' in path or '/academy_archive' in path:
        return '아카데미 소개'
    elif '/login' in path or '/register' in path or '/account' in path or '/find' in path:
        return '회원/계정'
    elif '/payment' in path or '/order' in path:
        return '결제/주문'
    elif '/mypage' in path:
        return '마이페이지'
    elif '/front' in path:
        return '프론트 페이지'
    elif '/private-policy' in path:
        return '정책'
    else:
        return '기타'

def main():
    # 기존 사이트맵 로드
    with open('sitemap.json', 'r', encoding='utf-8') as f:
        data = json.load(f)
    
    # URL 정규화 및 중복 제거
    normalized_urls = {}
    redirect_urls = []
    
    for url in data['urls']:
        normalized = normalize_url(url)
        if normalized is None:
            # 리다이렉트 URL은 별도로 관리
            redirect_urls.append(url)
        elif normalized not in normalized_urls:
            normalized_urls[normalized] = url
    
    unique_urls = sorted(list(normalized_urls.values()))
    
    # 카테고리별 분류
    categories = {}
    for url in unique_urls:
        category = categorize_url(url)
        if category not in categories:
            categories[category] = []
        categories[category].append(url)
    
    # 마크다운 생성
    with open('sitemap.md', 'w', encoding='utf-8') as f:
        f.write("# 뮤지엄한미 아카데미 사이트맵\n\n")
        f.write(f"**생성일**: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"**총 페이지 수**: {len(unique_urls)}개\n")
        f.write(f"**리다이렉트 URL**: {len(redirect_urls)}개\n\n")
        f.write("---\n\n")
        
        # 카테고리별 출력
        for category in sorted(categories.keys()):
            urls = sorted(categories[category])
            f.write(f"## {category} ({len(urls)}개)\n\n")
            for url in urls:
                f.write(f"- {url}\n")
            f.write("\n")
        
        # 리다이렉트 URL 섹션
        if redirect_urls:
            f.write("---\n\n")
            f.write("## 로그인 리다이렉트 URL\n\n")
            f.write("다음 URL들은 로그인이 필요한 페이지로 접근 시 리다이렉트되는 로그인 페이지입니다.\n\n")
            for url in sorted(set(redirect_urls)):
                f.write(f"- {url}\n")
            f.write("\n")
        
        f.write("---\n\n")
        f.write("## 전체 URL 목록\n\n")
        for i, url in enumerate(unique_urls, 1):
            f.write(f"{i}. {url}\n")
    
    # JSON 업데이트
    with open('sitemap.json', 'w', encoding='utf-8') as f:
        json.dump({
            'total': len(unique_urls),
            'redirect_count': len(redirect_urls),
            'urls': unique_urls,
            'redirect_urls': sorted(list(set(redirect_urls))),
            'categories': {k: sorted(v) for k, v in categories.items()}
        }, f, ensure_ascii=False, indent=2)
    
    print(f"사이트맵 정리 완료!")
    print(f"  - 고유 URL: {len(unique_urls)}개")
    print(f"  - 리다이렉트 URL: {len(redirect_urls)}개")
    print(f"  - 카테고리: {len(categories)}개")

if __name__ == "__main__":
    main()
