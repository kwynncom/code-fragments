import android.app.Notification
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.Service
import android.content.BroadcastReceiver
import android.content.Context
import android.content.Intent
import android.content.IntentFilter
import android.os.Build
import android.os.IBinder
import androidx.core.app.NotificationCompat
import java.net.HttpURLConnection
import java.net.URL


class ChargeService : Service() {

    private val NOTIFICATION_ID = 1
    private lateinit var notification: Notification

    override fun onCreate() {
        super.onCreate()
        createNotificationChannel()
        startForeground(NOTIFICATION_ID, createNotification())

        // Register for charging events
        val filter = IntentFilter().apply {
            addAction(Intent.ACTION_POWER_CONNECTED)
            addAction(Intent.ACTION_POWER_DISCONNECTED)
        }
        registerReceiver(chargingReceiver, filter)
    }

    private val chargingReceiver = object : BroadcastReceiver() {
        override fun onReceive(context: Context, intent: Intent) {
            val isCharging = intent.action == Intent.ACTION_POWER_CONNECTED
            sendToServer(if (isCharging) "charging on" else "charging off")
        }
    }

    private fun sendToServer(status: String) {
        Thread {
            try {
                val url = URL("https://kwynn.com/t/25/11/android.php")
                val conn = url.openConnection() as HttpURLConnection
                conn.requestMethod = "POST"
                conn.setRequestProperty("Content-Type", "application/json")
                conn.doOutput = true

                val json = "{\"status\":\"$status\"}"
                conn.outputStream.use { it.write(json.toByteArray()) }

                conn.responseCode // trigger request
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }.start()
    }

    private fun createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val channel = NotificationChannel(
                "charge_channel",
                "Charge Events",
                NotificationManager.IMPORTANCE_LOW
            )
            getSystemService(NotificationManager::class.java)
                .createNotificationChannel(channel)
        }
    }

    private fun createNotification(): Notification {
        val builder = NotificationCompat.Builder(this, "charge_channel")
            .setContentTitle("Charge Reporter")
            .setContentText("Monitoring charging events...")
            .setSmallIcon(android.R.drawable.ic_notification_overlay)
            .setPriority(NotificationCompat.PRIORITY_LOW)
            .setOngoing(true)

        notification = builder.build()
        return notification
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        return START_STICKY // Restart if killed
    }

    override fun onDestroy() {
        unregisterReceiver(chargingReceiver)
        super.onDestroy()
    }

    override fun onBind(intent: Intent?): IBinder? = null
}