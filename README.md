## BR Website
<br>

### 基本構成
---

|NO|項目|環境|ソフトウェア|
|--|--|--|--|
|1|OS|AWS ECS Fargate|Debian GNU/Linux 10.4|
|2|WEBサーバー|AWS ECS Fargate|Apache 2.4.38|
|3|プログラミング言語|AWS ECS Fargate|PHP 7.3.18|

<br>

### 利用AWSサービス
---
- S3 (Static Website Hosting)

<br>

### リポジトリ
---

|項目|値|
|--|--|
|バージョン管理システム|Git|
|ソースコード管理ツール|GitHub|
|リポジトリURL|https://github.com/yuichiroyamaji/br_website.git
|ブランチ|master|

<br>

### ディレクトリ構成
---

```
[プロジェクトルート]
  ├ assets/
  ├ docs/
  ├ en/
  ├ include/
  ├ php/
  ├ htaccess.txt
  ├ index_en.php
  ├ index.php
  ├ php.ini
  └ README.md
```

<br>

### ビルド/デプロイ (未実装。今後実装予定)
---

|利用サービス|内訳|動作|
|--|--|--|
|AWS CodePipeline|CodeCommit|リポジトリ<b>【master】</b>ブランチへのプッシュで起動|
||CodeDeploy|タスク更新, ECS Fargateコンテナ反映|

<br>

### ローカル環境構築
---

▼ 本ファイルがあるルートディレクトリにて以下実行
```
docker build -t br . 
docker run -d --privileged --name br -p 8000:80 -v /Users/yuichiroyamaji/Documents/00_repos/20190203_br/br_website:/var/www/html br
```

▼ httpdイメージなど、apache2系のイメージを使用する場合はドキュメントルートが違うため注意
```
docker run -d --privileged --name br -p 8000:80 -v /Users/yuichiroyamaji/Documents/00_repos/20190203_br/br_website:/usr/local/apache2/htdocs httpd
```
