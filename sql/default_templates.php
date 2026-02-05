<?php
/**
 * Alapértelmezett sablonok listája a telepítéshez
 */

$default_templates = array(
    array(
        'name' => 'Modern Clean (Romanian)',
        'content_html' => '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    @import url(\'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap\');
    body { background-color: #f4f7f9; font-family: \'Inter\', Arial, sans-serif; margin: 0; padding: 0; }
    .main-card { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; }
    .header { padding: 40px; text-align: center; }
    .content { padding: 0 40px 40px 40px; }
    h1 { color: #1a1c21; font-size: 24px; font-weight: 700; }
    .info-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
  </style>
</head>
<body>
  <div class="main-card">
    <div class="header"><img src="{shop_logo}" width="150"></div>
    <div class="content">
      <h1>Felicitări! 🎉</h1>
      <p>O comandă nouă a fost plasată pe <strong>{shop_name}</strong>.</p>
      <div class="info-box">
        <strong>Cod:</strong> {order_name}<br>
        <strong>Data:</strong> {date}<br>
        <strong>Plată:</strong> {payment}
      </div>
      <div style="margin-top:20px;">{items}</div>
    </div>
  </div>
</body>
</html>'
    ),
    array(
        'name' => 'Black Friday Special',
        'content_html' => '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { background-color: #000; font-family: Arial, sans-serif; margin: 0; padding: 0; }
    .main-card { max-width: 600px; margin: 20px auto; background-color: #111; border: 1px solid #333; border-radius: 12px; overflow: hidden; }
    .header { background: #000; padding: 40px; text-align: center; border-bottom: 2px solid #fbbf24; }
    h1 { color: #fbbf24; text-transform: uppercase; letter-spacing: 2px; }
    p { color: #ccc; }
  </style>
</head>
<body>
  <div class="main-card">
    <div class="header"><h1>BLACK FRIDAY ORDER</h1></div>
    <div style="padding:40px;">
      <p>O nouă comandă specială de Black Friday pe <strong>{shop_name}</strong>.</p>
      <div style="background:#1a1a1a; padding:20px; border-radius:8px; color:#fff; border:1px solid #333;">
        <strong>ID:</strong> {order_name}
      </div>
      <div style="margin-top:20px; color:#fff;">{items}</div>
    </div>
  </div>
</body>
</html>'
    ),
    array(
        'name' => 'Christmas Greeting 🎄',
        'content_html' => '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { background-color: #fdf2f2; font-family: Georgia, serif; }
    .main-card { max-width: 600px; margin: 30px auto; background-color: #fff; border-top: 10px solid #dc2626; border-radius: 16px; overflow: hidden; }
    h1 { color: #dc2626; text-align: center; }
  </style>
</head>
<body>
  <div class="main-card">
    <div style="padding:40px;">
      <h1>Crăciun Fericit! 🎅</h1>
      <p>O nouă bucurie a fost comandată pe <strong>{shop_name}</strong>.</p>
      <div style="background:#fff1f2; padding:20px; border-radius:12px; border:1px dashed #fecdd3;">
        <strong>Cod Comandă:</strong> {order_name}
      </div>
      <div style="margin-top:20px;">{items}</div>
    </div>
  </div>
</body>
</html>'
    )
);
