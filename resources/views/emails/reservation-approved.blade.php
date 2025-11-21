<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rezerwacja zatwierdzona</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">🎉 Rezerwacja zatwierdzona!</h1>
        </div>

        <!-- Content -->
        <div style="padding: 30px;">
            <p style="font-size: 16px; color: #333333;">Cześć <strong>{{ $user_name }}</strong>,</p>
            
            <p style="font-size: 16px; color: #333333; line-height: 1.6;">
                Twoja rezerwacja została <strong style="color: #10b981;">zatwierdzona</strong> przez administratora! 
            </p>

            <!-- Reservation Details -->
            <div style="background-color: #f8fafc; border-left: 4px solid #667eea; padding: 20px; margin: 20px 0; border-radius: 5px;">
                <h3 style="margin-top: 0; color: #667eea;">Szczegóły rezerwacji #{{ $reservation_id }}</h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Samochód:</td>
                        <td style="padding: 8px 0; color: #1e293b; font-weight: bold; text-align: right;">{{ $car_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Data odbioru:</td>
                        <td style="padding: 8px 0; color: #1e293b; font-weight: bold; text-align: right;">{{ $start_date }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #64748b; font-size: 14px;">Data zwrotu:</td>
                        <td style="padding: 8px 0; color: #1e293b; font-weight: bold; text-align: right;">{{ $end_date }}</td>
                    </tr>
                    <tr style="border-top: 2px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #1e293b; font-size: 16px; font-weight: bold;">Całkowity koszt:</td>
                        <td style="padding: 12px 0; color: #667eea; font-size: 20px; font-weight: bold; text-align: right;">{{ $total_price }} zł</td>
                    </tr>
                </table>
            </div>

            <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
                Prosimy o stawienie się w naszej wypożyczalni o wyznaczonej godzinie z dokumentem tożsamości i prawem jazdy.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="http://localhost:8080/dashboard/reservations" 
                   style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; font-size: 16px;">
                    Zobacz szczegóły rezerwacji
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #64748b; font-size: 12px; margin: 0;">
                © 2025 Car Rental System. Wszystkie prawa zastrzeżone.
            </p>
        </div>
    </div>
</body>
</html>
