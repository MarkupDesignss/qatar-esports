<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QEC • Team Invite</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- ICON -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />

    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Inter", sans-serif;
        background: linear-gradient(
          135deg,
          #e7faff 0%,
          #f8feff 50%,
          #e5fbff 100%
        );
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 18px;
        position: relative;
        overflow-x: hidden;
      }

      /* SOFT GLOW */
      body::before {
        content: "";
        position: fixed;
        width: 420px;
        height: 420px;
        background: radial-gradient(
          circle,
          rgba(16, 183, 199, 0.1),
          transparent 70%
        );
        top: -160px;
        left: -160px;
        border-radius: 50%;
        filter: blur(90px);
        pointer-events: none;
      }

      body::after {
        content: "";
        position: fixed;
        width: 420px;
        height: 420px;
        background: radial-gradient(
          circle,
          rgba(55, 213, 229, 0.1),
          transparent 70%
        );
        bottom: -160px;
        right: -160px;
        border-radius: 50%;
        filter: blur(90px);
        pointer-events: none;
      }

      /* CARD */
      .invite-popup {
        width: 100%;
        max-width: 560px;
        background: #ffffff;
        border-radius: 30px;
        overflow: hidden;
        border: 1px solid #dff4fb;
        box-shadow:
          0 10px 30px rgba(0, 140, 180, 0.1),
          0 4px 12px rgba(0, 0, 0, 0.04);
        position: relative;
        z-index: 10;
      }

      /* HEADER */
      .header {
        background: linear-gradient(135deg, #10b7c7 0%, #37d5e5 100%);
        padding: 22px;
      }

      .logo-section {
        display: flex;
        align-items: center;
        gap: 16px;
      }

      .logo {
        width: 64px;
        height: 64px;
        object-fit: contain;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.22);
        padding: 8px;
        flex-shrink: 0;
      }

      .brand {
        flex: 1;
      }

      .brand h1 {
        color: #ffffff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 4px;
      }

      .brand span {
        color: rgba(255, 255, 255, 0.92);
        font-size: 13px;
        line-height: 1.6;
      }

      .brand span i {
        color: #ffffff;
        margin-right: 6px;
      }

      /* BODY */
      .content {
        padding: 24px 22px 22px;
      }

      /* BADGE */
      .badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ddf9fc;
        color: #0f766e;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
      }

      .badge i {
        color: #06b6d4;
      }

      /* INFO ROW */
      .info-row {
        display: flex;
        gap: 14px;
        margin-bottom: 20px;
      }

      .info-box {
        flex: 1;
        background: linear-gradient(135deg, #f0fdff 0%, #ecfeff 100%);
        border: 1px solid #dff4fb;
        border-radius: 20px;
        padding: 16px;
      }

      .info-label {
        color: #5b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
      }

      .info-label i {
        color: #06b6d4;
        margin-right: 6px;
      }

      .info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
      }

      /* MESSAGE */
      .main-text {
        background: #f7fdff;
        border-left: 4px solid #10b7c7;
        border-radius: 18px;
        padding: 16px 18px;
        margin-bottom: 22px;
        border: 1px solid #dff4fb;
        color: #44616d;
        font-size: 14px;
        line-height: 1.8;
      }

      .main-text i {
        color: #06b6d4;
        margin-right: 6px;
      }

      /* BUTTON */
      .join-btn {
        width: 100%;
        text-decoration: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 20px;
        border-radius: 999px;
        background: linear-gradient(135deg, #10b7c7 0%, #37d5e5 100%);
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 22px;
        transition: 0.3s ease;
      }

      .join-btn:hover {
        opacity: 0.92;
      }

      /* COPY BOX */
      .copy-box {
        background: #f3fdff;
        border: 1px solid #dff4fb;
        border-radius: 22px;
        padding: 16px;
      }

      .copy-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #0f766e;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
      }

      .copy-title i {
        color: #06b6d4;
      }

      .copy-row {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        border: 1px solid #dff4fb;
        border-radius: 999px;
        padding: 8px 8px 8px 16px;
      }

      .copy-link {
        flex: 1;
        color: #44616d;
        font-size: 13px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }

      .copy-btn {
        min-width: 44px;
        height: 44px;
        border: none;
        border-radius: 999px;
        background: linear-gradient(135deg, #10b7c7 0%, #37d5e5 100%);
        color: #ffffff;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s ease;
      }

      .copy-btn:hover {
        opacity: 0.92;
      }

      /* FOOTER */
      .footer {
        text-align: center;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #e7f4f8;
        color: #8aa4af;
        font-size: 12px;
      }

      .footer strong {
        color: #10b7c7;
        font-weight: 700;
      }

      /* MOBILE */
      @media (max-width: 540px) {
        .header {
          padding: 18px 16px;
        }

        .content {
          padding: 20px 16px;
        }

        .logo {
          width: 54px;
          height: 54px;
        }

        .brand h1 {
          font-size: 20px;
        }

        .brand span {
          font-size: 12px;
        }

        .info-row {
          flex-direction: column;
        }

        .copy-link {
          font-size: 12px;
        }

        .join-btn {
          font-size: 14px;
        }
      }
    </style>
  </head>

  <body>
    <div class="invite-popup">
      <!-- HEADER -->
      <div class="header">
        <div class="logo-section">
          <img src="{{ asset('mail_logo/logo.png') }}" alt="QEC" class="logo" />

          <div class="brand">
            <h1>Team Invite</h1>

            <span>
              <i class="fa-solid fa-circle-check"></i>
              Join the battle · build legacy
            </span>
          </div>
        </div>
      </div>

      <!-- BODY -->
      <div class="content">
        <!-- BADGE -->
        <div class="badge">
          <i class="fa-solid fa-bolt"></i>
          QEC ESPORTS INVITE
        </div>

        <!-- MESSAGE -->
        <div class="main-text">
          <i class="fa-regular fa-message"></i>
          You've been officially invited to join the squad and compete in the
          tournament.
        </div>

        <!-- BUTTON -->
        <a href="{{ $inviteUrl }}" class="join-btn">
          <i class="fa-solid fa-gamepad"></i>
          JOIN TEAM
        </a>

        <!-- COPY -->
        <div class="copy-box">
          <div class="copy-title">
            <i class="fa-solid fa-link"></i>
            Invite Link
          </div>

          <div class="copy-row">
            <div class="copy-link" id="inviteLink">{{ $inviteUrl }}</div>

            <button class="copy-btn" onclick="copyInvite()">
              <i class="fa-regular fa-copy"></i>
            </button>
          </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">Powered by <strong>QEC Esports</strong></div>
      </div>
    </div>

    <script>
      function copyInvite() {
        const text =
          "https://www.markupdesigns.net/qec-web/tourmainpage/FiFi%20Cup?invite=emXEcNRv75E28Sry";

        navigator.clipboard.writeText(text).then(() => {
          const btn = document.querySelector(".copy-btn");

          btn.innerHTML = '<i class="fa-solid fa-check"></i>';

          setTimeout(() => {
            btn.innerHTML = '<i class="fa-regular fa-copy"></i>';
          }, 2000);
        });
      }
    </script>
  </body>
</html>
