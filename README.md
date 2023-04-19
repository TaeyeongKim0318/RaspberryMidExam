# RaspberryMidExam
## 2주차
1. 라즈베리파이 OS Lite 버전을 설치할 수 있는가?
   1. Micro SD 카드를 PC에 연결한다.
   2. Raspberry Pi Imager를 다운로드 후 설치한다.
      - 운영체제를 Raspberry Pi OS Lite (32-bit)를 선택 후 
   3. Micro SD 카드를 라즈베리파이 연결
<br>

2. 라즈베리파이 환경을 설정할 수 있는가?<br>
`sudo raspi-config` 에서 아래 4가지 설정
   1. 사용자 명, password 설정
   2. 무선 네트워크 설정
   3. Timezone 설정
   4. ssh 설정
   5. 소프트웨어 업데이트<br>
   - `sudo apt-get update`, `sudo apt-get upgrade`
<br> 

3. 포트포워딩으로 외부 접속 가능한가?
   1. upnpc를 사용한 ssh(22) 포트 접속 환경 구성
      1. upnp 클라이언트 모듈을 설치
      - `sudo apt -y install miniupnpc`
      2. 라즈베리파이 ip 확인<br>
      - `hostname -I` or `ifconfig`
      3. upnpc로 라즈베리파이 IP의 22 포트를 공유기 포트로 접속할 수 있도록 설정<br>
      - `upnpc -a <raspi IP add> <raspi ssh port> <공유기 포워딩 포트> <통신 방식>`<br>
      - ssh : 22, HTTP : 80, VNC : 5900
      ```
      upnpc -a 192.168.219.200 22 22102 TCP
      ```
      4. 포트 등록 사항 확인
      - `upnpc -l`
      5. 포트 삭제하기<br>
      - `upnpc -d <포워딩된 포트> <통신 방식>`
      ```
      upnpc -d 22102 TCP
      ```
   2. 여러 사람이 함께 사용하기 위한 UPNP지정
      1. bin 폴더 생성 후 쉘을 실행할 수 있도록 폴더 접근 권한 지정<br>
      `mkdir ~pi/bin`, `chmod 775 ~pi/bin`
      2. 포트포워딩을 적용할 쉘 스크립트 편집
      ```
      sleep 10
      uip=$(hostname -I | cut -d " " -f1) && echo "IP:" $uip
      uip3d="000" && echo "prefix:" $uip3d
      # for SSH
      uport="22"$uip3d && echo $uport
      upnpc -d $uport TCP || true
      upnpc -a $uip 22 $uport TCP || true
      ```
      ```
      sleep 10
      uip=$(hostname -I | cut -d " " -f1)
      uport=22$(printf "%03d" $(echo $uip | cut -d "." -f4))
      upnpc -d $uport TCP
      upnpc -a $uip 22 $uport TCP
      upnpc -l | grep $uport
      ```
      uport 설정을 아래와 같이 할 수도 있다.<br>
      - `uport="22"$(printf "%03d" $(echo $uip | cut -d "." -f4))`
<br>

1. Github 저장소를 RPi와 연동할 수 있는가?<br>
가능하지만 보안 방식 변경으로 토큰을 발급해야 된다.
   1. token 발급 순서
      1. Setting -> Developer settings -> tokens -> Generate new token
      2. 원하는 옵션과 note 입력 후 생성
      3. 코드 복사하여 clone 시 password 대신 사용
   2. RPI와 GitHub 연동
      1. git 패키지를 설치한다.
      - `sudo apt-get install git`
      2. git hub에 생성된 repository를 clone한다.
      - `git clone <git hub repository URL>`
      3. 파일 수정 후 Staging area에 추가한다.
      - `git add *`
      4. staged 된 파일을 commit한다.
      - `git commit -m '<Enter Message>'`
      5. git hub에 push 한다.
      - `git push origin main`
<br>

2. 부팅시 쉘을 자동실행할 수 있는가?
   1. Shell 파일의 실행 권한 지정
   - `chmod a+x <실행 파일 경로>`
   ```
   chmod a+x ~pi/bin/autoupnp.sh
   ```
   2. crontab에 추가
   - `crontab -e`
<br>
<br>


### 3주차
1. 아파치 웹서버 설치 방법은 ?
   1. 아파치 서버 패키지 설치
   - `sudo apt-get install apache2`
   2. 웹 서버 루트 디렉토리를 변경
   - `sudo mousepad /etc/apache2/sites-enabled/000-default.conf`
   3. 웹 서버 사용자 권한 확장
   - `sudo usermod -a -G www-data pi`, `sudo usermod -a -G pi www-data`
   4. 홈에 iot 디렉토리 추가
   - `mkdir ~pi/iot/`
   5. iot 디렉토리를 웹 서버와 연결하기
   - `sudo ln -sfT ~pi/iot var/www/iot`
2. HTML, CSS, JS를 활용한 간단한 html 페이지 구성 방법은 ?
3. 웹페이지(HTML, CSS, JS) 오류 추적 방법은 ?
4. PHP 응용프록그램 서버 설치 방법은 ?
5. RESTful 서비스를 만들고 연결하는 방법은 ?
6. 웹서버 오류 추적 방법은 ?
7. 라즈베리파이에서 git 명령어 사용법을 알고 있는가 ?(clone, add, commit, status, push, pull)

### 4주차
1. 파이썬 프로그램의 버전은?
2. 센서에 필요한 파이썬 라이브러리를 설치하는 방법은?
3. 파이썬에서 파일로 데이터를 저장하는 방법은?
4. 파이썬 저장 데이터를 웹화면에서 확인하는 방법은?

### 5주차
1. SQLite는 무엇인가?
2. SQLite는 어떻게 DB파일을 만드는가?
3. SQLite는 어떻게 PHP파일에 연결하는가?
4. SQLite의 사용자는 어떻게 지정하는가?
5. PDO와 SQLIite의 SELECT문장 사용의 차이점은 무엇인가?

### 6주차
1. PDO는 무엇인가?
2. PDO를 사용하여 SQL문장을 준비하는 함수는 무엇인가?
3. PDO를 사용하여 변수를 연결하는 방법 두가지를 무엇인가?

### 7주차
1. 라인그래프란 무엇인가?
2. 그래프에 사용할 라이브러리 이름은 무엇인가?
3. js 클래스로 만드는 이유는?

### 중간고사 출제 예상 쪽집게
1. upnpc를 사용한 ssh 접속 설정
   1. upnp 클라이언트 모듈을 설치
   - `sudo apt -y install miniupnpc`
   2. 라즈베리파이 ip 확인<br>
   - `hostname -I` or `ifconfig`
   3. upnpc로 라즈베리파이 IP의 22 포트를 공유기 포트로 접속할 수 있도록 설정<br>
   - `upnpc -a <raspi IP add> <raspi ssh port> <공유기 포워딩 포트> <통신 방식>`<br>
   - ssh : 22, HTTP : 80, VNC : 5900
   ```
   upnpc -a 192.168.219.200 22 22102 TCP
   ```
   4. 포트 등록 사항 확인
   - `upnpc -l`
   5. 포트 삭제하기<br>
   - `upnpc -d <포워딩된 포트> <통신 방식>`
   ```
   upnpc -d 22102 TCP
   ```


포트포워딩

upnpc 자동 설정
chmod a+x ~pi/bin/autoupnp.sh
crontab -e

tail -f error.log 보는 방법

ajax 방식 통신

SQlite 설치, 테이블 만들기

PHP에서 Update/Insert/Delete 사용방법 2가지
PDO 방법, execute 방법(?)
execute 방법(?)은 건바이 건으로 commit 해서 rollback이 어려움
PDO 방법은 한번에 commit해서 rollback이 쉬움
PDO에서 데이터를 연결하는 방법들(3가지)
