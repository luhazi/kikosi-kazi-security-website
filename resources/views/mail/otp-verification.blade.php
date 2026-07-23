<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email — Kikosi Kazi</title>
</head>
<body style="margin:0;padding:0;background:#f0eef8;font-family:'Inter',Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0eef8;padding:40px 0">
<tr><td align="center">
<table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 32px rgba(59,31,110,.12)">

  {{-- Header --}}
  <tr>
    <td style="background:linear-gradient(135deg,#0F1E43 0%,#3B1F6E 100%);padding:36px 40px;text-align:center;border-bottom:4px solid #D4AF37">
      <div style="font-size:28px;font-weight:800;color:#ffffff;letter-spacing:-.01em">Kikosi Kazi</div>
      <div style="font-size:11px;color:rgba(212,175,55,.8);letter-spacing:2px;margin-top:4px">COMPANY LTD</div>
    </td>
  </tr>

  {{-- Body --}}
  <tr>
    <td style="padding:40px 40px 32px">
      <p style="font-size:16px;color:#1A233A;margin:0 0 8px">Hello, <strong>{{ $name }}</strong></p>
      <p style="font-size:15px;color:#4B5563;margin:0 0 28px;line-height:1.7">
        Thank you for registering with <strong>Kikosi Kazi</strong>. Please use the verification code below to confirm your email address and activate your account.
      </p>

      {{-- OTP Box --}}
      <div style="background:#f8f6ff;border:2px dashed #D4AF37;border-radius:12px;padding:28px;text-align:center;margin-bottom:28px">
        <div style="font-size:11px;font-weight:700;letter-spacing:2px;color:#6B7280;margin-bottom:12px;text-transform:uppercase">Your Verification Code</div>
        <div style="font-size:48px;font-weight:800;letter-spacing:12px;color:#0F1E43;font-family:monospace">{{ $code }}</div>
        <div style="font-size:12px;color:#9CA3AF;margin-top:12px">This code expires in <strong>15 minutes</strong></div>
      </div>

      <p style="font-size:14px;color:#6B7280;line-height:1.7;margin:0 0 8px">
        Enter this code on the verification page to complete your registration. If you did not create an account, you can safely ignore this email.
      </p>
    </td>
  </tr>

  {{-- Footer --}}
  <tr>
    <td style="background:#F8F9FC;padding:20px 40px;border-top:1px solid #EAEEf5;text-align:center">
      <p style="font-size:12px;color:#9CA3AF;margin:0">&copy; {{ date('Y') }} Kikosi Kazi Security &bull; Dar es Salaam, Tanzania</p>
      <p style="font-size:12px;color:#9CA3AF;margin:6px 0 0">TSIA Registered &bull; PSCGP Compliant</p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
