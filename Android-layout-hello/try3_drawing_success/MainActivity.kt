// Kwynn - v5 - whittled more - 2024/08/04 05:42

package com.example.trydraw

import android.os.Bundle
import android.widget.ImageView
import androidx.appcompat.app.AppCompatActivity

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        val mImageView : ImageView = findViewById(R.id.image_view_1)
        DrawRange(mImageView, windowManager)
    }
}

/*

Starting from:

https://www.geeksforgeeks.org/how-to-draw-a-line-in-android-with-kotlin/

How to Draw a Line in Android with Kotlin?
Last Updated : 14 Feb, 2022

Then I used the "Basic Views Activity" in Android Studio and combined the two

 */