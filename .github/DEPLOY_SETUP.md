# エックスサーバーへの自動デプロイ設定手順

このリポジトリの `main` ブランチが更新されたタイミング(=プルリクエストがマージされた時など)で、
GitHub Actions が自動的にエックスサーバーへファイルをアップロードするように設定できます。

⚠️ **重要**: セキュリティ上の理由により、AI(このツール)は `.github/workflows/` フォルダに
直接ファイルを追加する権限がありません。そのため、以下の**手順0**だけは、恐れ入りますが
リポジトリの管理者様(GitHubアカウントをお持ちの方)ご自身で一度だけ操作をお願いいたします。
以降(手順1〜)はいつも通りの設定作業です。

## 手順0: ワークフローファイルの設置(初回のみ・GitHub画面上の操作)

用意したワークフロー定義は `.github/workflow-templates/deploy-xserver.yml.txt` に格納されています。
これを本来の場所(`.github/workflows/deploy-xserver.yml`)へ、GitHubの画面上でコピーしてください。

1. GitHubでこのリポジトリを開く: `https://github.com/teamkitkumazaki/designd`
2. `main` ブランチで `.github/workflow-templates/deploy-xserver.yml.txt` を開く
3. 右上の鉛筆アイコン(Edit)を押し、内容を全てコピーする
4. 画面上部のパスを `.github/workflows/deploy-xserver.yml` に書き換える
   (ファイル名の末尾の `.txt` を消し、フォルダ名を `workflow-templates` → `workflows` に変更するイメージです)
5. コピーした内容を貼り付け、そのまま `main` ブランチに直接コミットして保存する
   ※ この操作はGitHubへログイン済みの人が行うものなので、AIでは代行できません

これで設定ファイル本体(`.github/workflows/deploy-xserver.yml`)が有効になります。

## 事前に必要な準備(1回だけ実施すればOKです)

GitHubリポジトリに、エックスサーバーのFTP接続情報を「Secrets(秘密情報)」として登録します。
パスワードなどをファイルに直接書かず、安全に管理するための仕組みです。

### 手順

1. GitHubでこのリポジトリを開く
   `https://github.com/teamkitkumazaki/designd`

2. 上部メニューから **Settings**(設定)を開く

3. 左側メニューの **Secrets and variables** → **Actions** を開く

4. **New repository secret** ボタンを押し、以下の4つを1つずつ登録する

   | Name(名前) | 値の内容 | 確認方法 |
   |---|---|---|
   | `XSERVER_FTP_SERVER` | FTPサーバー名 | エックスサーバーのサーバーパネル →「FTP」→「FTP情報」で確認できます。通常 `sv〇〇〇.xserver.jp` の形式です |
   | `XSERVER_FTP_USERNAME` | FTPユーザー名 | 同じくFTP情報ページに記載されています |
   | `XSERVER_FTP_PASSWORD` | FTPパスワード | FTP情報ページ、または初期設定時にメールで送付されたもの |
   | `XSERVER_SERVER_DIR` | アップロード先のフォルダパス | 今回は次の値を推奨します:`/designd.jp/public_html/wp-content/themes/designd2/` |

   ※ `XSERVER_SERVER_DIR` の先頭は、エックスサーバーのFTPにログインした際の「ドメインごとのルートフォルダ」からの相対パスです。
   　念のため、実際にFTPソフト(FileZilla等)で一度ログインし、テーマフォルダの位置が
   　`/designd.jp/public_html/wp-content/themes/designd2/` で間違いないか確認してください。

5. 4つとも登録が終わったら準備完了です。特別な操作は不要で、次に `main` ブランチが更新された時点から自動的に動作します。

## 動作の流れ

1. プルリクエストが `main` ブランチにマージされる
2. GitHub Actionsが自動的に起動
3. リポジトリの最新ファイルが、FTPS(暗号化された安全なFTP)経由でエックスサーバーのテーマフォルダへアップロードされる
4. アップロード完了(通常1〜2分程度)

## 動作確認方法

1. GitHubリポジトリの上部メニューから **Actions** タブを開く
2. 一覧に `Deploy to Xserver` というジョブが表示され、緑のチェックマーク(成功)になっていればOKです
3. 赤い×マークが出た場合は、ジョブをクリックするとエラーの詳細ログが確認できます(多くの場合、Secretsの値の入力ミスが原因です)

## アップロード対象から除外されるファイル

開発中に作成されたバックアップファイルや、Gitの管理用ファイルは自動的にアップロード対象から除外されます。

- `.git` 関連ファイル、`.github` フォルダ
- `.DS_Store`(Macの管理ファイル)
- `*-bk.php` `*-bk_*.php` などのバックアップ用ファイル
- `header (日付).php` のような日付付きの旧ファイル

## 手動で今すぐデプロイしたい場合

1. GitHubリポジトリの **Actions** タブを開く
2. 左側の **Deploy to Xserver** を選択
3. 右側の **Run workflow** ボタンを押すと、`main` ブランチの最新状態を今すぐデプロイできます

## セキュリティに関する補足

- FTPパスワードなどはGitHubの「Secrets」という暗号化された領域に保存され、ログや画面上には表示されません。
- 通信は `ftps`(FTP over SSL/TLS)を使用しており、平文のFTPよりも安全です。
- もしSFTP(SSH経由)に対応させたい場合や、エックスサーバーの「サブドメインごとのFTPアカウント」を新たに発行して権限を絞りたい場合は、別途ご相談ください。
