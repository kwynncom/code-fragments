<?php

// does work; brought to you by Grok 3 beta

function getSSLCertExpiration($domain) {
    // Ensure the domain has the right protocol
    $url = 'ssl://' . str_replace(['http://', 'https://'], '', $domain);
    
    // Create a stream context with a timeout
    $context = stream_context_create([
        'ssl' => [
            'capture_peer_cert' => true,
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);
    
    // Attempt to connect and get certificate
    $client = @stream_socket_client(
        $url . ':443',
        $errno,
        $errstr,
        30,
        STREAM_CLIENT_CONNECT,
        $context
    );
    
    if (!$client) {
        return 'Could not connect to $domain: $errstr ($errno)';
    }
    
    // Get the certificate
    $params = stream_context_get_params($client);
    $cert = $params['options']['ssl']['peer_certificate'];
    
    // Parse certificate details
    $certInfo = openssl_x509_parse($cert);
    
    if ($certInfo === false) {
        return 'Could not parse certificate';
    }
    
    // Get expiration date
    $expirationTimestamp = $certInfo['validTo_time_t'];
    $expirationDate = date('Y-m-d H:i:s', $expirationTimestamp);
    
    // Clean up
    fclose($client);
    
    return [
        'expiration_date' => $expirationDate,
        'days_left' => floor(($expirationTimestamp - time()) / (60 * 60 * 24))
    ];
}

// Example usage
$domain = 'kwynn.com';
$result = getSSLCertExpiration($domain);

if (is_array($result)) {
    echo 'Certificate for ' . $domain . ' expires on: ' . $result['expiration_date'] . "\n";
    echo 'Days remaining: ' . $result['days_left'] . "\n";
} else {
    echo $result;
}
