<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0; padding:0; background:#f4f6f8; font-family: Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px;">
<tr>
<td align="center">

    <!-- CARD -->
    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05);">

        <!-- HEADER -->
        <tr>
            <td style="background:#1f2937; padding:20px; text-align:center;">
                <img src="https://misc.tradesmartzm.com/logo.png" alt="Logo" style="height:50px; margin-bottom:10px;">
                <h2 style="color:#ffffff; margin:0; font-size:18px;">Technical Evaluation Submission</h2>
            </td>
        </tr>

        <!-- BODY -->
        <tr>
            <td style="padding:25px;">

                <p style="margin:0 0 15px; color:#374151;">
                    A candidate has completed the HR Technical Evaluation.
                </p>

                <!-- INFO BOX -->
                <table width="100%" style="border:1px solid #e5e7eb; border-radius:8px; padding:15px; margin-bottom:20px;">
                    <tr>
                        <td style="padding:5px 0;"><strong>Candidate Name:</strong></td>
                        <td style="padding:5px 0;">{{ $candidate }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 0;"><strong>Date Submitted:</strong></td>
                        <td style="padding:5px 0;">{{ date('d M Y H:i') }}</td>
                    </tr>
                </table>

                <p style="color:#374151;">
                    The candidate's responses are attached as a PDF for review, printing, and marking.
                </p>

                <!-- CTA -->
                <div style="margin-top:20px;">
                    <span style="display:inline-block; padding:10px 15px; background:#2563eb; color:#ffffff; border-radius:6px; font-size:13px;">
                        See Attachment
                    </span>
                </div>

            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td style="background:#f9fafb; padding:15px; text-align:center; font-size:12px; color:#6b7280;">
                This is an automated message from the Quiz Portal.<br>
                © {{ date('Y') }} Your Company. All rights reserved.
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>