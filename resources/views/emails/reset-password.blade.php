<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f7; color: #51545e; margin: 0; padding: 0; width: 100%; -webkit-text-size-adjust: none;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f4f4f7; margin: 0; padding: 25px 0; width: 100%;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 570px; background-color: #ffffff; border: 1px solid #e8e8e8; border-radius: 8px;">
                    <tr>
                        <td style="padding: 45px 0; text-align: center;">
                            <a href="{{ url('/') }}" style="font-size: 24px; font-weight: bold; color: #333; text-decoration: none;">
                                {{ config('app.name') }}
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 45px 45px 45px;">
                            <h1 style="color: #333333; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">Halo!</h1>
                            <p style="font-size: 16px; line-height: 1.5; margin-top: 0; text-align: left;">
                                Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi (reset password) untuk akun Anda.
                            </p>

                                {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}


                            <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 30px auto; text-align: center; width: 100%;">
                                <tr>
                                    <td align="center">
                                       <a href="{{ url('api/reset-password/'.$token.'/'.$email) }}"
                                        style="background-color: #2d3748; border: 10px solid #2d3748; border-radius: 5px; color: #ffffff; display: inline-block; font-size: 16px; font-weight: bold; text-decoration: none; padding: 5px 15px;">
                                        Atur Ulang Kata Sandi
                                    </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 16px; line-height: 1.5; margin-top: 0; text-align: left;">
                                Link reset password ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak merasa meminta pengaturan ulang kata sandi, abaikan saja email ini.
                            </p>

                            <p style="font-size: 16px; line-height: 1.5; margin-top: 25px; text-align: left;">
                                Salam,<br>Tim {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>
                </table>

                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 570px; text-align: center;">
                    <tr>
                        <td style="padding: 30px; text-align: center;">
                            <p style="font-size: 12px; color: #b0adc5; text-align: center;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
