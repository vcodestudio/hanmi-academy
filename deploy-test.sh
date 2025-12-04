#!/bin/bash

# 한미아카데미 테스트서버 배포 스크립트

# 사용법: npm run deploy:test [커밋 메시지]

# 예시: npm run deploy:test "기능 추가: 새로운 페이지 추가"

echo "🚀 한미아카데미 테스트서버 배포를 시작합니다..."

# 서버 정보
SERVER_IP="3.36.128.96"
USER="bitnami"
KEY_FILE="/Users/vcodestudio/Studio/mh-academy/wp-content/themes/hanmi-academy/keys/hanmi-photo.pem"
THEME_PATH="/opt/bitnami/wordpress/wp-content/themes/hanmi-academy"

# 키 파일 존재 확인
if [ ! -f "$KEY_FILE" ]; then
    echo "❌ 키 파일을 찾을 수 없습니다: $KEY_FILE"
    echo "💡 keys 폴더에 키 파일을 추가해주세요."
    exit 1
fi

# 1단계: 빌드 실행
echo "🔨 프로덕션 빌드를 시작합니다..."
npm run build

if [ $? -ne 0 ]; then
    echo "❌ 빌드 중 오류가 발생했습니다. 배포를 중단합니다."
    exit 1
fi

echo "✅ 빌드가 완료되었습니다."

# Git 상태 확인
echo "📋 Git 상태 확인 중..."
git status --short

# 변경사항이 있는지 확인
if [ -n "$(git status --porcelain)" ]; then
    echo "📦 변경사항을 스테이징 중..."
    git add .
    
    # 커밋 메시지 설정
    if [ -n "$1" ]; then
        COMMIT_MSG="$1"
    else
        COMMIT_MSG="테스트서버 배포: $(date '+%Y-%m-%d %H:%M:%S')"
    fi
    
    echo "💾 커밋 중: $COMMIT_MSG"
    git commit -m "$COMMIT_MSG"
    
    if [ $? -ne 0 ]; then
        echo "❌ 커밋 중 오류가 발생했습니다."
        exit 1
    fi
    
    echo "📤 원격 저장소에 푸시 중..."
    git push
    
    if [ $? -ne 0 ]; then
        echo "❌ 푸시 중 오류가 발생했습니다."
        exit 1
    fi
    
    echo "✅ Git 커밋 및 푸시가 완료되었습니다."
else
    echo "ℹ️  커밋할 변경사항이 없습니다."
fi

echo "📡 테스트서버($SERVER_IP)에 연결 중..."

# SSH를 통해 원격 서버에서 git pull 실행 (dev 브랜치)
# 기존 파일이 있어도 강제로 업데이트 (로컬 변경사항은 백업)
ssh -i "$KEY_FILE" "$USER@$SERVER_IP" "cd $THEME_PATH && git fetch origin && git stash 2>/dev/null || true && git checkout -B dev origin/dev 2>/dev/null || (git checkout dev 2>/dev/null && git reset --hard origin/dev) || git reset --hard origin/dev"

# 실행 결과 확인
if [ $? -eq 0 ]; then
    echo "✅ 배포가 성공적으로 완료되었습니다!"
    echo "🌐 사이트 확인: http://$SERVER_IP"
else
    echo "❌ 배포 중 오류가 발생했습니다."
    exit 1
fi

