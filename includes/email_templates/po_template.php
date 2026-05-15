<?php
/**
 * resupply - Professional HTML Purchase Order Email Template
 * Mirrors shopping cart layout + rocket animation header
 * Date: May 15, 2026
 */
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; margin:0; padding:20px; background:#f4f4f4;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow:hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        
        <!-- Rocket Header -->
        <div style="background: #2c3e50; color: white; padding: 20px; text-align:center;">
            <h1 style="margin:0; font-size:28px;">🚀 Resupply Rocket</h1>
            <p style="margin:5px 0 0;">Purchase Order #{{ORDER_ID}} – {{ORDER_TYPE}}</p>
        </div>
        
        <div style="padding: 30px;">
            <h2>New Order from {{USER_EMAIL}}</h2>
            
            <table style="width:100%; border-collapse:collapse; margin:20px 0;">
                <tr style="background:#ecf0f1;">
                    <th style="padding:12px; text-align:left;">Item</th>
                    <th style="padding:12px; text-align:right;">Qty</th>
                </tr>
                {{ITEMS}}
            </table>
            
            <p style="text-align:center; margin-top:40px; font-size:18px;">
                Thank you for using Resupply Rocket!<br>
                <span style="font-size:32px;">🚀</span>
            </p>
        </div>
        
        <div style="background:#2c3e50; color:#ecf0f1; padding:15px; text-align:center; font-size:12px;">
            © <?= date('Y') ?> Quarry Tile Shop • Resupply Rocket
        </div>
    </div>
</body>
</html>