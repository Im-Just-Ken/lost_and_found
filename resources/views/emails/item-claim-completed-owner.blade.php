<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>Item Successfully Claimed</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0"
       style="background:#ffffff;border-radius:12px;overflow:hidden;">

```
<tr>
    <td style="background:#16a34a;padding:30px;text-align:center;">
        <h1 style="color:#ffffff;margin:0;">
            Claim Completed
        </h1>
    </td>
</tr>

<tr>
    <td style="padding:40px;">
        <p>Hello {{ $owner->name }},</p>

        <p>
            We are pleased to inform you that your item has been successfully claimed and the recovery process has been completed.
        </p>

        <table width="100%" style="background:#f8fafc;border-radius:8px;padding:20px;">
            <tr>
                <td>
                    <strong>Item:</strong><br>
                    {{ $item->title }}
                </td>
            </tr>

            <tr>
                <td style="padding-top:12px;">
                    <strong>Status:</strong><br>
                    Claimed
                </td>
            </tr>
        </table>

        <p style="margin-top:25px;">
            Thank you for using the Lost &amp; Found System.
        </p>

        <p>
            We are glad that your item has been safely returned.
        </p>

        <p>
            Regards,<br>
            Lost &amp; Found Team
        </p>
    </td>
</tr>

<tr>
    <td style="background:#f8fafc;padding:20px;text-align:center;color:#64748b;font-size:12px;">
        Community Lost &amp; Found System
    </td>
</tr>
```

</table>

</td>
</tr>
</table>

</body>
</html>
