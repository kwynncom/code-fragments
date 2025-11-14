package com.kwynn.battery

import android.app.*
import android.content.*
import android.os.*
import android.util.Log
import androidx.core.app.NotificationCompat
import java.net.HttpURLConnection
import java.net.URL

class ChargeService : Service() {

    private val NOTIFICATION_ID = 1
    private val CHANNEL_ID = "charge_channel"

    override fun onCreate() {
        super.onCreate()
        Log.d("ChargeService", "SERVICE IS ALIVE - onCreate()")

        createNotificationChannel()
        val notification = createNotification()
        startForeground(NOTIFICATION_ID, notification)  // MUST be here

        registerChargingReceiver()
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        Log.d("ChargeService", "onStartCommand() called")
        return START_STICKY  // Restart if killed
    }

    private fun registerChargingReceiver() {
        val filter = IntentFilter().apply {
            addAction(Intent.ACTION_POWER_CONNECTED)
            addAction(Intent.ACTION_POWER_DISCONNECTED)
        }
        registerReceiver(chargingReceiver, filter)
        Log.d("ChargeService", "Charging receiver registered")
    }

    private val chargingReceiver = object : BroadcastReceiver() {
        override fun onReceive(context: Context, intent: Intent) {
            val isCharging = intent.action == Intent.ACTION_POWER_CONNECTED
            val status = if (isCharging) "charging on" else "charging off"
            Log.d("ChargeService", "EVENT: $status")
            sendToServer(status)
        }
    }

    private fun sendToServer(status: String) {
        Thread {
            try {
                val url = URL("https://kwynn.com/t/25/11/android.php")  // TEST FIRST
                val conn = url.openConnection() as HttpURLConnection
                conn.requestMethod = "POST"
                conn.setRequestProperty("Content-Type", "application/json")
                conn.doOutput = true

                val json = "{\"status\":\"$status\"}"
                conn.outputStream.use { it.write(json.toByteArray()) }

                val code = conn.responseCode
                Log.d("ChargeService", "HTTP POST sent: $code")
            } catch (e: Exception) {
                Log.e("ChargeService", "HTTP failed", e)
            }
        }.start()
    }

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channel = NotificationChannel(
                CHANNEL_ID,
                "Charge Monitoring",
                NotificationManager.IMPORTANCE_LOW
            )
            getSystemService(NotificationManager::class.java)
                .createNotificationChannel(channel)
        }
    }

    private fun createNotification(): Notification {
        return NotificationCompat.Builder(this, CHANNEL_ID)
            .setContentTitle("Charge Reporter")
            .setContentText("Monitoring charging events...")
            .setSmallIcon(android.R.drawable.ic_dialog_info)
            .setPriority(NotificationCompat.PRIORITY_LOW)
            .setOngoing(true)
            .build()
    }

    override fun onDestroy() {
        unregisterReceiver(chargingReceiver)
        Log.d("ChargeService", "Service destroyed")
        super.onDestroy()
    }

    override fun onBind(intent: Intent?): IBinder? = null
}