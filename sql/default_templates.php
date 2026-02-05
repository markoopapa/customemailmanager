<?php
/**
 * Alapértelmezett sablonok listája a telepítéshez
 * Target Email beállításokkal (order_conf, new_order, all)
 */

$default_templates = array(
    // 1. MODERN CLEAN -> Ezt kapja az Admin (new_order)
    array(
        'name' => 'Admin Alert (Modern Clean)',
        'target_email' => 'new_order', 
        'content_html' => '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @import url(\'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap\');
    body { background-color: #f4f7f9; font-family: \'Inter\', Arial, sans-serif; margin: 0; padding: 0; }
    .main-card { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; }
    .header { padding: 30px; text-align: center; border-bottom: 1px solid #eee; }
    .content { padding: 30px; }
    h1 { color: #1a1c21; font-size: 22px; font-weight: 700; margin:0; }
    .info-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin: 20px 0; font-size: 14px; }
  </style>
</head>
<body>
  <div class="main-card">
    <div class="header">
      <img src="{shop_logo}" width="120">
      <h1 style="margin-top:10px;">Új rendelés érkezett! 📦</h1>
    </div>
    <div class="content">
      <div class="info-box">
        <strong>Rendelés ID:</strong> {order_name}<br>
        <strong>Vevő:</strong> {firstname} {lastname} ({email})<br>
        <strong>Fizetés:</strong> {payment}<br>
        <strong>Szállító:</strong> {carrier}
      </div>
      <p>A rendelés részletei:</p>
      <div style="margin-top:10px;">{items}</div>
      <br>
      <p style="font-size:12px; color:#888;">Ez egy automatikus admin értesítő.</p>
    </div>
  </div>
</body>
</html>'
    ),

    // 2. KARÁCSONYI -> Ezt kapja a Vevő (order_conf)
    array(
        'name' => 'Christmas Customer Greeting 🎄',
        'target_email' => 'order_conf',
        'content_html' => '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { background-color: #fdf2f2; font-family: Georgia, serif; margin: 0; padding: 0; }
    .main-card { max-width: 600px; margin: 30px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(153, 27, 27, 0.1); border-top: 10px solid #991b1b; }
    .header { padding: 40px; text-align: center; }
    .content { padding: 0 40px 40px 40px; }
    h1 { color: #991b1b; font-size: 32px; margin: 0; }
    .festive-icon { font-size: 40px; margin-bottom: 10px; display: block; }
    p { color: #4b5563; font-size: 16px; line-height: 1.8; }
    .info-box { background-color: #fff1f2; border-radius: 12px; padding: 20px; border: 1px dashed #fecdd3; margin: 20px 0; }
    .footer { text-align: center; padding: 30px; color: #9ca3af; font-size: 13px; background-color: #f9fafb; }
  </style>
</head>
<body>
  <div class="main-card">
    <div class="header">
      <span class="festive-icon">🎄✨</span>
      <h1>Crăciun Fericit!</h1>
    </div>
    <div class="content">
      <p>Mulțumim pentru comandă! Comanda ta pe <strong>{shop_name}</strong> a fost înregistrată cu succes.</p>
      <div class="info-box">
        <strong>Cod Comandă:</strong> {order_name}<br>
        <strong>Data:</strong> {date}
      </div>
      <div style="margin-top: 30px;">
        <strong>Produsele tale:</strong>
        <div style="margin-top: 10px;">{items}</div>
      </div>
      <div style="margin-top: 20px; padding: 15px; background: #fefce8; border-left: 4px solid #facc15; font-style: italic;">
        Mesaj: {message}
      </div>
    </div>
    <div class="footer">
      Vă dorim sărbători fericite! <br> <strong>{shop_name}</strong>
    </div>
  </div>
</body>
</html>'
    ),

    // 3. BLACK FRIDAY -> Tartalék (all)
    array(
        'name' => 'Black Friday Special (Dark Mode)',
        'target_email' => 'all',
        'content_html' => '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { background-color: #000000; font-family: Arial, sans-serif; margin: 0; padding: 0; }
    .main-card { max-width: 600px; margin: 20px auto; background-color: #111111; border: 1px solid #333; border-radius: 12px; overflow: hidden; }
    .header { background: linear-gradient(135deg, #000000, #222222); padding: 40px; text-align: center; border-bottom: 2px solid #fbbf24; }
    .content { padding: 40px; }
    h1 { color: #fbbf24; font-size: 28px; font-weight: 800; text-transform: uppercase; letter-spacing: 3px; margin: 0; }
    p { color: #aaaaaa; font-size: 16px; }
    .items-table { border-top: 1px solid #333; margin-top: 20px; color: #fff; }
  </style>
</head>
<body>
  <div class="main-card">
    <div class="header">
      <img src="{shop_logo}" width="150">
      <h1>BLACK FRIDAY ORDER</h1>
    </div>
    <div class="content">
      <p>O nouă comandă a fost plasată pe <strong>{shop_name}</strong>.</p>
      <div style="background-color: #1a1a1a; padding: 20px; border: 1px solid #333; border-radius: 8px; color: #fff;">
        <strong>ID:</strong> {order_name} <br>
        <strong>Total:</strong> {total_paid}
      </div>
      <div class="items-table">
        <br>
        {items}
      </div>
    </div>
  </div>
</body>
</html>'
    )
);
