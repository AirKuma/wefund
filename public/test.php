<?php
$url = 'https://graph.facebook.com/v2.6/980383791993468/feed?';
$myvars = 'access_token=EAALbZBUdZCZCMYBAEvZC0j1Oczb4hdBTmox6B8Pt6UsSeZCYViCzHv1G46hcDspX86glZCWyQtcdCBYYwBwbPIAMW0ZBAr2KmMm2YlUHoY44dYx5ZATVx0ehU2O19eh6NemRfx9pKQv20dwJhEKI74jii9xzOtBj8YIflVadBnL9LgZDZD&message=test';
//echo $url;
//echo $myvars;
$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

echo $response = curl_exec( $ch );
?>
