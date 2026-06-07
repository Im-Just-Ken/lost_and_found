<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>Found Report Rejected</title>
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
    <td style="background:#dc2626;padding:30px;text-align:center;">
        <h1 style="color:#ffffff;margin:0;">
            Found Report Rejected
        </h1>
    </td>
</tr>

<!-- Content -->
<tr>
    <td style="padding:40px;">
        <p>Hello {{ $finder->name }},</p>

        <p>
            Thank you for taking the time to help the community by reporting a found item.
        </p>

        <p>
            After review, your found report for the item below was not approved by an administrator at this time.
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
        </table>

        <p style="margin-top:25px;">
            This does not prevent you from reporting the item again in the future if you are still in possession of it or have additional information that may help verify ownership.
        </p>

        <p>
            We sincerely appreciate your effort and willingness to help reunite lost belongings with their owners.
        </p>

        <p>
            Thank you,<br>
            Lost &amp; Found Team
        </p>
    </td>
</tr>

<!-- Footer -->
<tr>
    <td style="background:#f8fafc;padding:20px;text-align:center;
               color:#64748b;font-size:12px;">
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
