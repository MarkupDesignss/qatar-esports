<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Request</title>

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
      .contact-card {
        width: 100%;
        max-width: 560px;
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
        gap: 16px;
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

      .header-text {
        flex: 1;
      }

      .header-text h2 {
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 3px;
      }

      .subhead {
        color: rgba(255, 255, 255, 0.92);
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .subhead i {
        color: #ffffff;
        font-size: 11px;
      }

      /* BODY */
      .card-body {
        padding: 24px 22px 20px;
      }

      /* DETAIL GRID */
      .detail-grid {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-bottom: 18px;
      }

      .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: linear-gradient(135deg, #f0fdff 0%, #ecfeff 100%);
        border: 1px solid #dff4fb;
        border-radius: 18px;
        padding: 14px 16px;
      }

      .detail-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #ddf9fc;
        color: #06b6d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
      }

      .detail-content {
        flex: 1;
      }

      .detail-label {
        font-size: 11px;
        font-weight: 700;
        color: #5b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
      }

      .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.6;
        word-break: break-word;
      }

      .detail-value a {
        color: #10b7c7;
        text-decoration: none;
      }

      /* MESSAGE */
      .message-block {
        background: #f7fdff;
        border-left: 4px solid #10b7c7;
        border-radius: 18px;
        padding: 18px;
        border: 1px solid #dff4fb;
        margin-bottom: 18px;
      }

      .message-label {
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

      .message-label i {
        color: #06b6d4;
      }

      .message-text {
        font-size: 14px;
        line-height: 1.8;
        color: #44616d;
        white-space: pre-wrap;
        word-break: break-word;
      }

      /* FOOTER */
      .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 14px;
      }

      .footer-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f3fdff;
        border: 1px solid #dff4fb;
        border-radius: 14px;
        padding: 12px 14px;
        color: #5d7380;
        font-size: 13px;
      }

      .footer-meta i {
        color: #06b6d4;
      }

      .btn-reply {
        background: linear-gradient(135deg, #10b7c7 0%, #37d5e5 100%);
        color: #ffffff;
        text-decoration: none;
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s ease;
      }

      .btn-reply:hover {
        opacity: 0.92;
      }

      /* BRAND */
      .brand {
        border-top: 1px solid #e7f4f8;
        padding-top: 14px;
        text-align: center;
        font-size: 11px;
        color: #8aa4af;
      }

      .brand i {
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

        .logo-wrapper {
          width: 48px;
          height: 48px;
        }

        .logo-wrapper img {
          width: 32px;
          height: 32px;
        }

        .header-text h2 {
          font-size: 18px;
        }

        .subhead {
          font-size: 11px;
        }

        .detail-item {
          padding: 12px;
        }

        .message-block {
          padding: 16px;
        }

        .card-footer {
          flex-direction: column;
          align-items: stretch;
        }

        .btn-reply {
          justify-content: center;
        }
      }
    </style>
  </head>

  <body>
    <div class="contact-card">
      <!-- HEADER -->
      <div class="card-header">
        <div class="logo-wrapper">
          <!-- QEC logo — bilkul bech mein -->
          <img src="{{ asset('mail_logo/logo.png') }}" alt="QEC" />
        </div>

        <div class="header-text">
          <h2>Contact Request</h2>

          <div class="subhead">
            <i class="fas fa-envelope-open-text"></i>
            <span>from website contact form</span>
          </div>
        </div>
      </div>

      <!-- BODY -->
      <div class="card-body">
        <!-- DETAILS -->
        <div class="detail-grid">
          <!-- NAME -->
          <div class="detail-item">
            <div class="detail-icon">
              <i class="fas fa-user"></i>
            </div>

            <div class="detail-content">
              <div class="detail-label">Full Name</div>

              <div class="detail-value">
                {{ $data['full_name'] ?? 'John Doe' }}
              </div>
            </div>
          </div>

          <!-- EMAIL -->
          <div class="detail-item">
            <div class="detail-icon">
              <i class="fas fa-envelope"></i>
            </div>

            <div class="detail-content">
              <div class="detail-label">Email Address</div>

              <div class="detail-value">
                <a href="mailto:{{ $data['email'] ?? 'example@domain.com' }}">
                  {{ $data['email'] ?? 'example@domain.com' }}
                </a>
              </div>
            </div>
          </div>

          <!-- SUBJECT -->
          <div class="detail-item">
            <div class="detail-icon">
              <i class="fas fa-tag"></i>
            </div>

            <div class="detail-content">
              <div class="detail-label">Subject</div>

              <div class="detail-value">
                {{ $data['subject'] ?? 'General Inquiry' }}
              </div>
            </div>
          </div>
        </div>

        <!-- MESSAGE -->
        <div class="message-block">
          <div class="message-label">
            <i class="fas fa-comment-dots"></i>
            Message
          </div>

          <div class="message-text">
            {{ $data['message'] ?? 'Hello, I would like to know more about your
            services. Looking forward to your response.' }}
          </div>
        </div>

        <!-- FOOTER -->
        <div class="card-footer">
          <div class="footer-meta">
            <i class="fas fa-clock"></i>
            <span>Received: {{ now()->format('M d, Y · H:i') }}</span>
          </div>

          <a
            href="mailto:{{ $data['email'] ?? 'reply@domain.com' }}"
            class="btn-reply"
          >
            <i class="fas fa-reply"></i>
            Reply Now
          </a>
        </div>

        <!-- BRAND -->
        <div class="brand">
          <i class="fas fa-bolt"></i>
          Powered by QEC Esports
        </div>
      </div>
    </div>
  </body>
</html>