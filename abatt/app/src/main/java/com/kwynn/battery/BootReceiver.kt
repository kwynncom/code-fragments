package com.kwynn.battery

import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import androidx.core.content.ContextCompat  // ← THIS WAS MISSING

class BootReceiver : BroadcastReceiver() {
    override fun onReceive(context: Context, intent: Intent) {
        if (Intent.ACTION_BOOT_COMPLETED == intent.action) {
            val serviceIntent = Intent(context, ChargeService::class.java)
            ContextCompat.startForegroundService(context, serviceIntent)
        }
    }
}