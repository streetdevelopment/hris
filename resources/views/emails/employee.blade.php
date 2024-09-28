<table class="body-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
    <tbody>
        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
            <td class="container" width="600" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                <div class="content" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 7px; background-color: #fff; color: #495057; margin: 0; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03);" bgcolor="#fff">
                        <tbody>
                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <div style="display: flex; justify-content: center;">
                                    <img src="{{ asset('assets/images/logos/HRIS.png') }}" alt="" height="38">
                                </div>
                            </tr>
                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td class="content-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                    @if(isset($data['onboarding']))
                                    <table width="100%" cellpadding="0" cellspacing="0" style="padding-top: 20px; padding-bottom: 20px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tbody>
                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <p><span style="font-weight: bold;">Sender:</span> {{$data['sender']}}</p>
                                            </tr>
                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <p>Dear {{$data['recipient']}},</p>

                                                <p>We are pleased to welcome you to <span style="color: #0d6efd;">{{$data['company']}}</span>. Our team is fully committed to ensuring that your onboarding experience is as smooth and seamless as possible. Should you have any questions or require assistance throughout this process, please feel free to contact our dedicated onboarding team.</p>

                                                <p>We look forward to a successful and productive partnership during your time with <span style="color: #0d6efd;">{{$data['company']}}</span>.</p>

                                                <p>To access your account, please visit <a href="http://127.0.0.1:8000/">www.hris.com</a> and log in using the credentials provided below:</p>

                                                <p><strong>Username:</strong> {{$data['username']}}<br><strong>Password:</strong> {{$data['password']}}</p>

                                                <p>For security purposes, we highly recommend that you <span style="color: #ffc107;">change your password</span> upon first login.</p>

                                                <p>Warm regards,</p>
                                                <p>The {{$data['company']}} Team</p>

                                            </tr>
                                            <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="content-block" style="text-align: center;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                                    © 2024 HRIS
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                @else
                                <table width="100%" cellpadding="0" cellspacing="0" style="padding-top: 20px; padding-bottom: 20px; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <tbody>
                                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <p><span style="font-weight: bold;">Sender:</span> {{$data['sender']}}</p>
                                        </tr>
                                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <p>{{$data['message']}}</p>
                                        </tr>
                                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="text-align: center;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                                © 2024 HRIS
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
            </td>
            @endif
        </tr>
    </tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>