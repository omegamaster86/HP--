<?php
  session_start();
  $mode = 'input';
  $errmessage = array();
  if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm'] ){
      // 確認画面
    if( !$_POST['fullname'] ) {
        $errmessage[] = "名前を入力してください";
    } else if( mb_strlen($_POST['fullname']) > 100 ){
        $errmessage[] = "名前は100文字以内にしてください";
    }
      $_SESSION['fullname'] = htmlspecialchars($_POST['fullname'], ENT_QUOTES);

      if( !$_POST['furigana'] ) {
        $errmessage[] = "ふりがなを入力してください";
      } else if( mb_strlen($_POST['furigana']) > 100 ){
        $errmessage[] = "ふりがなは100文字以内にしてください";
      }
        $_SESSION['furigana'] = htmlspecialchars($_POST['furigana'], ENT_QUOTES);

      if( !$_POST['email'] ) {
          $errmessage[] = "Eメールを入力してください";
      } else if( mb_strlen($_POST['email']) > 200 ){
          $errmessage[] = "Eメールは200文字以内にしてください";
    } else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
        $errmessage[] = "メールアドレスが不正です";
      }
      $_SESSION['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);

      if( !$_POST['belongs'] ) {
        $errmessage[] = "会社名・所属を入力してください";
      } else if( mb_strlen($_POST['belongs']) > 100 ){
        $errmessage[] = "会社名・所属は100文字以内にしてください";
      }
        $_SESSION['belongs'] = htmlspecialchars($_POST['belongs'], ENT_QUOTES);

      if( !$_POST['message'] ){
          $errmessage[] = "お問い合わせ内容を入力してください";
      } else if( mb_strlen($_POST['message']) > 500 ){
          $errmessage[] = "お問い合わせ内容は500文字以内にしてください";
      }
      $_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

      if( $errmessage ){
        $mode = 'input';
    } else {
        $mode = 'confirm';
    }
  } else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
    $from_name = 'テスト';
    $from_encoded = mb_convert_encoding($from_name, 'UTF-8', 'AUTO');
    $header="From: " .mb_encode_mimeheader($from_encoded) ."  <mail@address.com>";
    $add_header .= "Reply-to:mail@address\n";
    $message  = "※本メールは株式会社 日本医薬品からの自動返信メールです。 \r\n\n"
              . "この度は当社ホームページよりお問合せを頂き誠にありがとうございます。 \r\n\n"
              . "内容を確認後、担当者より2営業日以内にご連絡を差し上げますので少々お待ちくださいませ。 \r\n"
              . "また本メールにお心あたりがない場合、お手数ですがinfo@japanpharma.jpまでご連絡頂ますようお願いいたします。 \r\n\n"
              . "ご入力内容 \r\n"
              . "名前: " . $_SESSION['fullname'] . "\r\n"
              . "ふりがな: " . $_SESSION['furigana'] . "\r\n"
              . "email: " . $_SESSION['email'] . "\r\n"
              . "会社名・所属: " . $_SESSION['belongs'] . "\r\n"
              . "お問い合わせ内容:\r\n"
              . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
      mail($_SESSION['email'],'【株式会社 日本医薬品】お問合せを受け付けました',$message);
    $message  = "HPよりお問い合わせがありました。 \r\n\n"
      . "内容を確認の上、対応をお願いします。 \r\n\n"
      . "お問い合わせ内容 \r\n"
      . "名前: " . $_SESSION['fullname'] . "\r\n"
      . "ふりがな: " . $_SESSION['furigana'] . "\r\n"
      . "email: " . $_SESSION['email'] . "\r\n"
      . "会社名・所属: " . $_SESSION['belongs'] . "\r\n"
      . "お問い合わせ内容:\r\n"
      . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
    mail('info@japanpharma.jp','HPよりお問合せがあります',$message);
    $_SESSION = array();
    $mode = 'send';
  } else {
    $_SESSION['fullname'] = "";
    $_SESSION['furigana'] = "";
    $_SESSION['email']    = "";
    $_SESSION['belongs'] = "";
    $_SESSION['message']  = "";
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>お問い合わせフォーム</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../images/favicon.ico">
</head>
<body>
<?php if( $mode == 'input' ){ ?>
          <!-- 入力画面 -->
          <?php
            if( $errmessage ){
              echo '<div style="color:red;">';
              echo implode('<br>', $errmessage );
              echo '</div>';
            }
          ?>
          <form action="php/mail.php" method="post">  
            <div class="form">
              <div class="form-item">
                <p class="form-item-label">お名前</p>
                <input type="text" name="fullname" value="<?php echo $_SESSION['fullname'] ?>" class="form-item-input" placeholder="田中 太郎">
                
              </div>
              <div class="form-item">
                <p class="form-item-label">ふりがな</p>
                <input type="text" name="furigana" value="<?php echo $_SESSION['furigana'] ?>" class="form-item-input" placeholder="たなか  たろう">
              </div>
              <div class="form-item">
                <p class="form-item-label">メールアドレス</p>
                <input type="email"   name="email" value="<?php echo $_SESSION['email'] ?>" class="form-item-input" placeholder="info@japanpharma.jp">
              </div>
              <div class="form-item">
                <p class="form-item-label">会社名・所属</p>
                <input type="text"   name="belongs" value="<?php echo $_SESSION['belongs'] ?>" class="form-item-input" placeholder="株式会社 日本医薬品">
              </div>
              <div class="form-item">
                <p class="form-item-label">お問い合わせ内容</p>
                <textarea cols="40" rows="8" name="message" class="form-item-textarea"><?php echo $_SESSION['message'] ?></textarea>
              </div>
              <input type="submit" name="confirm" value="確認" class="form-btn" />
            </div>
          </form>

          <?php } else if( $mode == 'confirm' ){ ?>
            <!-- 確認画面 -->
            <form action="mail.php" method="post">
              <div class="form-confirm">
                <p id="contact-confirm-title">ご入力内容にお間違いがないかご確認の上、送信ボタンを押してください。</p>
                <div class="form-item">
                  <p class="form-item-label">お名前</p>
                  <p class="form-item-text"><?php echo $_SESSION['fullname'] ?></p>
                </div>
                <div class="form-item">
                  <p class="form-item-label">ふりがな</p>
                  <p class="form-item-text"><?php echo $_SESSION['furigana'] ?></p>
                </div>
                <div class="form-item">
                  <p class="form-item-label">メールアドレス</p>
                  <p class="form-item-text"><?php echo $_SESSION['email'] ?></p>
                </div>
                <div class="form-item">
                  <p class="form-item-label">会社名・所属</p>
                  <p class="form-item-text"><?php echo $_SESSION['belongs'] ?></p>
                </div>
                <div class="form-item">
                  <p class="form-item-label">お問い合わせ内容</p>
                  <p class="form-item-text"><?php echo nl2br($_SESSION['message']) ?></p>
                </div>
                <input type="submit" name="send" value="送信" class="form-btn"/>
              </div>
            </form>
            <form action="../index.html" method="post">
            <div class="form-confirm-back">
              <input type="submit" name="back" value="戻る" class="form-btn-confirmback"  />
            </div>
            </form>
            <footer class="footer">
              <div class="footer-wrapper">
                <div class="footer-content">
                  <img src="../images/logo.png" alt="FooterLogo" class="footerlogo" >
                  <div class="content-footer-texts">
                    <ul>
                      <li class="list-item">
                        <div class="list-head">社名</div>
                        <div class="list-data">株式会社 日本医薬品</div>
                      </li>
                      <li class="list-item">
                        <div class="list-head">所在地</div>
                        <div class="list-data">
                          <p>〒542-0081</p>
                          <p>大阪府大阪市中央区南船場3丁目7-27-4-C号室</p>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <p class="privacypolicy">
                  <a href="privacypolicy.html">プライバシーポリシー</a>
                </p>
                <p class="copywrites" >&copy;copyright ©text.</p>
              </div>
            </footer>
        <?php } else { ?>
           <!-- 完了画面 -->
           <main>
      <div class="privacypolicy-confirm-content">
          <div class="privacypolicy-confirm-content-texts">
            <div class="form-item-completed">
            <p class="form-item-text">このたびはお問い合わせいただき、ありがとうございます。</p>
            <p class="form-item-text">お問い合わせ内容を確認させていただき、2営業日以内に担当よりご連絡差し上げます。</p>
            <p class="form-item-text">万一、弊社よりご連絡がない場合は、メールアドレスが誤っているか、迷惑メールフォルダ等に振り分けられている可能性がありますので、再度ご確認をお願いいたします。</p>
            <p class="form-item-text">もしくは、送信トラブル等の可能性もありますので、大変お手数ではございますが、もう一度フォームよりお問い合わせください。</p>
            </div>
            <div>
              <input type="button" onclick="location.href='../index.html'" value="トップへ戻る" class="backtotop-btn">
            </div>
          </div>
      </div>
    </main>

    <footer class="footer">
      <div class="footer-wrapper">
        <div class="footer-content">
           <img src="../images/logo.png" alt="FooterLogo" class="footerlogo" >
           <div class="content-footer-texts">
             <ul>
                <li class="list-item">
                  <div class="list-head">社名</div>
                  <div class="list-data">株式会社 日本医薬品</div>
                </li>
                <li class="list-item">
                  <div class="list-head">所在地</div>
                  <div class="list-data">
                    <p>〒542-0081</p>
                    <p>大阪府大阪市中央区南船場3丁目7-27-4-C号室</p>
                  </div>
                </li>
                  </div>
                </div>
                <p class="privacypolicy">
                  <a href="privacypolicy.html">プライバシーポリシー</a>
                </p>
                <p class="copywrites" >&copy;copyright ©text.</p>
              </div>
            </footer>
        <?php } ?>
   </body>
</html>
