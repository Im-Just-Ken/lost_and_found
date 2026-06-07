<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Found Report Approved</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0"
       style="background:#ffffff;border-radius:12px;overflow:hidden;">

    <!-- Header -->
    <tr>
        <td style="background:#16a34a;padding:30px;text-align:center;">
            <h1 style="color:#ffffff;margin:0;">
                Found Report Approved
            </h1>
        </td>
    </tr>

    <!-- Content -->
    <tr>
        <td style="padding:40px;">
            <p>Hello {{ $finder->name }},</p>

            <p>
                Thank you for helping the community.
            </p>

            <p>
                Your report indicating that you found the item below has been
                reviewed and approved by an administrator.
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
                The item owner may now proceed with the recovery process.
            </p>

            <p>
                We appreciate your honesty and effort in helping return lost
                belongings.
            </p>

            <p>
                Thank you,<br>
                Lost & Found Team
            </p>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="background:#f8fafc;padding:20px;text-align:center;
                   color:#64748b;font-size:12px;">
            Community Lost & Found System
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>