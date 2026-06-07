<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>Your Item Has Been Found</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0"
       style="background:#ffffff;border-radius:12px;overflow:hidden;">

```
<!-- Header -->
<tr>
    <td style="background:#16a34a;padding:30px;text-align:center;">
        <h1 style="color:#ffffff;margin:0;">
            Your Item Has Been Found
        </h1>
    </td>
</tr>

<!-- Content -->
<tr>
    <td style="padding:40px;">
        <p>Hello {{ $owner->name }},</p>

        <p>
            Good news! A community member reported finding an item that matches your lost item report, and the report has been verified by an administrator.
        </p>

        <table width="100%"
               style="background:#f8fafc;border-radius:8px;padding:20px;">
            <tr>
                <td>
                    <strong>Item:</strong><br>
                    {{ $item->title }}
                </td>
            </tr>

            <tr>
                <td style="padding-top:12px;">
                    <strong>Location:</strong><br>
                    {{ $item->location_text }}
                </td>
            </tr>

            <tr>
                <td style="padding-top:12px;">
                    <strong>Status:</strong><br>
                    Found and Verified
                </td>
            </tr>
        </table>

<p style="margin-top:25px;">
    Please proceed to the Human Resources (HR) Office to claim your item.
</p>

<p>
    When claiming the item, kindly bring a valid company identification card or any supporting proof of ownership for verification purposes.
</p>

<p>
    If you have any questions regarding the claim process, please contact the Human Resources (HR) Office for assistance.
</p>

<p>
    We hope this helps reunite you with your lost property as soon as possible.
</p>

        <p>
            Thank you,<br>
            Lost &amp; Found Team
        </p>
    </td>
</tr>

<!-- Footer -->
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
