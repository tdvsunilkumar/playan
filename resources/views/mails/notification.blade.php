<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
  <style>
    table, td, div, h1, p {font-family: Arial, sans-serif;}
  </style>
</head>
<body style="margin:0;padding:0;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
      <td align="center" style="padding:0;">
        <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
          <tr>
            <td align="center" style="padding:20px 0 20px 0;background:#fff;">
              <img src="{{ $message->embed('assets/storage/uploads/logo/new-logo.png') }}" alt="" width="120" style="height:auto;display:block;" />
            </td>
          </tr>
          <tr>
            <td style="padding:15px 30px 0px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 20px 0;color:#153643; text-align: center">
                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi {{ $nickname }},</h1>
                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">{{ $messages }}</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding: 0px 20px 35px 20px">
              <table role="presentation2" style="width:100%;border-collapse:collapse;border:1px solid #ddd;border-spacing:0; font-size: 1rem">
                  <tr>     
                    <td style="padding:7.5px 15px;color:#153643; text-align: left; width: 29%;">
                      <strong>Control No.:</strong>
                    </td>
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      {{ $details->control_no }}
                    </td>  
                  </tr>
                  <tr style="background:#f4f5f4">     
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      <strong>Requested Date:</strong>
                    </td>
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      {{ date('d-M-Y', strtotime($details->requested_date)) }}
                    </td>  
                  </tr>
                  <tr>     
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      <strong>Requested By:</strong>
                    </td>
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      {{ ucwords($details->employee->fullname) }}
                    </td>  
                  </tr>
                  <tr style="background:#f4f5f4">     
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      <strong>Department/Division:</strong>
                    </td>
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      {{ $details->department->name . ' [' . $details->division->code .']' }}
                    </td>  
                  </tr>                
                  <tr>     
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      <strong>Total Requested Amount:</strong>
                    </td>
                    <td style="padding:7.5px 15px;color:#153643; text-align: left">
                      {{ '₱' . number_format(floor(($details->total_amount*100))/100, 2)  }}
                    </td>  
                  </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0px 30px 0px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 40px 0;color:#153643; text-align: center">
                    <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="{{ $approve }}" style="text-decoration:none;background-color:#96ce73; color:#fff; padding: 10px; margin-right: 5px; min-width: 100px; display: inline-block;">APPROVE</a><a href="{{ $disapprove }}" style="text-decoration:none;background-color:#ee4c50; color:#fff; padding: 10px; margin-left: 5px; min-width: 100px; display: inline-block;">DISAPPROVE</a></p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0px 30px 0px 30px;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                  <td style="padding:0 0 40px 0;color:#153643; text-align: center">
                    <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                      Please do not reply to this message. This is a system-generated email sent to <a href="mailto:{{ $email }}" target="_blank">{{ $email }}</a>.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:30px;background:#96ce73;">
              <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                <tr>
                  <td style="padding:0;width:50%;" align="left">
                    <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                      &reg; Palayan, Dyn Edge 2023<br/>
                    </p>
                  </td>
                  <td style="padding:0;width:50%;" align="right">
                    <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                      <tr>
                        <td style="padding:0 0 0 10px;width:38px;">
                          <a href="http://www.twitter.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/tw_1.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                        </td>
                        <td style="padding:0 0 0 10px;width:38px;">
                          <a href="http://www.facebook.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/fb_1.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>