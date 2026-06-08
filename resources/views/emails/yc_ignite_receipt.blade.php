<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>YC Ignite 2025 - Registration Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 0;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:20px;">
        <tr>
            <td align="center">
                <!-- Main Card -->
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #ff6600, #ff9900); padding:20px; text-align:center; color:#fff;">
                            <h1 style="margin:0; font-size:24px;">YC Ignite 2025</h1>
                            <p style="margin:0; font-size:14px;">Official Registration Receipt</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <h2 style="margin-top:0; font-size:20px; color:#ff6600;">
                                🎉 Thank you for registering, {{ $data->first_name }} {{ $data->last_name }}!
                            </h2>
                            <p style="font-size:15px; color:#555; line-height:1.6;">
                                We’re excited to have you join <strong>YC Ignite 2025</strong>.  
                                Below are your registration details:
                            </p>

                            <!-- Receipt Table -->
                            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse; margin-top:20px;">
                                <tr style="background:#fafafa;">
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Event ID</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->event_id }}</td>
                                </tr>
                                <tr>
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Full Name</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->first_name }} {{ $data->middle_name }} {{ $data->last_name }}</td>
                                </tr>
                                <tr style="background:#fafafa;">
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Email</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->email }}</td>
                                </tr>
                                <tr>
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Mobile</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->mobile }}</td>
                                </tr>
                                <tr style="background:#fafafa;">
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Date of Birth</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->dob }}</td>
                                </tr>
                                <tr>
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">School</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->school }}</td>
                                </tr>
                                <tr style="background:#fafafa;">
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Grade</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->grade }}</td>
                                </tr>
                                <tr>
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Sport Event</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $data->sport_event }}</td>
                                </tr>

                                @php
                                    $schoolType = DB::table('school_types')->where('id', $data->age_category)->first();
                                @endphp
                                <tr style="background:#fafafa;">
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">School Type</th>
                                    <td style="border:1px solid #ddd; padding:10px;">{{ $schoolType->school_type ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th align="left" style="border:1px solid #ddd; padding:10px; color:#555;">Price</th>
                                    <td style="border:1px solid #ddd; padding:10px; font-weight:bold; color:#ff6600;">
                                        ₹{{ $schoolType->price ?? '0' }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:25px; font-size:15px; line-height:1.6; color:#333;">
                                ✅ Please keep this receipt safe.  
                                We look forward to welcoming you at <strong>YC Ignite 2025</strong>!
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f4f6f8; text-align:center; padding:20px; font-size:13px; color:#777;">
                            Regards,<br>
                            <strong>Team Youth Central</strong><br>
                            <em>Igniting the Future of Youth Sports</em>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
