<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Reset OTP</title>

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
        background: linear-gradient(
          135deg,
          #e7faff 0%,
          #f8feff 50%,
          #e5fbff 100%
        );
        font-family: "Inter", sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
      }

      /* CARD */
      .otp-card {
        width: 100%;
        max-width: 540px;
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #dff4fb;
        box-shadow:
          0 10px 30px rgba(0, 140, 180, 0.1),
          0 4px 12px rgba(0, 0, 0, 0.04);
      }

      /* HEADER */
      .card-header {
        background: linear-gradient(135deg, #10b7c7 0%, #37d5e5 100%);
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
      }

      /* LEFT SIDE */
      .header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
      }

      .logo-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.22);
      }

      .logo-wrapper img {
        width: 36px;
        height: 36px;
        object-fit: contain;
        display: block;
      }

      .brand-text h1 {
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 2px;
      }

      .brand-text p {
        color: rgba(255, 255, 255, 0.92);
        font-size: 12px;
        line-height: 1.4;
      }

      /* RIGHT ICON — properly centered */
      .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
      }

      /* BODY */
      .card-body {
        padding: 24px 22px 20px;
      }

      .welcome-text {
        text-align: center;
        margin-bottom: 18px;
      }

      .welcome-text h2 {
        color: #083344;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 8px;
      }

      .welcome-text p {
        color: #5b7280;
        font-size: 14px;
        line-height: 1.7;
      }

      /* OTP BOX */
      .otp-container {
        background: linear-gradient(135deg, #f0fdff 0%, #ecfeff 100%);
        border: 2px dashed #8be8f3;
        border-radius: 20px;
        padding: 22px 16px;
        text-align: center;
        margin-bottom: 18px;
      }

      .otp-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ddf9fc;
        color: #0f766e;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 14px;
      }

      .otp-label i {
        color: #06b6d4;
      }

      .otp-code {
        font-size: 38px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 10px;
        margin-bottom: 10px;
      }

      .otp-note {
        font-size: 13px;
        color: #5b7280;
      }

      .otp-note strong {
        color: #0f172a;
      }

      /* INFO */
      .info-box {
        background: #f7fdff;
        border-left: 4px solid #10b7c7;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 16px;
        border: 1px solid #dff4fb;
      }

      .info-box p {
        font-size: 13px;
        line-height: 1.7;
        color: #44616d;
      }

      /* SECURITY */
      .security-box {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        background: #f3fdff;
        border: 1px solid #dff4fb;
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 14px;
      }

      .security-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #d9f7fb;
        color: #06b6d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
      }

      .security-content h3 {
        font-size: 14px;
        color: #0f172a;
        margin-bottom: 4px;
        font-weight: 700;
      }

      .security-content p {
        font-size: 12px;
        line-height: 1.6;
        color: #5d7380;
      }

      /* FOOTER */
      .footer {
        border-top: 1px solid #e7f4f8;
        padding-top: 12px;
        text-align: center;
      }

      .footer-brand {
        color: #8aa4af;
        font-size: 11px;
        letter-spacing: 0.3px;
      }

      .footer-brand i {
        color: #06b6d4;
        margin-right: 5px;
      }

      /* MOBILE */
      @media (max-width: 540px) {
        .card-header {
          padding: 16px;
        }

        .card-body {
          padding: 20px 16px;
        }

        .brand-text h1 {
          font-size: 17px;
        }

        .brand-text p {
          font-size: 11px;
        }

        .logo-wrapper {
          width: 48px;
          height: 48px;
        }

        .logo-wrapper img {
          width: 32px;
          height: 32px;
        }

        .otp-code {
          font-size: 30px;
          letter-spacing: 6px;
        }

        .header-icon {
          display: none;
        }

        .welcome-text h2 {
          font-size: 19px;
        }
      }
    </style>
  </head>

  <body>
    <div class="otp-card">
      <!-- HEADER -->
      <div class="card-header">
        <!-- LEFT -->
        <div class="header-left">
          <div class="logo-wrapper">
            <img
              src="{{ asset('mail_logo/logo.png') }}"
              alt="QEC"
              class="logo"
            />
          </div>

          <div class="brand-text">
            <h1>Qatar Esports</h1>
            <p>Secure Password Reset Verification</p>
          </div>
        </div>

        <!-- RIGHT — shield icon perfectly centered -->
        <div class="header-icon">
          <i class="fas fa-shield-halved"></i>
        </div>
      </div>

      <!-- BODY -->
      <div class="card-body">
        <!-- TITLE -->
        <div class="welcome-text">
          <h2>Password Reset OTP</h2>

          <p>
            We received a request to reset your account password. Use the OTP
            below to continue securely.
          </p>
        </div>

        <!-- OTP -->
        <div class="otp-container">
          <div class="otp-label">
            <i class="fas fa-key"></i>
            One Time Password
          </div>

          <div class="otp-code">{{ $otp }}</div>

          <div class="otp-note">Valid for <strong>10 minutes</strong></div>
        </div>

        <!-- INFO -->
        <div class="info-box">
          <p>
            Enter this OTP on the password reset page to verify your identity
            and continue resetting your password.
          </p>
        </div>

        <!-- SECURITY -->
        <div class="security-box">
          <div class="security-icon">
            <i class="fas fa-lock"></i>
          </div>

          <div class="security-content">
            <h3>Security Notice</h3>

            <p>
              If you did not request a password reset, please ignore this email
              safely.
            </p>
          </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
          <div class="footer-brand">
            <i class="fas fa-bolt"></i>
            Powered by Qatar Esports
          </div>
        </div>
      </div>
    </div>
  </body>
</html>