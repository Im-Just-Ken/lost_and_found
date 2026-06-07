<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Lost & Found Notification</title>
</head>

<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center" style="padding:40px 20px;">

<table width="650" cellpadding="0" cellspacing="0"
       style="background:#ffffff;border-radius:12px;overflow:hidden;">

    <tr>
        <td
            align="center"
            style="background:#0f172a;padding:30px;"
        >

            <img
                src="{{ asset('assets/co_logo.png') }}"
                width="90"
                alt="Logo"
                style="display:block;margin-bottom:15px;"
            >

            <h1
                style="margin:0;color:white;font-size:24px;"
            >
                Lost & Found System
            </h1>

        </td>
    </tr>

    <tr>
        <td style="padding:35px;">

            <h2 style="margin-top:0;">
                Item Reported As Found
            </h2>

            <p>
                A community member has reported that a lost item
                may have been found and is awaiting verification.
            </p>

            <table
                width="100%"
                cellpadding="8"
                cellspacing="0"
                style="margin-top:20px;background:#f8fafc;border-radius:8px;"
            >
                <tr>
                    <td><strong>Item</strong></td>
                    <td>{{ $item->title }}</td>
                </tr>

                <tr>
                    <td><strong>Owner</strong></td>
                    <td>{{ $item->user->name }}</td>
                </tr>

                <tr>
                    <td><strong>Finder</strong></td>
                    <td>{{ $finder->name }}</td>
                </tr>

                <tr>
                    <td><strong>Finder Email</strong></td>
                    <td>{{ $finder->email }}</td>
                </tr>

                <tr>
                    <td><strong>Location</strong></td>
                    <td>{{ $item->location_text }}</td>
                </tr>
            </table>

            <p style="margin-top:25px;">
                Please review this report through the admin panel.
            </p>

        </td>
    </tr>

    <tr>
        <td
            align="center"
            style="padding:20px;background:#f8fafc;color:#64748b;font-size:12px;"
        >
            Lost & Found Management System
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>