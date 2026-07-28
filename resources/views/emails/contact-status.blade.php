<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Status Update</title>

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
        padding: 14px;
      }

      /* CARD */
      .status-card {
        width: 100%;
        max-width: 540px;
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid #dff4fb;
        box-shadow:
          0 10px 28px rgba(0, 140, 180, 0.1),
          0 4px 10px rgba(0, 0, 0, 0.04);
      }

      /* HEADER */
      .card-header {
        background: linear-gradient(135deg, #10b7c7 0%, #37d5e5 100%);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
      }

      .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
      }

      .logo-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.22);
        flex-shrink: 0;
      }

      .logo-wrapper img {
        width: 32px;
        height: 32px;
        object-fit: contain;
        display: block;
      }

      .brand-text h1 {
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 2px;
      }

      .brand-text p {
        color: rgba(255, 255, 255, 0.92);
        font-size: 11px;
      }

      .header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 16px;
        flex-shrink: 0;
      }

      /* BODY */
      .card-body {
        padding: 20px 18px 18px;
      }

      /* TITLE */
      .welcome-text {
        text-align: center;
        margin-bottom: 16px;
      }

      .welcome-text h2 {
        color: #083344;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
      }

      .welcome-text p {
        color: #5b7280;
        font-size: 13px;
        line-height: 1.7;
      }

      /* STATUS */
      .status-box {
        background: linear-gradient(135deg, #f0fdff 0%, #ecfeff 100%);
        border: 2px dashed #8be8f3;
        border-radius: 18px;
        padding: 18px 16px;
        text-align: center;
        margin-bottom: 16px;
      }

      .status-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #ddf9fc;
        color: #0f766e;
        padding: 7px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
      }

      .status-label i {
        color: #06b6d4;
      }

      .status-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
      }

      .status-note {
        font-size: 12px;
        color: #5b7280;
      }

      /* INFO */
      .info-box {
        background: #f7fdff;
        border-left: 4px solid #10b7c7;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 14px;
        border: 1px solid #dff4fb;
      }

      .info-label {
        font-size: 11px;
        font-weight: 700;
        color: #5b7280;
        text-transform: uppercase;
        margin-bottom: 4px;
        letter-spacing: 0.4px;
      }

      .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.6;
      }

      /* RESOLUTION */
      .resolution-box {
        background: #f3fdff;
        border: 1px solid #dff4fb;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 14px;
      }

      .resolution-title {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #ddf9fc;
        color: #0f766e;
        padding: 7px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
      }

      .resolution-title i {
        color: #06b6d4;
      }

      .resolution-text {
        font-size: 13px;
        line-height: 1.7;
        color: #44616d;
        white-space: pre-wrap;
      }

      /* FOOTER TEXT */
      .footer-message {
        background: #f7fdff;
        border: 1px solid #dff4fb;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 14px;
        color: #44616d;
        font-size: 13px;
        line-height: 1.7;
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
      }

      .footer-brand i {
        color: #06b6d4;
        margin-right: 5px;
      }

      /* MOBILE */
      @media (max-width: 520px) {
        .card-header {
          padding: 14px;
        }

        .card-body {
          padding: 18px 14px;
        }

        .header-icon {
          display: none;
        }

        .brand-text h1 {
          font-size: 16px;
        }

        .welcome-text h2 {
          font-size: 18px;
        }

        .status-value {
          font-size: 22px;
        }
      }
    </style>
  </head>

  <body>
    <div class="status-card">
      <!-- HEADER -->
      <div class="card-header">
        <div class="header-left">
          <div class="logo-wrapper">
            <img
              src="{{ asset('mail_logo/logo.png') }}"
              alt="QEC"
              class="logo"
            />
          </div>

          <div class="brand-text">
            <h1>QEC Esports</h1>
            <p>Contact Request Status Update</p>
          </div>
        </div>

        <div class="header-icon">
          <i class="fas fa-envelope-open-text"></i>
        </div>
      </div>

      <!-- BODY -->
      <div class="card-body">
        <!-- TITLE -->
        <div class="welcome-text">
          <h2>Hello {{ $contact->full_name }},</h2>

          <p>Your support request status has been updated.</p>
        </div>

        <!-- STATUS -->
        <div class="status-box">
          <div class="status-label">
            <i class="fas fa-circle-check"></i>
            Current Status
          </div>

          <div class="status-value">
            {{ ucfirst(str_replace('_', ' ', $contact->status)) }}
          </div>

          <div class="status-note">Support team is reviewing your request.</div>
        </div>

        <!-- SUBJECT -->
        <div class="info-box">
          <div class="info-label">Subject</div>

          <div class="info-value">{{ $contact->subject }}</div>
        </div>

        <!-- RESOLUTION -->
        @if($contact->status == 'resolved')

        <div class="resolution-box">
          <div class="resolution-title">
            <i class="fas fa-message"></i>
            Resolution Message
          </div>

          <div class="resolution-text">{{ $contact->resolution }}</div>
        </div>

        @endif

        <!-- THANK YOU -->
        <div class="footer-message">
          Thank you for contacting us.<br />
          Regards,<br />
          <strong>Support Team</strong>
        </div>

        <!-- FOOTER -->
        <div class="footer">
          <div class="footer-brand">
            <i class="fas fa-bolt"></i>
            Powered by QEC Esports
          </div>
        </div>
      </div>
    </div>
  </body>
</html>