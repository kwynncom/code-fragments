package com.kwynn.battery

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import android.util.Log
import android.os.Build

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        Log.d("MainActivity", "LAUNCHER ACTIVITY STARTED")

        // Start service
        val intent = Intent(this, ChargeService::class.java)
        startForegroundService(intent)
        finish()  // Close immediately
    }
}