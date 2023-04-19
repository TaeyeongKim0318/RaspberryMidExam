# RaspberryMidExam
## 2주차
**1. 라즈베리파이 OS Lite 버전을 설치할 수 있는가?**
   1. Micro SD 카드를 PC에 연결한다.
   2. Raspberry Pi Imager를 다운로드 후 설치한다.
      - 운영체제를 Raspberry Pi OS Lite (32-bit)를 선택 후 
   3. Micro SD 카드를 라즈베리파이 연결
<br>

**2. 라즈베리파이 환경을 설정할 수 있는가?<br>**
`sudo raspi-config` 에서 아래 4가지 설정
   1. 사용자 명, password 설정
   2. 무선 네트워크 설정
   3. Timezone 설정
   4. ssh 설정
   5. 소프트웨어 업데이트<br>
   `sudo apt-get update`, `sudo apt-get upgrade`
<br> 

**3. 포트포워딩으로 외부 접속 가능한가?**
   - **upnpc를 사용한 ssh(22) 포트 접속 환경 구성**
      1. upnp 클라이언트 모듈을 설치
      `sudo apt -y install miniupnpc`
      2. 라즈베리파이 ip 확인<br>
      `hostname -I` or `ifconfig`
      3. upnpc로 라즈베리파이 IP의 22 포트를 공유기 포트로 접속할 수 있도록 설정<br>
      `upnpc -a <raspi IP add> <raspi ssh port> <공유기 포워딩 포트> <통신 방식>`<br>
      ssh : 22, HTTP : 80, VNC : 5900
      ```
      upnpc -a 192.168.219.200 22 22102 TCP
      ```
      5. 포트 등록 사항 확인
      `upnpc -l`
      6. 포트 삭제하기<br>
      `upnpc -d <포워딩된 포트> <통신 방식>`
      ```
      upnpc -d 22102 TCP
      ```
   - **여러 사람이 함께 사용하기 위한 UPNP지정**
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
      # for WEB
      uport="28"$uip3d && echo $uport
      upnpc -d $uport TCP || true
      upnpc -a $uip 80 $uport TCP || true
      # for VNC
      uport="29"$uip3d && echo $uport
      upnpc -d $uport TCP || true
      upnpc -a $uip 5900 $uport TCP || true
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
      `uport="22"$(printf "%03d" $(echo $uip | cut -d "." -f4))`
<br>

**4. Github 저장소를 RPi와 연동할 수 있는가?<br>**
가능하지만 보안 방식 변경으로 토큰을 발급해야 된다.
   - **token 발급 순서**
      1. Setting -> Developer settings -> tokens -> Generate new token
      2. 원하는 옵션과 note 입력 후 생성
      3. 코드 복사하여 clone 시 password 대신 사용
   - **RPI와 GitHub 연동**
      1. git 패키지를 설치한다.<br>
      `sudo apt-get install git`
      2. git hub에 생성된 repository를 clone한다.<br>
      `git clone <git hub repository URL>`
      3. 파일 수정 후 Staging area에 추가한다.<br>
      `git add *`
      4. staged 된 파일을 commit한다.<br>
      `git commit -m '<Enter Message>'`
      5. git hub에 push 한다.<br>
      `git push origin main`
<br>

**5. 부팅시 쉘을 자동실행할 수 있는가?**
   1. Shell 파일의 실행 권한 지정<br>
   `chmod a+x <실행 파일 경로>`
   ```
   chmod a+x ~pi/bin/autoupnp.sh
   ```
   2. crontab에 추가<br>
   `crontab -e`
   ```
   @reboot ~pi/bin/autoupnp.sh
   ```
<br>
<br>


## 3주차
**1. 아파치 웹서버 설치 방법은?**
   1. 아파치 서버 패키지 설치<br>
   `sudo apt-get install apache2`
   2. 웹 서버 루트 디렉토리를 변경
      1. 웹서버의 DocumentRoot를 편집기로 열기<br>
      `sudo nano /etc/apache2/sites-enabled/000-default.conf`
      2. DocumentRoot를 수정<br>
      `DocumentRoot /var/www/html` -> `DocumentRoot /var/www`
   3. 웹 서버 사용자 권한 확장<br>
   `sudo usermod -a -G www-data pi`, `sudo usermod -a -G pi www-data`
   4. 홈에 iot 디렉토리 추가<br>
   `mkdir ~pi/iot/`
   5. iot 디렉토리를 웹 서버와 연결하기<br>
   `sudo ln -sfT ~pi/iot var/www/iot`
<br>

**2. HTML, CSS, JS를 활용한 간단한 html 페이지 구성 방법은?**
   - ??????????????????????
<br>

**3. 웹페이지(HTML, CSS, JS) 오류 추적 방법은?**
   - 라즈베리파이 웹페이지에서 마우스 우클릭 후 inspect 선택하여 개발자 화면의 console에서 확인한다.
<br>

**4. PHP 응용프로그램 서버 설치 방법은?**<br>
`sudo apt-get-y install php libapache2-mod-php`
<br>

**5. RESTful 서비스를 만들고 연결하는 방법은 ?**
   - name.php 참고
<br>

**6. 웹서버 오류 추적 방법은?**
   - `tail -f /var/log/apache2/error.log`
<br>

**7. 라즈베리파이에서 git 명령어 사용법을 알고 있는가 ?(clone, add, commit, status, push, pull)**
   - clone<br>
   `git clone <GitHub Repository URL>`
   - add<br>
   `git add * `
   - commit<br>
   `git commit -m '입력할 메세지'`
   - status<br>
   `git status`
   - push<br>
   `git push origin main`
   - pull<br>
   `git pull`
<br>
<br>

## 4주차
**1. 파이썬 프로그램의 버전은?<br>**
Python 3.9.2
<br>

**2. 센서에 필요한 파이썬 라이브러리를 설치하는 방법은?**
   1. 라즈베리파이 I2C 활성화<br>
   `sudo raspi-config` -> Interface Option -> I2C 활성화
   2. 파이썬 I2C 라이브러리 설치<br>
   `sudo pip install smbus2`
<br>
   
**3. 파이썬에서 파일로 데이터를 저장하는 방법은?**
   1. dictionary를 출력 또는 파일에 저장 가능한 문자열로 변환<br>
   `json.dumps(<데이터>)`
   - 예시
    ```python
   import json
   s = []
   s = json.dumps({"Peter":35,  "Ben":37,  "Joe":43})
   ```
   - 결과
   ```
   string'{"Peter":35,  "Ben":37,  "Joe":43}' => s
   ```
   2. 문자열 json 자료를 python dictinary로 변환<br>
   `json.loads('<문자열>', true)`
   - 예시
   ```python
   j = json.loads('{"Peter":35,  "Ben":37,  "Joe":43}',true)
   ```
   - 결과
   ```
   {"Peter":35,  "Ben":37,  "Joe":43} => j
   ```

**4. 파이썬 저장 데이터를 웹화면에서 확인하는 방법은?**
   - week 4 폴더 확인
<br>
<br>

## 5주차
**1. SQLite는 무엇인가?**
   1. SQLite는 RDB를 라이브러리다.
   2. SQL을 이용하여 데이터 입출력이 가능하다.
   3. 일반 응용 프로그램에서 다량의 데이터를 저장하고 활용하는데 용이하다.
<br>

**2. SQLite는 어떻게 DB파일을 만드는가?**
   1. SQLite 설치<br>
   `sudo apt-get install sqlite3`
   2. DB 파일 생성<br>
   `sqlite3 <파일명.db>`
   ```
   sqlite3 ~/ex.db
   ```
<br>


**3. SQLite는 어떻게 PHP파일에 연결하는가?**
   1. php에서 사용할 수 있도록 패키지 설치<br>
   `sudo apt-get-y install php-sqlite3`
   2. 테이블 생성
      1. DB 파일 생성
      `sqlite3 <파일명.db>`
      2. 테이블 생성
      `CREATE TABLE <테이블 명>(컬럼명1 타입1, 컬럼명2 타입2, ...)`
      ```
      CREATE TABLE ta_iot(time NUMBER, addr TEXT, temp FLOAT, humi FLOAT);
      ```
   3. 테이블 데이터 입력, 조회, 삭제
      - ./week 5/sht_pdo.php 참고
<br>

**4. SQLite의 사용자는 어떻게 지정하는가?**
   `$<변수명> = new PDO("<DB종류명>:<DB파일명>");`
   ```php
   $db = new PDO("sqlite:iot.db");
   ```
<br>

**5. PDO와 SQLIite의 SELECT문장 사용의 차이점은 무엇인가?**
   - SQLite를 사용하면 매개 변수를 바인딩 해줄 수 없지만 PDO를 사용하면 매개변수를 바인딩 해줄 수 있어 쿼리문을 좀더 유연하게 사용할 수 있다.
<br>
<br>

## 6주차
**1. PDO는 무엇인가?**
   - RDB의 표준화 된 데이터 처리 방법이다.
   - 대부분의 RDB에 적용 가능하여 코드 수정없이 다른 RDB로 변경 가능하다.
<br>

**2. PDO를 사용하여 SQL문장을 준비하는 함수는 무엇인가?**
   - `PDO::prepare()`
<br>

**3. PDO를 사용하여 변수를 연결하는 방법 두 가지는 무엇인가?**
   1. bindValue()를 사용하는 방법
   2. prepare()와 execute()를 사용하여 매개 변수를 전달하는 방법
<br>
<br>

## 7주차
**1. 라인그래프란 무엇인가?**
   - 데이터 포인트를 선으로 연결하여 그린 그래프
<br>

**2. 그래프에 사용할 라이브러리 이름은 무엇인가?**
   - Chart.js
<br>

**3. js 클래스로 만드는 이유는?**
   - 반복 재사용을 위해서
<br>
<br>

## 중간고사 출제 예상 쪽집게
**1. upnpc를 사용한 ssh 접속 설정**
   1. upnp 클라이언트 모듈을 설치<br>1
   `sudo apt -y install miniupnpc`
   2. 라즈베리파이 ip 확인<br>
   `hostname -I` or `ifconfig`
   3. upnpc로 라즈베리파이 IP의 22 포트를 공유기 포트로 접속할 수 있도록 설정<br>
   `upnpc -a <raspi IP add> <raspi ssh port> <공유기 포워딩 포트> <통신 방식>`<br>
   ssh : 22, HTTP : 80, VNC : 5900
   ```
   upnpc -a 192.168.219.200 22 22102 TCP
   ```
   4. 포트 등록 사항 확인<br>
   `upnpc -l`
   5. 포트 삭제하기<br>
   `upnpc -d <포워딩된 포트> <통신 방식>`
   ```
   upnpc -d 22102 TCP
   ```
<br>

**2. upnpc 자동 설정**
   1. Shell 파일의 실행 권한 지정<br>
   `chmod a+x <실행 파일 경로>`
   ```
   chmod a+x ~pi/bin/autoupnp.sh
   ```
   2. crontab에 추가<br>
   `crontab -e`
   ```
   @reboot ~pi/bin/autoupnp.sh
   ```
<br>

**3. tail -f error.log 보는 방법<br>**
`tail -f /var/log/apache2/error.log`

<br>

**4. ajax 방식 통신**
   1. XMLHttpRequest 객체 생성
   2. 콜백 함수 지정
      - onreadystatechange 등
   3. 요청 메서드 및 URL 설정 
      - open 메서드 사용
   4. HTTP 요청 헤더 설정
      - setRequestHeader 메서드 사용
   5. 데이터를 담을 FormData 생성
   6. 요청 전송
      - send 메서드 사용
   7. 콜백 함수에서 요청 완료 후 처리
      - readyState와 status 값 확인 후 처리
   8. responseText 또는 responseXML로 응답 데이터 가져오기
      - JSON.parse(request.response) 사용
<br>

**5. SQlite 설치, 테이블 만들기**
   1. SQLite 설치<br>
   `sudo apt-get install sqlite3`
   2. DB 파일 생성<br>
   `sqlite3 <파일명.db>`
   ```
   sqlite3 ~/ex.db
   ```
   3. 테이블 생성<br>
   `CREATE TABLE <테이블 명>(컬럼명1 타입1, 컬럼명2 타입2, ...)`
   ```
   CREATE TABLE ta_iot(time NUMBER, addr TEXT, temp FLOAT, humi FLOAT);
   ```
<br>

**6. PHP에서 Update/Insert/Delete 사용방법 2가지**
   1. bindValue()를 사용하는 방법
   2. prepare()와 execute()를 사용하여 매개 변수를 전달하는 방법
<br>
<br>

## 추가 사항
**1. JSON 변환 PHP코드**
- array 배열을 출력 또는 파일에 저장 가능한 문자열로 변환
```PHP
$s = json_encode(array("Peter"=>35, "Ben"=>37, "Joe"=>43))
```
```
string'{"Peter":35,  "Ben":37,  "Joe":43}'=>$s 
```
- 문자열 json 자료를 php array로 만듦
```PHP
$j = json_decode('{"Peter":35, "Ben":37, "Joe":43}',true)
```
```
array("Peter"=>35, "Ben"=>37, "Joe"=>43) => $j
```
`$j["Peter"] == 35`
<br>

**2. JSON 변환 JS코드**
- array 배열을 출력 또는 파일에 저장 가능한 문자열로 변환
```javascript
s = JSON.stringify({"Peter":35,  "Ben":37,  "Joe":43})
```
```
string'{"Peter":35, "Ben":37, "Joe":43}' => s
```
- 문자열 json 자료를 js object로 만듦
```javascript
j = JSON.parse('{"Peter":35, "Ben":37, "Joe":43}',true)
```
```
object("Peter"=>35, "Ben"=>37, "Joe"=>43) => j
```
`j["Peter"]==j.Peter==35`
<br>

**3. JSON 변환 Python 코드**
- dictionary를 출력 또는 파일에 저장 가능한 문자열로 변환
```python
import json
s=[]
s = json.dumps({"Peter":35,  "Ben":37,  "Joe":43})
```
```
string'{"Peter":35,  "Ben":37,  "Joe":43}' => s
```
- 문자열 json 자료를 python dictinalry로 만듦
```python
j = json.loads('{"Peter":35,  "Ben":37,  "Joe":43}',true)
```
```
{"Peter":35,  "Ben":37,  "Joe":43} => j
```
`j["Peter"] ==35 #비교`<br>
`j["Sam"] = 15 #추가`
<br>

**4. wget 명령어<br>**
`wget <url> -O - 2 /dev/null | cat -`
```
wget --post-data="func=create&name=sam&age=25" \
 http://localhost/iot/name.php -O - 2> /dev/null | cat -
```
<br>

**5. sqlite3의 장단점에 대해 적으시오<br>**
| SQLite | 장점 |단점 |
| :--: | :-- | :-- |
|#1| 라이센스 문제에서 자유롭다. | 많은 데이터를 처리하는데 적합하지 않다. |
|#2| 사용하는 저장공간이 매우 적다. | 서버가 없어 직접 네트워크 엑세스가 불가능하다. |
|#3| 데이터가 단일 파일로 저장된다.| 다른 DB에 비해 보안에 취약하다. |